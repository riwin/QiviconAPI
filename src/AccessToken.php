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
 * Description of AccessToken
 *
 * @author Richard Nahm
 */
class AccessToken {

    public $access_token;
    public $token_type;
    public $expires_in;
    public $refresh_token;
    public $scope;
    private $exp;

    public function __construct($json) {
        \riwin\Logger\Logger::debug("Constructing new AccessToken-Instance...");
        $this->set($json);
    }

    private function set($json) {
        \riwin\Logger\Logger::debug("Setting AccessToken from json...");
        \riwin\Logger\Logger::debug("JSON: " . $json);
        $object = json_decode($json);
        if (!$object) {
            \riwin\Logger\Logger::error("Given $json is not valid!");
            return false;
        }
        if (isset($object->access_token)) {
            $this->access_token = $object->access_token;
            $this->token_type = $object->token_type;
            \riwin\Logger\Logger::info("New access_token: " . $this->token_type . " " . $this->access_token);
            $this->expires_in = $object->expires_in;
            if (isset($object->refresh_token)) {
                $this->refresh_token = $object->refresh_token;
                \riwin\Logger\Logger::info("New refresh_token: " . $this->refresh_token);
            }
            $this->scope = $object->scope;


            $parts = explode(".", $this->access_token);
            $infoObject = json_decode(base64_decode($parts[1]));
            $this->exp = (intval($infoObject->exp));
            
        } else {
            \riwin\Logger\Logger::error("Given $json-Object doesn't looks like a valid AccessToken!");
            throw new Exceptions\OAuthException("Given $json-Object doesn't look like a valid AccessToken!", 1000);
        }
    }
    
    public function getExpiration() {
        return $this->get("exp");
    }
    
    public function expiresIn(){
        return $this->getExpiration() - time();
    }
    
    public function hasExpired(){
        return ($this->expiresIn() <= 0);
    }
    
    public function expiresSoon() {
        return ($this->expiresIn() < (60*60));
    }


    public function isValid() {
        \riwin\Logger\Logger::debug("Expires: " . $this->getExpiration());
        \riwin\Logger\Logger::debug("Now: " . time());
        if (!$this->hasExpired()) {
            \riwin\Logger\Logger::info("Token expires in " . $this->expiresIn() . " seconds.");
            return true;
        } else {
            \riwin\Logger\Logger::warn("Token has expired");
            return false;
        }
    }

    public function refresh($json) {
        $this->set($json);
    }

    public function get($property) {
        if (isset($this->$property)) {
            return $this->$property;
        }
    }

    public function __toString() {
        return json_encode($this);
    }

}
