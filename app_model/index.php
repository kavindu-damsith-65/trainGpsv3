<?php 
session_start();
if(isset($_SESSION['userLogin'])){
    $trainId=$_SESSION['trainId'];
    // echo '<script>alert('. $trainId.')</script>';
   

}else{
    header("Location: logIn.php");
    exit;
}

include('header.php');



?>
<style>
.alert {
    display:none;
    position: absolute;

    left: 80%;
    top: 20%;
    font-size:15px

}

.alert2 {
    display:none;
    position: absolute;

    left: 80%;
    top: 20%;
    font-size:15px

}
</style>




<div id="alert1" class="alert alert-danger alert-dismissable">
    <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> -->
    Warning! message sent successfully.
</div>

<div id="alert2" class="alert alert-danger alert-dismissable">
 
    Warning! Your Eyes are closed!!!!!!.
</div>



<div class="header-bottom">
    <div class="container">

        <div class="top-nav">
            <span class="menu"> </span>
            <ul class="navig megamenu skyblue">
                <li><a href="#" class="scroll"><span> </span> Find Locations</a>

                </li>

                <div class="clearfix"></div>
            </ul>
            <script>
            $("span.menu").click(function() {
                $(".top-nav ul").slideToggle(300, function() {});
            });
            </script>
        </div>
        <div class="head-right">
            <ul class="number">
                <li> <button class="btn btn-lg btn-danger" id="sendWarning">Send Warning</button></li>
                <li> <button class="btn btn-lg btn-warning" id="action">Stop Tracking</button></li>
                <li> <button class="btn btn-lg btn-danger" id="logOut">Log Out</button></li>
                <div class="clearfix"> </div>
            </ul>
        </div>
        <div class="clearfix"> </div>
    </div>
</div>




<div class="loc-lov">
    <?php  echo '<p id="trainId">'.$trainId.'</p>';?>
    <div class="container">

        <div class="loc-right">
            <div class="loc-top">
                <style>
                .customFont {
                    font-size: 15px;
                }
                </style>
                <!-- load ai model details -->
                <div class="row">
                    <div class="col-12">
                        <h2>Search Custom Location</h2>
                    </div>
                    <div class="col-md-5 col-sm-12 pt-2">
                        <input id="customLat" class="form-control form-control-lg" type="text"
                            placeholder="Latitude , Longitude">
                    </div>
                    <!-- <div class="col-md-3 col-sm-12 pt-2">
                        <input id="customLon" class="form-control form-control-lg" type="text" placeholder="Longitude">
                    </div> -->
                    <div class=" col-md-3 col-sm-12 pt-2">
                        <button id="searchData" class="customFont btn btn-lg btn-primary px-5">Search</button>
                    </div>

                </div>

                <div class="row mt-5 ">

                    <div class="col-md-12 col-lg-4 pt-5">

                        <style>
                        .displayHide {
                            display: none;
                        }
                        </style>
                        <video id="video" width="640" autoplay class="displayHide"></video>
                        <canvas id="canvas" width="640" height="480" class="displayHide"></canvas>


                        <hr>
                        <table>
                            <th style="font-size: 15px;">
                                <h3>Eye
                                    Detection&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </h3>
                            </th>
                            <!-- <tr>
                            <td width="100%"> 
                                   <h1 id="warningLabel">Warning!</h1>
                                </td>
                            </tr> -->
                            <tr>
                                <td width="60%"><br />
                                    <div class="gauge">
                                        <div class="gauge__body">
                                            <div class="gauge__fill" id="eyeDetection"></div>

                                            <div class="gauge__cover" id="eyeDetectionValue">
                                                0%
                                            </div>

                                        </div>
                                    </div>
                                </td>
                            </tr>



                        </table>

                    </div>
                    <div class="col-md-12 col-lg-4 pt-5">
                        <hr>
                        <table>
                            <th style="font-size: 15px;">
                                <h3>Dangerousness by Location</h3>
                            </th>

                            <tr>
                                <td width="60%"><br />
                                    <div class="gauge">
                                        <div class="gauge__body">
                                            <div class="gauge__fill" id="locationDetection"></div>
                                            <div class="gauge__cover" id="locationDetectionValue">0%</div>

                                        </div>
                                    </div>
                                </td>
                            </tr>

                        </table>

                    </div>
                    <div class="col-md-12 col-lg-4 pt-5">
                        <hr>
                        <table>
                            <th style="font-size: 15px;">
                                <h3>Dangerousness by Cracks&nbsp;&nbsp;</h3>
                            </th>

                            <tr>
                                <td width="60%"><br />
                                    <div class="gauge">
                                        <div class="gauge__body">
                                            <div class="gauge__fill" id="cracksDetection"></div>
                                            <div class="gauge__cover" id="cracksDetectionValue">0%</div>

                                        </div>
                                    </div>
                                </td>
                            </tr>

                        </table>

                    </div>


                </div>
                <br>

                <div class="row mt-5 ">
                    <div class="col-md-12 col-lg-4 pt-5">

                        <style>
                        .displayHide {
                            display: none;
                        }
                        </style>
                        <video id="video" width="640" autoplay class="displayHide"></video>
                        <canvas id="canvas" width="640" height="480" class="displayHide"></canvas>


                        <hr>
                        <table>
                            <th style="font-size: 15px;">
                                <h3>Elephant
                                    Detection&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </h3>
                            </th>
                            <!-- <tr>
                            <td width="100%"> 
                                   <h1 id="warningLabel">Warning!</h1>
                                </td>
                            </tr> -->
                            <tr>
                                <td width="60%"><br />
                                    <div class="gauge">
                                        <div class="gauge__body">
                                            <div class="gauge__fill" id="eleDetection"></div>

                                            <div class="gauge__cover" id="">
                                                <div id="eleDetectionValue">0</div>

                                            </div>

                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <br>


                        </table>

                    </div>


                </div>
                <br><br>

            </div>
        </div>
    </div>
</div>





<div>
    <!-- <div id="map-canvas"></div> -->
    <style>
    #map {
        height: 800px;
        width: 100%;
    }
    </style>
    <br><br>
    <div class="container">
        <h2>Elephant Locations</h2><br>
        <div id="map"></div>
    </div>

</div>



<?php 
// $firstname = $_GET['station'];
$firstname ="";
?>






<script>
var k = "<?php echo $firstname;?>";
console.log("final" + k);
</script>
<script>
//for get distance
function distance(lat1,
    lat2, lon1, lon2) {
    //to get the distance from the difference of the longitute and the latitude by using 180 degree
    lon1 = lon1 * Math.PI / 180;
    lon2 = lon2 * Math.PI / 180;
    lat1 = lat1 * Math.PI / 180;
    lat2 = lat2 * Math.PI / 180;

    // Haversine formula which is using as a common furmula
    let dlon = lon2 - lon1;
    let dlat = lat2 - lat1;
    let a = Math.pow(Math.sin(dlat / 2), 2) +
        Math.cos(lat1) * Math.cos(lat2) *
        Math.pow(Math.sin(dlon / 2), 2);

    let csin = 2 * Math.asin(Math.sqrt(a));


    let radius = 6371;

    // calculate the result
    return (csin * radius);
}

//for location track
window.lat = 6.9337;
window.lng = 79.8500;

var map;
var mark;
var lineCoords = [];

var initialize = function() {
    map = new google.maps.Map(document.getElementById('map-canvas'), {
        center: {
            lat: lat,
            lng: lng
        },
        zoom: 15
    });
    mark = new google.maps.Marker({
        position: {
            lat: lat,
            lng: lng
        },
        map: map
    });
};

window.initialize = initialize;

var redraw = function(payload) {
    var div = document.getElementById('values');

    var oldlat = lat;
    var oldlng = lng;
    if (payload.message.lat) {
        lat = payload.message.lat;
        lng = payload.message.lng;
        btn = payload.message.btn;
        hu = payload.message.hu;
        tmp = payload.message.tmp;
        console.log("old" + k);
        console.log("button" + btn);
        var s = distance(oldlat, lat, oldlng, lng);
        var v = parseInt(s / 0.00013889);
        console.log(distance(oldlat, lat,
            oldlng, lng) + " KM");
        console.log(v + "kmph");
        var vv = v / 200;

        var final = "rotate(" + vv + "turn)"
        document.getElementById("kk").style.transform = final;
        var div = document.getElementById('values');

        div.innerHTML = v + "Kmph";

        var div3 = document.getElementById('tempe');

        div3.innerHTML = tmp + " C";


        if (k = "Maradana") {
            console.log("OK");
            var fs = distance(6.6827776, lat, 79.9955937, lng);
            var time = fs / v;

            var div_t = document.getElementById('values_t');

            div_t.innerHTML = time.toFixed(2); + " hrs";
            if (time < "0.017") {
                console.log("arriver" + time);
                var div_t = document.getElementById('values_tdes');
                div_t.innerHTML = "Train is arriving, please proceed";
            } else {
                console.log("arriver" + time);
                var div_t = document.getElementById('values_tdes');
                div_t.innerHTML = "Waiting";
            }

        }

        if (k = "Kelaniya") {
            console.log(time);
            var fs = distance(6.6827836, lat, 79.9956172, lng);
            var time = fs / v;

            var div_t = document.getElementById('values_t');

            div_t.innerHTML = time.toFixed(2); + " hrs";
            if (time < "0.017") {
                console.log("arriver" + time);
                var div_t = document.getElementById('values_tdes');
                div_t.innerHTML = "Train is arriving, please proceed";
            } else {
                console.log("arriver" + time);
                var div_t = document.getElementById('values_tdes');
                div_t.innerHTML = "Waiting";
            }

        }


        map.setCenter({
            lat: lat,
            lng: lng,
            alt: 0
        });
        mark.setPosition({
            lat: lat,
            lng: lng,
            alt: 0
        });

        lineCoords.push(new google.maps.LatLng(lat, lng));

        var lineCoordinatesPath = new google.maps.Polyline({
            path: lineCoords,
            geodesic: true,
            strokeColor: '#2E10FF'
        });

        lineCoordinatesPath.setMap(map);
    }
};

var pnChannel = "raspi-tracker";

var pubnub = new PubNub({
    publishKey: 'pub-c-71d59dba-7381-4589-ace3-509489b2a407',
    subscribeKey: 'sub-c-df9abd94-af5f-11eb-a3cb-9a3343cb4534'
});

function myFunction() {
    pubnub.subscribe({
        channels: [pnChannel]
    });
    pubnub.addListener({
        message: redraw
    });
};




document.querySelector('#logOut').addEventListener('click', function() {
    window.location.href = "logIn.php";

});

document.querySelector('#action').addEventListener('click', function() {
    var text = document.getElementById("action").textContent;
    if (text == "Start Tracking") {
        pubnub.subscribe({
            channels: [pnChannel]
        });
        pubnub.addListener({
            message: redraw
        });

        document.getElementById("action").classList.add('btn-warning');
        document.getElementById("action").classList.remove('btn-success');
        document.getElementById("action").textContent = 'Stop Tracking';


        startCapture();

    } else {
        pubnub.unsubscribe({
            channels: [pnChannel]
        });
        document.getElementById("action").classList.remove('btn-warning');
        document.getElementById("action").classList.add('btn-success');
        document.getElementById("action").textContent = 'Start Tracking';

        stopCapture();
    }
});

/*for testing using default testing data
		function newPoint(time) {
      var radius = 0.0001;
      var x = Math.random() * radius;
      var y = Math.random() * radius;
      return {lat:window.lat + y, lng:window.lng + x};
        }
      setInterval(function() {
      pubnub.publish({channel:pnChannel, message:newPoint()});
      }, 500);
	  
	  */
</script>





<!-- 
<script
    src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyCfgh3B67Nmdh7eqy6Zo5eL6sMQrKAWq-U&callback=initialize">
</script> -->









<div class="loc-lov">
    <div class="container">

        <div class="loc-right">
            <div class="loc-top">


                <script>
                const gaugeElement = document.querySelector(".gauge");

                function setGaugeValue(gauge, value) {
                    if (value < 0 || value > 1) {
                        return;
                    }

                    gauge.querySelector(".gauge__fill").style.transform = `rotate(${
    value / 2
  }turn)`;
                    gauge.querySelector(".gauge__cover").textContent = `${Math.round(
    value * 100
  )}%`;
                }

                setGaugeValue(gaugeElement, 0.3);
                </script>





            </div>
        </div>

        <?php include('footer.php');?>