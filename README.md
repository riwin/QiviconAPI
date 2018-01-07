# QiviconAPI
## Homebase Kompatibilität:
[![QHB](https://img.shields.io/badge/QIVICON_Home_Base-nicht_getestet-orange.svg?style=flat-square)](https://www.qivicon.com/de/produkte/produktinformationen/qivicon-home-base/) 
[![QHB2](https://img.shields.io/badge/QIVICON_Home_Base_2.0-kompatibel-brightgreen.svg?style=flat-square)](https://www.qivicon.com/de/produkte/produktinformationen/qivicon-home-base-2-0/) 
[![SPS](https://img.shields.io/badge/Speedport_Smart-kompatibel-brightgreen.svg?style=flat-square)](https://www.qivicon.com/de/produkte/produktinformationen/telekom-speedport-smart)

## Status:
[![Packagist](https://img.shields.io/packagist/v/riwin/qivicon-api.svg?style=flat-square)](https://packagist.org/packages/riwin/qivicon-api) [![license](https://img.shields.io/github/license/riwin/qivicon-api.svg?style=flat-square)](https://github.com/riwin/QiviconAPI/blob/master/LICENSE) [![GitHub issues](https://img.shields.io/github/issues/riwin/qivicon-api.svg?style=flat-square)](https://github.com/riwin/QiviconAPI/issues) ![GitHub repo size in bytes](https://img.shields.io/github/repo-size/riwin/qivicon-api.svg?style=flat-square)
## Installation:

```
composer require riwin/qivicon-api
```

oder

```json
{
    "require": {
        "riwin/qivicon-api": "1.*"
    }
}
```


## API einbinden:

```php
<?php
require_once 'vendor/autoload.php';
header("Content-Type: application/json");
$api = new \riwin\QiviconAPI\QiviconAPI("hostname-Homebase", "email@mein.qivicon", "Passwort");
print_r($api->execute());
```



## Modul - AlarmSystem:

### Alarm scharf schalten:
```
/index.php?module=AlarmSystem&cmd=activateAlarmSystem
```

### Alarm unscharf schalten:
/index.php?module=AlarmSystem&cmd=deactivateAlarmSystem

### Ausgelösten Alarm beenden:
```
/index.php?module=AlarmSystem&cmd=deactivateAlarm
```

### Alarm System Eigenschaften anzeigen:
```
/index.php?module=AlarmSystem&cmd=getAlarmSystemProperties
```


## Modul - Generic

### Dashboard-Info:
```
/index.php?module=Generic&cmd=getDashboardInfo
```

### Homebase Eigenschaften:
```
/index.php?module=Generic&cmd=getHomeboxProperties
```

### Räume mit Geräten/Kanälen:
```
/index.php?module=Generic&cmd=listRooms
```


## Modul - Situation

### Haushüter an:
```
/index.php?module=Situation&cmd=activateVirtualResident
```

### Haushüter aus:
```
/index.php?module=Situation&cmd=deactivateVirtualResident
```

### Haushüter Ereignisse:
```
/index.php?module=Situation&cmd=getVirtualResidentProperties
```

### Situationen auflisten:
```
/index.php?module=Situation&cmd=listSituations
```

### Situation anzeigen:
```
/index.php?module=Situation&cmd=getSituation&param_id={situationId}
```

### Situation de- / aktivieren:
```
/index.php?module=Situation&cmd=setSituationState&param_id={situationId}&param_active={true|false}
```

### Situation löschen:
```
/index.php?module=Situation&cmd=removeSituation&param_id={situationId}
```

### Verfügbare Soundfiles auflisten:
```
/index.php?module=Situation&cmd=listAvailableSoundFiles
```


## Modul - Notification

### Benachrichtigungen auflisten:
```
/index.php?module=Notification&cmd=listNotifications
```



## Modul - Device

### Anwesend einstellen:
```
/index.php?module=Device&cmd=setHomeStatePresent
```

### Abwesend einstellen:
```
/index.php?module=Device&cmd=setHomeStateAway
```

### Dimmer einstellen (0-100):
```
/index.php?module=Device&cmd=setDimmerCommand&param_uid={uid}&param_level={0-100}
```

### setHueCommand
```
/index.php?module=Device&cmd=setHueCommand&param_uid={uid}&param_isCombinedBulb={true|false}&param_hue={0-360}&param_saturation={0-100}&param_brightness={0-100}
```

### setJunkersHotWaterState
```
/index.php?module=Device&cmd=setJunkersHotWaterState&param_uid={uid}&param_state={0-1}
```

### setMieleState
```
/index.php?module=Device&cmd=setMieleState&param_uid={uid}&param_active={true|false}
```

### setPlugState
```
/index.php?module=Device&cmd=setPlugState&param_uid={uid}&param_state={0-1}
```

### setShutterCommand
```
/index.php?module=Device&cmd=setShutterCommand&param_uid={uid}&param_level={0-100}
```

### setSonosControlPlayer
```
/index.php?module=Device&cmd=setSonosControlPlayer&param_uid={uid}&param_control={PLAY,PAUSE,PREVIOUS,NEXT}
```

### setSonosVolume
```
/index.php?module=Device&cmd=setSonosVolume&param_uid={uid}&param_volume={0-100}
```

### setTunableWhiteValuesCommand
```
/index.php?module=Device&cmd=setTunableWhiteValuesCommand&param_uid={uid}&param_brightness={0-100}&param_colorTemperature={0-100}
```

### setDeviceValue
```
Philips Hue White Ambiance:
/index.php?module=Device&cmd=setDeviceValue&param_uid={uid}&param_value={0-100@on|off}
```



## Modul - Temperature

### setDeviceTemperature:
```
/index.php?module=Temperature&cmd=setDeviceTemperature&param_uid={uid}&param_targetTemperature={0.0-35.0}
```

### setJunkersHotWaterDeviceTemperature:
```
/index.php?module=Temperature&cmd=setJunkersHotWaterDeviceTemperature&param_uid={uid}&param_targetTemperature={temperature}
```

### setRoomTemperature:
```
/index.php?module=Temperature&cmd=setRoomTemperature&param_room={room}&param_targetTemperature={temperature}
```





### Sitzung beenden (logout):
```
/index.php?logout
```

