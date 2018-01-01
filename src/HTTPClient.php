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
 * Description of HTTPClient
 *
 * @author Richard Nahm
 */
class HTTPClient {
    //put your code here

    /**
     *
     * @var \riwin\QiviconAPI\HTTPRequest 
     */
    private $request;
    private $response;
    private $ch;
    private $defaults = array(
        CURLOPT_HEADER => 1,
        CURLOPT_FRESH_CONNECT => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FORBID_REUSE => 1,
        CURLOPT_TIMEOUT => 4,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_AUTOREFERER => true
    );
    private $options = array();

    public function setOption($option, $value) {
        $this->options[$option] = $value;
    }

    public function getDefaults() {
        return $this->defaults;
    }

    public function getOptions() {
        return $this->options;
    }

    public function setRequest(HTTPRequest $request) {
        $this->request = $request;
    }

    public function makeRequest(HTTPRequest $request) {
        $this->setRequest($request);
        $this->ch = curl_init();
        $this->request->setHeader("User-Agent", "QiviconAPI/1.0 (https://github.com/riwin/QiviconAPI)");
        $this->setOption(CURLOPT_URL, $this->request->getURL());
        $this->setOption(CURLOPT_HTTPHEADER, $this->request->getHeaders());
        switch ($this->request->getMethod()) {
            case "GET":
                $this->setOption(CURLOPT_HTTPGET, 1);
                break;

            case "POST":
                $this->setOption(CURLOPT_POST, 1);
                if ($this->request->getPostFields() === NULL) {
                    $data = $this->request->getBody();
                } else {

                    $data = http_build_query($this->request->getPostFields());
                }
                $this->setOption(CURLOPT_POSTFIELDS, $data);
                break;

            case "PUT":
                $this->setOption(CURLOPT_CUSTOMREQUEST, "PUT");
                break;

            case "DELETE":
                $this->setOption(CURLOPT_CUSTOMREQUEST, "DELETE");
                break;

            case "OPTIONS":
                $this->setOption(CURLOPT_CUSTOMREQUEST, "OPTIONS");
                break;

            default:
                break;
        }
        curl_setopt_array($this->ch, ($this->getOptions() + $this->getDefaults()));
        $response = curl_exec($this->ch);
        $responseObj = new HTTPResponse();
        $__error = curl_error($this->ch);
        
        if ( $__error != "" )
        {
            throw new Exceptions\HTTPClientException($__error, curl_errno($this->ch));
            // return false;
        }
       
        $__header_size = curl_getinfo($this->ch,CURLINFO_HEADER_SIZE);
        $responseObj->setHeader(substr($response, 0, $__header_size));
        $responseObj->setBody(substr( $response, $__header_size ));
        $responseObj->setStatusCode(curl_getinfo($this->ch,CURLINFO_HTTP_CODE));
        
        curl_close($this->ch);
        return $responseObj;
        //return new HTTPResponse($response);
    }

}
