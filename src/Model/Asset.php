<?php

class Asset {
    public $parentAssetUid;
    public $envAssetUid;
    public $tfevtAssetUid;
    public $coordinates;

    // Environmental Planning Data
    public $temperature;
    public $humidity;
    public $pressure;

    // Traffic Planning Data
    public $vehicleSpeed;
    public $vehicleCount;

    // Situational Awareness Data
    public $photoUrl;
    public $videoUrl;

    function __construct($parentAssetUid, $envAssetUid, $coordinates, $temperature = 0, $humidity = 0, $pressure = 0, $vehicleSpeed = 0, $vehicleCount = 0) {
        $this->envAssetUid = $envAssetUid;
        $this->parentAssetUid = $parentAssetUid;
        $this->coordinates = $coordinates;
        $this->temperature = $temperature; // KELVIN
        $this->humidity = $humidity; // PASCALS
        $this->pressure = $pressure; // PASCALS
        $this->vehicleSpeed = $vehicleSpeed;
        $this->vehicleCount = $vehicleCount;
    }

    public static function parseNode($assetDataArray) {
        $asset = new Asset(
            $assetDataArray['parentAssetUid'],
            $assetDataArray['assetUid'],
            $assetDataArray['coordinates']
        );
        return $asset;
    }
    public static function parseNodes($assetAssociativeArray) {
        $assets = $assetAssociativeArray['content'];
        $assetArray = array();
        foreach($assets as &$asset) {
            array_push($assetArray,Asset::parseNode($asset));
        }
        unset($asset);
        return $assetArray;
    }

    public static function parseNodeAssetDetails(&$asset, $nodeData) {
        var_dump($nodeData);
    }
    public static function parseNodeEnvironmentalData(&$asset, $temperatureData, $humidityData, $pressureData){
        $asset->temperature = $temperatureData['content'][0]['measures']['mean'];
        $asset->humidity = $humidityData['content'][0]['measures']['mean'];
        $asset->pressure = $pressureData['content'][0]['measures']['mean'];
        if($GLOBALS['debug'] >= DebugVerbosity::LARGE) {
            var_dump($temperatureData);
        }
        return $asset;
    }
    public static function parseNodeTrafficData(&$asset, $trafficData){
        $asset->vehicleSpeed = $trafficData['content'][0]['measures']['speed'];
        $asset->vehicleCount = $trafficData['content'][0]['measures']['vehicleCount'];
        if($GLOBALS['debug'] >= DebugVerbosity::MEDIUM) {
            var_dump($trafficData);
        }
        return $asset;
    }
    public static function parseNodeAwarenessData(&$asset, $awarenessData){
        // TODO: Implement and remove mock data
        $asset->photoUrl = "https://ic-media-service.run.asv-pr.ice.predix.io/v2/mediastore/file/CAMERA-HYP1083-CAM-L_CAMERA-HYP1083-CAM-L_1459816592756_IMAGE.jpg";
    }
}