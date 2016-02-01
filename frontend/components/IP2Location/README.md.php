Installation
============
* Extract this package to `protected/extensions`
* Add following lines into `main.php` configuration file:

    	'components' => array(
    		...
    		'ip2location' => array(
    			'class' => 'application.extensions.ip2location.Geolocation',
    			'database' => 'C:\path\to\IP2Location\IP2LOCATION-LITE-DB1.BIN',
    			'mode' => 'FILE_IO',
    		),
    		...
    	),


Usage
=====
Use the following methods to retrieve geolocation information.
If $ip not provided, value from CHttpRequest::getUserHostAddress() will be used.

    $countryCode = Yii::$app->ip2location->getCountryCode($ip);
    $countryName = Yii::$app->ip2location->getCountryName($ip);
    $regionName = Yii::$app->ip2location->getRegionName($ip);
    $cityName = Yii::$app->ip2location->getCityName($ip);
    $latitude = Yii::$app->ip2location->getLatitude($ip);
    $longitude = Yii::$app->ip2location->getLongitude($ip);
    $isp = Yii::$app->ip2location->getISP($ip);
    $domainName = Yii::$app->ip2location->getDomainName($ip);
    $zipCode = Yii::$app->ip2location->getZIPCode($ip);
    $timeZone = Yii::$app->ip2location->getTimeZone($ip);
    $netSpeed = Yii::$app->ip2location->getNetSpeed($ip);
    $iddCode = Yii::$app->ip2location->getIDDCode($ip);
    $areaCode = Yii::$app->ip2location->getAreaCode($ip);
    $weatherStationCode = Yii::$app->ip2location->getWeatherStationCode($ip);
    $weatherStationName = Yii::$app->ip2location->getWeatherStationName($ip);
    $mcc = Yii::$app->ip2location->getMCC($ip);
    $mnc = Yii::$app->ip2location->getMNC($ip);
    $mobileCarrierName = Yii::$app->ip2location->getMobileCarrierName($ip);
    $elevation = Yii::$app->ip2location->getElevation($ip);
    $usageType = Yii::$app->ip2location->getUsageType($ip);


Database Update
===============
IP2Location database is updated monthly. You can get the latest database from http://www.ip2location.com (Commercial version) or
http://lite.ip2location.com (Free version).