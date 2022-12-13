<?php
/*
	date_default_timezone_set("Asia/Kuala_Lumpur");
// phpinfo();
$latlong= '25.317644,82.973915';
// $APIKEY = "AIzaSyDgH5mAegqWsg3LTQ8LSJW2RlMofmRsOfg"; // Replace this with your google maps api key
$APIKEY = "AIzaSyBItTTXWsLZVRnRyhEzwfC3pO__QzDKSQo"; // Replace this with your google maps api key
$googleMapsUrl = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . $latlong . "&language=en&key=" . $APIKEY;
$response = file_get_contents($googleMapsUrl);
$response = json_decode($response, true);
$results = $response["results"];
if(isset($results[0]["address_components"])){
$addressComponents = $results[0]["address_components"];
$cityName = "";
foreach ($addressComponents as $component) {
    // echo $component;
    $types = $component["types"];
    if (in_array("administrative_area_level_1", $types) && in_array("political", $types)) {
        $cityName = $component["long_name"];
    }
}
if ($cityName == "") {
    echo "Failed to get CityName";
    echo "Error";
} else {
    echo $cityName;
}
}
else
    { echo "location not detected"; }
*/
phpinfo();
?>