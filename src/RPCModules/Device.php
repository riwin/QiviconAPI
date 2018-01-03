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
 * Description of Device
 *
 * @author Richi
 */
class Device {

    public static function setHomeStatePresent() {
        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", [
                    'command' => 'setHomeState',
                    'homeState' => 'PRESENT']);
    }

    public static function setHomeStateAway() {
        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", [
                    'command' => 'setHomeState',
                    'homeState' => 'AWAY']);
    }

    public static function getBulbProperties() {
        if (!isset($_GET['param_uids']) OR $_GET['param_uids'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_uids' fehlt oder ist leer.");
        }
        $uids = [$_GET['param_uids']];

        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", [
                    'command' => 'getBulbProperties',
                    'uids' => $uids]);
    }

    public static function setDimmerCommand() {
        if (!isset($_GET['param_uid']) OR $_GET['param_uid'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_uid' fehlt oder ist leer.");
        }
        $uids = [$_GET['param_uid']];
        if (!isset($_GET['param_level']) OR $_GET['param_level'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_level' fehlt oder ist leer.");
        }
        $level = $_GET['param_level'];

        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", [
                    'command' => 'setSwitchLevel',
                    'level' => $level,
                    'uids' => $uids]);
    }

    /*
     * setHueCommand
     */

    public static function setHueCommand() {
        if (!isset($_GET['param_uid']) OR $_GET['param_uid'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_uid' fehlt oder ist leer.");
        }
        $uid = $_GET['param_uid'];
        if (!isset($_GET['param_isCombinedBulb']) OR $_GET['param_isCombinedBulb'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_isCombinedBulb' fehlt oder ist leer.");
        }
        $isCombinedBulb = $_GET['param_isCombinedBulb'];
        if (strtolower($isCombinedBulb) == "true") {
            $isCombinedBulb = true;
        } else {
            $isCombinedBulb = FALSE;
        }
        if (!isset($_GET['param_hue']) OR $_GET['param_hue'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_hue' fehlt oder ist leer.");
        }
        $hue = $_GET['param_hue'];
        if (!isset($_GET['param_saturation']) OR $_GET['param_saturation'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_saturation' fehlt oder ist leer.");
        }
        $saturation = $_GET['param_saturation'];
        if (!isset($_GET['param_brightness']) OR $_GET['param_brightness'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_brightness' fehlt oder ist leer.");
        }
        $brightness = $_GET['param_brightness'];

        if ($isCombinedBulb) {
            $value = $hue . "," . $saturation . "," . $brightness;
            return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", [
                        'command' => 'setDeviceValue',
                        'value' => $value,
                        'uid' => $uid]);
        } else {
            $uids = [$uid];
            return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", [
                        'command' => 'setBulbValue',
                        'hue' => $hue,
                        'saturation' => $saturation,
                        'brightness' => $brightness,
                        'uids' => $uids]);
        }
    }

    /*
     * setJunkersHotWaterState
     */

    public static function setJunkersHotWaterState() {
        if (!isset($_GET['param_uid']) OR $_GET['param_uid'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_uid' fehlt oder ist leer.");
        }
        $uid = [$_GET['param_uid']];
        if (!isset($_GET['param_state']) OR $_GET['param_state'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_state' fehlt oder ist leer.");
        }
        $state = $_GET['param_state'];
        switch ($state) {
            case 1:
                $value = "ON";

                break;
            case 0:
                $value = "OFF";
                break;

            default:
                throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_state' hat einen unerlaubten Wert. Erlaubte Werte sind [0, 1].");
                break;
        }

        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", [
                    'command' => 'setDeviceValue',
                    'value' => $value,
                    'uid' => $uid]);
    }

    /*
     * setMieleState
     */

    public static function setMieleState() {
        if (!isset($_GET['param_uid']) OR $_GET['param_uid'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_uid' fehlt oder ist leer.");
        }
        $uids = [$_GET['param_uid']];
        if (!isset($_GET['param_active']) OR $_GET['param_active'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_active' fehlt oder ist leer.");
        }
        $active = $_GET['param_active'];
        if (strtolower($active) == "true") {
            $active = true;
        } else {
            $active = FALSE;
        }
        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", [
                    'command' => 'setWGProperties',
                    'uids' => $uids,
                    'active' => $active]);
    }

    /*
     * setPlugState
     */

    public static function setPlugState() {
        if (!isset($_GET['param_uid']) OR $_GET['param_uid'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_uid' fehlt oder ist leer.");
        }
        $uids = [$_GET['param_uid']];
        if (!isset($_GET['param_state']) OR $_GET['param_state'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_state' fehlt oder ist leer.");
        }
        $state = $_GET['param_state'];
        switch ($state) {
            case 1:
                $command = "turnSwitchOn";

                break;
            case 0:
                $command = "turnSwitchOff";
                break;

            default:
                throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_state' hat einen unerlaubten Wert. Erlaubte Werte sind [0, 1].");
                break;
        }

        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", [
                    'command' => $command,
                    'uids' => $uids]);
    }

    /*
     * setShutterCommand
     */

    public static function setShutterCommand() {
        if (!isset($_GET['param_uid']) OR $_GET['param_uid'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_uid' fehlt oder ist leer.");
        }
        $uids = [$_GET['param_uid']];
        if (!isset($_GET['param_level']) OR $_GET['param_level'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_level' fehlt oder ist leer.");
        }
        $level = $_GET['param_level'];

        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", [
                    'command' => 'setBlindsLevel',
                    'level' => (int)$level,
                    'uids' => $uids]);
    }

    /*
     * setSonosControlPlayer
     */

    public static function setSonosControlPlayer() {
        if (!isset($_GET['param_uid']) OR $_GET['param_uid'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_uid' fehlt oder ist leer.");
        }
        $uids = [$_GET['param_uid']];
        if (!isset($_GET['param_control']) OR $_GET['param_control'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_control' fehlt oder ist leer.");
        }
        $control = $_GET['param_control'];
        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", [
                    'command' => 'controlPlayer',
                    'control' => $control,
                    'uids' => $uids]);
    }

    /*
     * setSonosVolume
     */

    public static function setSonosVolume() {
        if (!isset($_GET['param_uid']) OR $_GET['param_uid'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_uid' fehlt oder ist leer.");
        }
        $uids = [$_GET['param_uid']];
        if (!isset($_GET['param_volume']) OR $_GET['param_volume'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_volume' fehlt oder ist leer.");
        }
        $volume = $_GET['param_volume'];

        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", [
                    'command' => 'setVolume',
                    'volume' => $volume,
                    'uids' => $uids]);
    }

    /*
     * setTunableWhiteValuesCommand
     * Tested with Philips Hue White Ambiance LED E27
     */

    public static function setTunableWhiteValuesCommand() {
        if (!isset($_GET['param_uid']) OR $_GET['param_uid'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_uid' fehlt oder ist leer.");
        }
        $uid = $_GET['param_uid'];
        if (!isset($_GET['param_colorTemperature']) OR $_GET['param_colorTemperature'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_colorTemperature' fehlt oder ist leer.");
        }
        if (!isset($_GET['param_brightness']) OR $_GET['param_brightness'] == "") {
            throw new \riwin\QiviconAPI\Exceptions\QiviconAPIException("Der Parameter 'param_brightness' fehlt oder ist leer.");
        }
        $value = $_GET['param_colorTemperature'] . "," . $_GET['param_brightness'];

        return \riwin\QiviconAPI\QiviconAPI::getInstance()->RPC()->call("SMHM/executeCommand", [
                    'command' => 'setDeviceValue',
                    'value' => $value,
                    'uid' => $uid]);
    }

}
