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
 * Description of HTTPRequest
 *
 * @author Richard Nahm
 */
class HTTPRequest {
    private $headers = array();
    private $body = NULL;
    private $URL;
    private $method;
    private $postFields = NULL;
    public function setHeader($name, $value) {
        $this->headers[$name] = $value;
        return $this;
    }
    
    public function hasHeader($name) {
        return isset($this->headers[$name]);
    }
    
    public function getHeader($name) {
        if($this->hasHeader($name)) {
            return $this->headers[$name];
        }
    }
    
    public function getHeaders() {
        $headers = array();
        foreach ($this->headers as $header => $value) {
            $headers[] = $header . ": " . $value;
        }
        return $headers;
    }
    
    public function setBody($content, $json = true){
        if($json) {
            $this->body = json_encode($content);
        }else{
            $this->body = $content;
        }
        return $this;
    }
    
    public function getBody() {
        return $this->body;
    }
    
    public function setURL($url){
        $this->URL = $url;
        return $this;
    }
    
    public function getURL() {
        return $this->URL;
    }
    
    public function setMethod($method) {
        $this->method = $method;
        return $this;
    }
    
    public function getMethod() {
        return $this->method;
    }
    
    public function setPostFields(array $fields) {
        $this->postFields = $fields;
        return $this->setMethod("POST");
    }
    
    public function getPostFields() {
        return $this->postFields;
    }
    
    /**
     * 
     * @param \riwin\QiviconAPI\HTTPClient $client
     * @returns \riwin\QiviconAPI\HTTPResponse
     */
    
    public function make(HTTPClient $client) {
        return $client->makeRequest($this);
    }
    //put your code here
}
