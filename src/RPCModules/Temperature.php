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

namespace riwin\QiviconAPI\RPCModules;

/**
 * Description of Temperature
 *
 * @author Richard Nahm
 */
class Temperature {
    public static function setDeviceTemperature() {
        if (!isset($_GET['param_uid']) OR $_GET['param_uid'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_uid' fehlt oder ist leer.");
        }
        $uids = [$_GET['param_uid']];
        if (!isset($_GET['param_targetTemperature']) OR $_GET['param_targetTemperature'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_targetTemperature' fehlt oder ist leer.");
        }
        $targetTemperature = $_GET['param_targetTemperature'];

        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", [
                    'command' => 'setTargetTemperature',
                    'targetTemperature' => floatval($targetTemperature),
                    'uids' => $uids]);
    }
    
    public static function setJunkersHotWaterDeviceTemperature() {
        if (!isset($_GET['param_uid']) OR $_GET['param_uid'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_uid' fehlt oder ist leer.");
        }
        $uid = $_GET['param_uid'];
        if (!isset($_GET['param_targetTemperature']) OR $_GET['param_targetTemperature'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_targetTemperature' fehlt oder ist leer.");
        }
        $targetTemperature = $_GET['param_targetTemperature'];

        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", [
                    'command' => 'setDeviceValue',
                    'value' => floatval($targetTemperature),
                    'uid' => $uid]);
    }
    
    public static function setRoomTemperature() {
        if (!isset($_GET['param_room']) OR $_GET['param_room'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_room' fehlt oder ist leer.");
        }
        $param_room = $_GET['param_room'];
        if (!isset($_GET['param_targetTemperature']) OR $_GET['param_targetTemperature'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_targetTemperature' fehlt oder ist leer.");
        }
        $targetTemperature = $_GET['param_targetTemperature'];

        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", [
                    'command' => 'setRoomTemperature',
                    'level' => floatval($targetTemperature),
                    'room' => $room]);
    }
}
