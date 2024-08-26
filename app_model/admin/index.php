<!DOCTYPE html>
<html>
<script>
var urlPath = 'http://192.168.1.12:8000';
</script>

<head>
    <meta charset="utf-8">
    <title>Train Details</title>
    <style>
    #map {
        height: 800px;
        width: 100%;
    }



    .navbar a,
    .navbar button {
        font-size: 2rem !important;
        /* padding: 0.5rem 0.5rem !important; */
    }

    .navbar button {
        font-size: 1.5rem !important;
    }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>


    <!-- <script src="https://unpkg.com/leaflet-geolocation@3.3.0/dist/leaflet-geolocation.min.js"></script> -->


</head>

<body>
    <nav class="navbar navbar-light bg-primary justify-content-between">
        <div class="container">
            <a style="color:white" class="navbar-brand">Train Details</a>
        </div>

        <button id="logOutButton" class="btn btn-sm btn-danger my-2 my-sm-0 mx-5" type="submit">Log Out</button>
    </nav>

    <?php
session_start();
if(isset($_SESSION['adminLogin'])){
    // echo '<script>alert('.$_SESSION['adminLogin'].')</script>';
        $servername = "localhost";
        $username = "root";
        $passwordDb = "";
        $dbname = "train_gps";
        
       
        $conn = new mysqli($servername, $username, $passwordDb, $dbname);
        
      
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }
       
        $sql = "SELECT * FROM `warnings` ORDER BY time DESC";
     
        $result = $conn->query($sql);

}else{
    header("Location: adminLogIn.php");
    exit;
}

?>
    <div class="container pb-4">
        <div class="py-4">
        <h3>Warnings</h3>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">Train Id</th>
                    <th scope="col">Timt</th>
                    <th scope="col">Latitude</th>
                    <th scope="col">Longitide</th>
                   
                </tr>
            </thead>
            <tbody>
                <tr>
                <?php
                
                if ($result->num_rows > 0) {
                    $counter=1;
                    while($row = $result->fetch_assoc()){
                  ?>     
                   
                <tr>
                    <th scope="row"><?php echo $counter?></th>
                    <td><?php echo $row['train_id']?></td>
                    <td><?php echo $row['time']?></td>
                    <td><?php echo $row['lat']?></td>
                    <td><?php echo $row['lon']?></td>
                </tr>
                <?php
                $counter+=1;   
                }
                }?>
                </tr>
               
            </tbody>
        </table>

    </div>
    <div id="map"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"
        integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script>
    var map = L.map('map').setView([0, 0], 40);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
            '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
            'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 40,
    }).addTo(map);

    // Try to get the user's current location
    map.locate({
        setView: true,
        maxZoom: 40
    });


    var iconTrain = L.icon({
        iconUrl: 'images/Daco_5237943.png',
        iconSize: [25, 25],
        iconAnchor: [12, 25],
        popupAnchor: [0, -25]
    });
    var iconElephant = L.icon({
        iconUrl: 'images/elephant.png',
        iconSize: [38, 38],
        iconAnchor: [22, 38],
        popupAnchor: [-3, -76],
    });

    let elephants;
    let trains;

    var markerLayer = L.layerGroup().addTo(map);


    function updateTrainData() {

        markerLayer.eachLayer(function(layer) {
            markerLayer.removeLayer(layer);
        });


        $.ajax({
            async: false,
            type: "POST",
            url: urlPath + "/getAllData/",
            data: {
                csrfmiddlewaretoken: '{{ csrf_token }}',
            },
            success: function(data) {

                elephants = JSON.parse(data['elephants']);
                trains = JSON.parse(data['trains']);



            }
        });


        elephants.forEach(function(elephant) {

            var marker = L.marker(elephant, {
                icon: iconElephant
            }).addTo(markerLayer);
            // Add a popup to the marker
            // marker.bindPopup("<b>Elephant</b>").openPopup();
        });


        trains.forEach(function(train) {

            var marker = L.marker(train, {}).addTo(markerLayer);
            // marker.bindPopup("<b>Your Location</b><br").openPopup();

        });
    }


    setInterval(updateTrainData, 1000);





    document.querySelector('#logOutButton').addEventListener('click', function() {
        window.location.href = "adminLogIn.php";

    });
    </script>
</body>

</html>