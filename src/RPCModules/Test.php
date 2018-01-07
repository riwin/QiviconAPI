<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace riwin\QiviconAPI\RPCModules;

/**
 * Description of Test
 *
 * @author Richi
 */
class Test {
    /*
     * setTunableWhiteValuesCommand
     * Tested with Philips Hue White Ambiance LED E27
     */

    public static function setDeviceState() {
        if (!isset($_GET['param_uid']) OR $_GET['param_uid'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_uid' fehlt oder ist leer.");
        }
        $uid = $_GET['param_uid'];
        if (!isset($_GET['param_state']) OR $_GET['param_state'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_state' fehlt oder ist leer.");
        }
        $state = $_GET['param_state'];

        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", [
                    'command' => 'setDeviceState',
                    'state' => $state,
                    'uid' => $uid]);
    }
}
