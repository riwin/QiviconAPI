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
 * Description of QiviconAPI
 *
 * @author Richard Nahm
 */
class QiviconAPI {
    //put your code here

    /**
     *
     * @var \riwin\QiviconAPI\OAuth
     */
    private $oauth;

    /**
     *
     * @var \riwin\QiviconAPI\RPC
     */
    private $rpc;

    /**
     *
     * @var \riwin\QiviconAPI\HTTPClient
     */
    private $httpClient;

    /**
     *
     * @var \riwin\QiviconAPI\QiviconAPI 
     */
    private static $instance;

    /**
     *
     * @var \WebSocket\Client 
     */
    private $websocketClient;

    /**
     *
     * @var string
     */
    private $hostname;

    /**
     * 
     * @var string
     */
    private $serialNumber;

    /**
     * 
     * @return \riwin\QiviconAPI\QiviconAPI
     */
    public static function getInstance() {
        return static::$instance;
    }

    /**
     * 
     * @param \riwin\QiviconAPI\QiviconAPI $instance
     */
    public static function setInstance(\riwin\QiviconAPI\QiviconAPI $instance) {
        \riwin\Logger\Logger::debug("Setting static QiviconAPI-Instance...");
        static::$instance = $instance;
    }

    public function __construct($hostname, $serialNumber, $username, $password) {
        \riwin\Logger\Logger::setLogLevel(\riwin\Logger\Logger::LEVEL_INFO);
        \riwin\Logger\Logger::debug("Constructing new QiviconAPI-Instance...");
        \riwin\Logger\Logger::debug("Starting session...");
        ini_set('session.gc_maxlifetime', 28800);
        session_set_cookie_params(28800);
        session_start();
        $this->hostname = $hostname;
        $this->serialNumber = $serialNumber;
        $this->httpClient = new \riwin\QiviconAPI\HTTPClient();

        if ($hostname !== "global.telekom.com") {

            /*
             * local
             */

            $this->oauth = new \riwin\QiviconAPI\OAuth("de.telekom.smarthomeb2c.core.aos", "YFBAYJU5P6QCYKUT3J3I", $hostname, "/system/oauth/token", "/system/oauth/authorize", null);
        } else {
            /*
             * remote
             */

            $this->oauth = new \riwin\QiviconAPI\OAuth("yERCcGPUR9", "", $hostname, "/gcp-web-api/oauth", "/gcp-web-api/oauth", "TB4DAJ7S");
        }
        QiviconAPI::setInstance($this);
        $this->rpc = new \riwin\QiviconAPI\RPC($hostname, $serialNumber);
        if (!$this->restoreSession()) {
            \riwin\Logger\Logger::debug("Session restore failed. Trying Credentials...");
            if (!$this->login($username, $password)) {
                throw new \riwin\QiviconAPI\Exceptions\OAuthException("Please Check your User-Credentials!");
            }
        }
    }

    public function execute($asJson = true, $withLog = true) {
        $out = [];
        try {

            if (isset($_GET['getAccessToken'])) {
                $out['token'] = $this->OAuth()->getAccessToken();
            }
            if (isset($_GET['logout'])) {
                session_destroy();
                $out['logout'] = true;
            } elseif (!isset($_GET['module']) OR $_GET['module'] == "") {
                $modules = scandir(__DIR__ . '\\RPCModules', SCANDIR_SORT_ASCENDING);
                $available_modules = [];
                foreach ($modules as $index => $module) {
                    if($module === '.'){
                        unset($modules[$index]);
                    }elseif($module === '..'){
                        unset($modules[$index]);
                    }else{
                    $available_modules[] = str_replace('.php', '', $module);
                    }
                }
                $out['available_modules'] = $available_modules;
                $out['help'] = "Use one of the listed modules with the GET-Parameter 'module'. Example: /?module=" . $available_modules[0];
                //throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter "module' fehlt oder ist leer.");
            } elseif (!isset($_GET['cmd']) OR $_GET['cmd'] == "") {
                $out['available_commands'] = get_class_methods('\\riwin\\QiviconAPI\\RPCModules\\' . $_GET['module']);
                $out['help'] = "Use one of the listed commands with the GET-Parameter 'cmd'. Example: /?module=" . $_GET['module'] . "&cmd=". $out['available_commands'][0];
                //throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'cmd' fehlt oder ist leer.");
            } else {
                $response = json_decode(call_user_func('\\riwin\\QiviconAPI\\RPCModules\\' . $_GET['module'] . '::' . $_GET['cmd'])->getBody());
                $out['rpc_response'] = $response;
            }
        } catch (Exception $exc) {
            $out['error'] = $this->renderException($exc);
        } catch (\riwin\QiviconAPI\Exceptions\HTTPClientException $exc) {
            $out['error'] = $this->renderException($exc);
        } catch (\riwin\QiviconAPI\Exceptions\OAuthException $exc) {
            $out['error'] = $this->renderException($exc);
        } catch (\riwin\QiviconAPI\Exceptions\QiviconAPIException $exc) {
            $out['error'] = $this->renderException($exc);
        } catch (\riwin\QiviconAPI\Exceptions\RPCException $exc) {
            $out['error'] = $this->renderException($exc);
        } finally {
            
        }

        if ($withLog) {
            $out['log'] = \riwin\Logger\Logger::get();
        }
        if ($asJson) {
            $out = json_encode($out);
        }
        return $out;
    }

    private function renderException($exc) {
        $__className = get_class($exc);
        $__classNameArr = explode("\\", $__className);
        $type = $__classNameArr[count($__classNameArr) - 1];
        $error = [
            'message' => $exc->getMessage(),
            'type' => $type,
            'code' => $exc->getCode()
        ];
        $error = (object) $error;
        return $error;
    }

    public function updateSession() {
        \riwin\Logger\Logger::debug("Updating Session...");
        $session['access_token'] = (string) $this->oauth->getAccessToken();
        \riwin\Logger\Logger::info("Updating Session to " . $session['access_token']);
        $_SESSION['riwin.QiviconAPI'] = $session;
    }

    private function restoreSession() {
        \riwin\Logger\Logger::debug("Restoring Session...");
        if (isset($_SESSION['riwin.QiviconAPI'])) {
            \riwin\Logger\Logger::debug("Found \"riwin.QiviconAPI\".");
            $session = $_SESSION['riwin.QiviconAPI'];
            $this->oauth->setAccessToken($session['access_token']);
            \riwin\Logger\Logger::debug("Session restored.");
            $authorized = $this->isAuthorized();
            if ($authorized) {
                if ($this->oauth->getAccessToken()->expiresSoon()) {
                    $this->oauth->authorizeByRefreshToken();
                    $authorized = $this->isAuthorized();
                }
            }
            return $authorized;
        } else {
            \riwin\Logger\Logger::debug("No Session-Data found.");
            return false;
        }
    }

    public function login($username, $password) {
        \riwin\Logger\Logger::debug("Logging in...");
        return $this->oauth->authorizeByCredentials($username, $password);
    }

    public function isAuthorized() {
        return $this->oauth->isAuthorized();
    }

    public function OAuth() {
        return $this->oauth;
    }

    public function RPC() {
        return $this->rpc;
    }

    public function HTTPClient() {
        return $this->httpClient;
    }

    public function WebsocketClient() {
        return $this->websocketClient;
    }

}
