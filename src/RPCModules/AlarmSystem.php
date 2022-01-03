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
 * Description of AlarmSystem
 *
 * @author Richard Nahm
 */
class AlarmSystem {

    public static function activateAlarmSystem() {
        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", ['command' => 'activateAlarmSystem']);
    }
    public static function deactivateAlarmSystem() {
        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", ['command' => 'deactivateAlarmSystem']);
    }
    public static function deactivateAlarm() {
        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", ['command' => 'deactivateAlarm']);
    }
    
    public static function getAlarmSystemProperties() {
        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", ['command' => 'getAlarmSystemProperties']); 
    }
    
    public static function getPinProperties() {
        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", ['command' => 'getLockModePinProperties']);
    }
    
    // only used internaly (private)
    private static function __setAlarmSystemProperties($properties) {
        $params = ['command' => 'setAlarmSystemProperties'];
        if(isset($_GET['Profile']) && $_GET['Profile'] !== ""){
            $params['profile'] = $_GET['Profile'];
        }
        foreach ($properties as $property => $value) {
            $params[$property] = $value;
        }        
        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", $params);
    }
    
    public static function setAlarmSystemProperty() {
        return AlarmSystem::__setAlarmSystemProperties([$GET_['PropertyName'] => $_GET['PropertyValue']]); 
    }
    
    public static function setAlarmSystemProperties() {
        return AlarmSystem::__setAlarmSystemProperties(json_decode($_GET['Properties']));
    }
    
}
