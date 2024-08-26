<?php include('header.php');?>
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

                <li> <button class="btn btn-danger col-sm-3" id="action">Stop Tracking</button></li>
                <div class="clearfix"> </div>
            </ul>
        </div>
        <div class="clearfix"> </div>
    </div>
</div>
<div>
    <div id="map-canvas"></div>
</div>

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

        if (btn = "1") {
            var a_lat = document.getElementById('al_lat');

            a_lat.innerHTML = "latitude" + lat;
            var a_lng = document.getElementById('al_lng');

            a_lng.innerHTML = "longitude" + lng;

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
document.querySelector('#action').addEventListener('click', function() {
    var text = document.getElementById("action").textContent;
    if (text == "Start Tracking") {
        pubnub.subscribe({
            channels: [pnChannel]
        });
        pubnub.addListener({
            message: redraw
        });

        document.getElementById("action").classList.add('btn-danger');
        document.getElementById("action").classList.remove('btn-success');
        document.getElementById("action").textContent = 'Stop Tracking';

    } else {
        pubnub.unsubscribe({
            channels: [pnChannel]
        });
        document.getElementById("action").classList.remove('btn-danger');
        document.getElementById("action").classList.add('btn-success');
        document.getElementById("action").textContent = 'Start Tracking';
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
<script
    src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyCfgh3B67Nmdh7eqy6Zo5eL6sMQrKAWq-U&callback=initialize">
</script>









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
                <table>
                    <th style="font-size: 15px;">
                        <h3>Train speed</h3>
                    </th>
                    <tr>
                        <td width="60%"><br />
                            <div class="gauge">
                                <div class="gauge__body">
                                    <div class="gauge__fill" id="kk"></div>
                                    <div class="gauge__cover" id="values">Kmph</div>

                                </div>
                            </div>
                        </td>


                    </tr>

                </table>

                <br /><Br />

                <div class="blas">
                    <li class="wicked"><b>Driver emergency alerts</b></li>
                    <li class="mullet" id="al_lat">
                        <h2 style="color:red;"></h2>
                    </li>
                    <li class="mullet" style="color:red;" id="al_lng">
                        <h2></h2>
                    </li>


                </div>

            </div>

        </div>
        <div class="clearfix"></div>
    </div>
</div>

<?php include('footer.php');?>