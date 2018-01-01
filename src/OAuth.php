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
 * Description of OAuth
 *
 * @author Richard Nahm
 */
class OAuth {

    private $client_id;
    private $client_secret;
    private $oauth_hostname;
    private $oauth_token_endpoint;
    private $oauth_authorize_endpoint;
    private $scope;

    /**
     *
     * @var \riwin\QiviconAPI\AccessToken
     */
    private $access_token;

    /**
     *
     * @var \riwin\QiviconAPI\HTTPClient
     */
    private $httpClient;

    public function __construct($client_id, $client_secret, $oauth_hostname, $oauth_token_endpoint, $oauth_authorize_endpoint, $scope = null) {
        \riwin\Logger\Logger::debug("Constructing new OAuth-Instance...");
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->oauth_hostname = $oauth_hostname;
        $this->oauth_token_endpoint = $oauth_token_endpoint;
        $this->oauth_authorize_endpoint = $oauth_authorize_endpoint;
        $this->scope = $scope;
    }

    public function getAccessToken() {
        return $this->access_token;
    }

    public function authorizeByCredentials($username, $password) {
        \riwin\Logger\Logger::debug("Authorize User by credentials...");
        $request = new \riwin\QiviconAPI\HTTPRequest();
        $grant = [
            'grant_type' => 'password',
            'client_id' => $this->client_id,
            'username' => $username,
            'password' => $password
        ];
        if($this->oauth_hostname == "global.telekom.com") {
            $grant['scope'] = $this->scope;
        }
        $query = http_build_query($grant);
        $request->setURL($this->getTokenURL() . "?" . $query);
        $request->setMethod("POST");
        $request->setHeader("Authorization", $this->getAuthorizationHeader());
        $response = $request->make(\riwin\QiviconAPI\QiviconAPI::getInstance()->HTTPClient());

        $body = $response->getBody();
        $json = json_decode($body);
        //die("<pre>".$response->getHTTPStatusCode()."</pre>");
        if ($response->getHTTPStatusCode() != 200) {
            throw new Exceptions\OAuthException($json->error, $response->getHTTPStatusCode());
            return false;
        }
        $this->setAccessToken($body);
        $authorized = $this->isAuthorized();
        if ($authorized){
            \riwin\QiviconAPI\QiviconAPI::getInstance()->updateSession();
        }else{
            die($body);
            throw new Exceptions\OAuthException($body);
        }
        return $authorized;
    }

    public function authorizeByRefreshToken() {
        \riwin\Logger\Logger::debug("Authorize User by refresh token...");
        $request = new \riwin\QiviconAPI\HTTPRequest();
        $grant = [
            'scope' => $this->getAccessToken()->get('scope'),
            'grant_type' => "refresh_token",
            'refresh_token' => $this->getAccessToken()->get('refresh_token')
        ];
        $query = http_build_query($grant);
        $request->setURL($this->getTokenURL() . "?" . $query);
        $request->setMethod("POST");
        $request->setHeader("Authorization", $this->getAuthorizationHeader());
        $response = $request->make($this->httpClient);
        $body = $response->getBody();
        $json = json_decode($body);
        
        //die("<pre>".$response->getHTTPStatusCode()."</pre>");
        if ($response->getHTTPStatusCode() != 200) {
            throw new Exceptions\OAuthException($json->error, $response->getHTTPStatusCode());
        }
        $this->refreshAccessToken($body);
        $authorized = $this->isAuthorized();
        if ($authorized){
            \riwin\QiviconAPI\QiviconAPI::getInstance()->updateSession();
        }
        return $authorized;
    }

    private function getTokenURL() {
        return "https://" . $this->oauth_hostname . $this->oauth_token_endpoint;
    }

    private function getAuthorizeURL() {
        return "https://" . $this->oauth_hostname . $this->oauth_authorize_endpoint;
    }

    private function getAuthorizationHeader() {
        $header = $this->client_id . ":" . $this->client_secret;
        return "Basic " . base64_encode($header);
    }

    public function setAccessToken($json_token) {
        \riwin\Logger\Logger::debug("Setting AccessToken...");
        $this->access_token = new \riwin\QiviconAPI\AccessToken($json_token);
    }
    
    private function refreshAccessToken($json_token) {
        \riwin\Logger\Logger::debug("Refreshing AccessToken...");
        $this->access_token->refresh($json_token);
    }

    public function isAuthorized() {
        if ($this->access_token->get("access_token") != "") {
            if($this->access_token->isValid()){
                \riwin\Logger\Logger::debug("Is Authorized? Yes!");
                return true;
            }else{
                \riwin\Logger\Logger::debug("Is Authorized? No!");
                return false;
            }
        } else {
            \riwin\Logger\Logger::debug("Is Authorized? No!");
            return false;
        }
    }

}
