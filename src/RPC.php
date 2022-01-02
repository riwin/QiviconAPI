<?php

/*
 * MIT License
 * 
 * Copyright (c) 2017 Richard Nahm
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace riwin\QiviconAPI;

/**
 * Description of RPC
 *
 * @author Nahms
 */
class RPC {

    private $hostname;
    private $serialNumber;

    /**
     *
     * @var \riwin\QiviconAPI\HTTPRequest
     */
    private $request;

    /**
     *
     * @var \riwin\QiviconAPI\HTTPResponse
     */
    private $response;

    public function __construct($hostname, $serialNumber) {
        $this->hostname = $hostname;
        $this->serialNumber = $serialNumber;
    }

    public function call($method, $params) {
        \riwin\Logger\Logger::debug("New RPC...");
        $api = \riwin\QiviconAPI\QiviconAPI::getInstance();
        if ($api->isAuthorized()) {
            $this->request = new \riwin\QiviconAPI\HTTPRequest();
            $this->request->setMethod("POST");
            if ($this->hostname !== "global.telekom.com") {
                $this->request->setURL("https://" . $this->hostname . "/remote/json-rpc");
            } else {
                $this->request->setURL("https://ris-prod-es.cloud.qivicon.com/gateway/json-rpc");
            }
            $this->request->setHeader("Authorization", "Bearer " . \riwin\QiviconAPI\QiviconAPI::getInstance()->OAuth()->getAccessToken()->get("access_token"));
            $this->request->setHeader("QIVICON-ServiceGatewayId", $this->serialNumber);
            $body = [
                'jsonrpc' => '2.0',
                'method' => $method,
                'params' => $params,
                'id' => md5(time() . "." . mt_rand() . "." . $method)
            ];
            $this->request->setBody($body);
            \riwin\Logger\Logger::info($this->request->getBody());
            $this->response = $this->request->make(\riwin\QiviconAPI\QiviconAPI::getInstance()->HTTPClient());
            $responseBody = json_decode($this->response->getBody());
            /*
             * validationError
             */
            if (isset($responseBody->result->state) AND $responseBody->result->state == "validationError") {
                throw new \riwin\QiviconAPI\Exceptions\RPCException($responseBody->result->state . ": " . $responseBody->result->result[0]);
            }elseif (isset($responseBody->result->state) AND $responseBody->result->state == "unsuccessful") {
                throw new \riwin\QiviconAPI\Exceptions\RPCException($responseBody->result->state);
            }elseif (isset($responseBody->result->state) AND $responseBody->result->state !== "ok") {
                throw new \riwin\QiviconAPI\Exceptions\RPCException($responseBody->result->state . ": " . $responseBody->result->result[0]);
            }
            \riwin\Logger\Logger::debug($this->response->getBody());
            return $this->response;
        }
    }

}
