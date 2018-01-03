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
 * Description of Situation
 *
 * @author Nahms
 */
class Situation {
    
    public static function activateVirtualResident($blnState) {
        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", ['command' => 'activateVirtualResident']);
    }
    
    public static function deactivateVirtualResident($blnState) {
        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", ['command' => 'deactivateVirtualResident']);
    }

    public static function getVirtualResidentProperties() {
        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", ['command' => 'getVRProperties']);
    }

    public static function listSituations() {
        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", ['command' => 'listSituations']);
    }

    public static function getSituation() {
        if(!isset($_GET['param_id']) OR $_GET['param_id'] == ""){
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_id' fehlt oder ist leer.");
        }
        $id = $_GET['param_id'];
        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", ['command' => 'getSituation',
                                                                                                'situationId' => $id]);
    }

    public static function setSituationState() {
        if(!isset($_GET['param_id']) OR $_GET['param_id'] == ""){
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_id' fehlt oder ist leer.");
        }
        $id = $_GET['param_id'];
        if(!isset($_GET['param_active']) OR $_GET['param_active'] == ""){
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_active' fehlt oder ist leer.");
        }
        $active = $_GET['param_active'];
        if($active == "true"){
            $active = true;
        }else{
            $active = FALSE;
        }
        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", ['command' => 'setSituationState',
                                                                                                'situationId' => $id,
                                                                                                'active' => $active]);
    }

    public static function listKeyBindings() {
        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", ['command' => 'listKeyBindings']);
    }
/**
 * listAvailableSoundFiles
 */

    public static function listAvailableSoundFiles() {
        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", ['command' => 'listAvailableSoundFiles']);
    }
}
