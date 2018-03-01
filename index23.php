<?php
// function to geocode address, it will return false if unable to geocode address
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
//DO THE SAME BUT TO GET DIRECTION VALUE
// function to get location
function get_destination($address){
    $address = str_replace(" ", "+", $address);
    $getjson_content = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyBmVD9vSThqp7gggOXQHB8aNOzC8gjyvLA");
    // decode the json
    $mapjson = json_decode($getjson_content, true);
    // check if status 'OK'
    if($mapjson['status']=='OK'){

        // get latitude and longitude
        $lati2 = $mapjson['results'][0]['geometry']['location']['lat'];
        $longi2 =  $mapjson['results'][0]['geometry']['location']['lng'];
        $destination_array = array('lat'=> $lati2 ,'lng'=>$longi2);
    }
    return $destination_array;
}

if(isset($_GET['address'])){
    // get latitude, longitude
      $location_array = get_location($_GET['address']);
      $destination_array = get_destination($_GET['address2']);
    // if able to geocode the address
    if($location_array && $destination_array){
      $latitude = $location_array['lat'];
      $longitude = $location_array['lng'];
      $latitude2 = $destination_array['lat'];
      $longitude2 = $destination_array['lng'];
    }else{
        echo "No map found or enter 2 location";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>PHP Google Map direction</title>
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
  	<input type='submit' value='Geocode!' /><br>
    <input type='text' name='address2' placeholder='Enter destination here' />

  </form>

  <div id="journey">
  Distance:
  </div>

	<!-- google map will be shown here -->
	<div id="map">Loading map...</div>
	<!-- JavaScript to show google map -->
  <script async defer
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmVD9vSThqp7gggOXQHB8aNOzC8gjyvLA&callback=initMap">
  </script>
	<script type="text/javascript">
		function initMap() {
      var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 13,
          center: {lat: 40.771, lng: -73.974}
        });
      var markerArray = [];
      // Instantiate a directions service.
      var directionsService = new google.maps.DirectionsService;
      // Create a renderer for directions and bind it to the map.
      var directionsDisplay = new google.maps.DirectionsRenderer({map: map});
      // Instantiate an info window to hold step text.
      var stepDisplay = new google.maps.InfoWindow;


      for (var i = 0; i < markerArray.length; i++) {
        markerArray[i].setMap(null);
      }

      // Retrieve the start and end locations and create a DirectionsRequest using
      // WALKING directions.
      directionsService.route({
        origin: {lat:<?php echo $latitude; ?>,lng:<?php echo $longitude; ?>},
        destination: {lat:<?php echo $latitude2; ?>,lng:<?php echo $longitude2; ?>},
        travelMode: 'WALKING'
      }, function(response, status) {
        // Route the directions and pass the response to a function to create
        // markers for each step.
        if (status === 'OK') {
          directionsDisplay.setDirections(response);
          //DISTANCE

        } else {
          window.alert('Directions request failed due to ' + status);
        }
      });

            var service = new google.maps.DistanceMatrixService;
            service.getDistanceMatrix({
                    origins: [{lat:<?php echo $latitude; ?>,lng:<?php echo $longitude; ?>}],
                    destinations: [{lat:<?php echo $latitude2; ?>,lng:<?php echo $longitude2; ?>}],
                    travelMode: 'DRIVING',
                  }, function(response, status) {
                    if (status !== 'OK') {
                      alert('Error was: ' + status);
                    } else {
                      var originList = response.originAddresses;
                      var destinationList = response.destinationAddresses;
                      for(var i =0; i<originList.length; i++){
                        var results = response.rows[i].elements;
                        for(var j=0; j<results.length;j++){
                        var element = results[j];
                        var dt = element.distance.text;
                        document.getElementById('journey').innerHTML = "Distance: "+dt;
                        };
                      };
                    }
                  });
        }
    </script>
</body>
</html>
<!-- Reference link:

Get location via PHP: https://stackoverflow.com/questions/23212681/php-get-latitude-longitude-from-an-address
Find distance display:https://developers.google.com/maps/documentation/javascript/examples/distance-matrix
Find direction display: https://developers.google.com/maps/documentation/javascript/examples/directions-complex
-->
