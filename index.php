<?php
// function to get location
function get_location($address){
    $address = str_replace(" ", "+", $address);
    $getjson_content = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyBmVD9vSThqp7gggOXQHB8aNOzC8gjyvLA");
    // decode the json
    $mapjson = json_decode($getjson_content, true);
    // check if status 'OK'
    if($mapjson['status']=='OK'){

        // get latitude and longitude
        $lati = $mapjson['results'][0]['geometry']['location']['lat'];
        $longi =  $mapjson['results'][0]['geometry']['location']['lng'];
        $location_array = array('lat'=> $lati ,'lng'=>$longi);
    }
    return $location_array;
}


if(isset($_GET['address'])){
    // get latitude, longitude
    $location_array = get_location($_GET['address']);

    if($location_array){
        $latitude = $location_array['lat'];
        $longitude = $location_array['lng'];

    }else{
        echo "Map not found";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>PHP Google Map</title>

	<style>
	#map{
		width:100%;
		height:40em;
	}
	</style>

</head>
<body>

  <!-- enter any address -->
  <form action="" method="get">
  	<input type='text' name='address' placeholder='Enter any address here' />
  	<input type='submit' value='Search' /><br>
  </form>

  <p>Latitude: <?php echo $latitude; ?></p>
  <p> Longitude: <?php echo $longitude; ?></p>

	<!-- Show map in div -->
	<div id="map">Please enter a location...</div>
	<!-- use api key-->
  <script async defer
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmVD9vSThqp7gggOXQHB8aNOzC8gjyvLA&callback=initMap">
  </script>
	<script type="text/javascript">
		function initMap() {
       var map = new google.maps.Map(document.getElementById('map'), {
              zoom: 13,
              center: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>)
            });//show map
            marker = new google.maps.Marker({
                map: map,
                position: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>)
            });
        }
    </script>
</body>
</html>
<!--Refernce Link:
Search location display : https://developers.google.com/maps/documentation/javascript/examples/geocoding-simple
Get location via PHP: https://www.codeofaninja.com/2014/06/google-maps-geocoding-example-php.html
-->
