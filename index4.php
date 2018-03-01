<!DOCTYPE html >
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>From Info Windows to a Database: Saving User-Added Form Data</title>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>
  <body>
    <div id="map" height="460px" width="100%"></div>
    <div id="form">
      <table>
      <tr> <td><input type='text' id='name' placeholder="Name"/> </td> </tr>
      <tr> <td><input type='text' id='address' placeholder="Adrress"/> </td> </tr>
      <tr><td><input type='text' id='type' placeholder="Description"/> </td></tr>
                 <tr><td></td><td><input type='button' value='Submit' onclick='saveData()'/></td></tr>
      </table>
    </div>
    <div id="message">Location saved</div>
    <script>
      var map;
      var marker;
      var infowindow;
      var messagewindow;

      function initMap() {

        var california = {lat: 37.4419, lng: -122.1419};
        map = new google.maps.Map(document.getElementById('map'), {
          center: california,
          zoom: 13
        });
        infowindow = new google.maps.InfoWindow({
          content: document.getElementById('form')
        });

        messagewindow = new google.maps.InfoWindow({
          content: document.getElementById('message')
        });

        google.maps.event.addListener(map, 'click', function(event) {
          marker = new google.maps.Marker({
            position: event.latLng,
            map: map
          });


          google.maps.event.addListener(marker, 'click', function() {
            infowindow.open(map, marker);
          });
        });
      }

      function saveData() {
        var name = escape(document.getElementById('name').value);
        var address = escape(document.getElementById('address').value);
        var type = document.getElementById('type').value;
        var latlng = marker.getPosition();
        var url = 'http://www.students.oamk.fi/~t6trhu00/huymap/huyphp.php?name=' + name + '&address=' + address +
                  '&type=' + type + '&lat=' + latlng.lat() + '&lng=' + latlng.lng();
        downloadUrl(url, function(data, responseCode) {
          if (responseCode == 200 && data.length <= 1) {
            infowindow.close();
            messagewindow.open(map, marker);
          }
        });
      }

      function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;

        request.onreadystatechange = function() {
          if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request.responseText, request.status);
          }
        };

        request.open('GET', url, true);
        request.send(null);
      }
      function doNothing () {
      }

    </script>
    <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmVD9vSThqp7gggOXQHB8aNOzC8gjyvLA&callback=initMap">
    </script>
  </body>
</html>
<!-- Refernce:
PHP to DB:https://developers.google.com/maps/documentation/javascript/info-windows-to-db
-->
