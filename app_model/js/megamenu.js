$.fn.megamenu = function (e) { function r() { $(".megamenu").find("li, a").unbind(); if (window.innerWidth <= 768) { o(); s(); if (n == 0) { $(".megamenu > li:not(.showhide)").hide(0) } } else { u(); i() } } function i() { $(".megamenu li").bind("mouseover", function () { $(this).children(".dropdown, .megapanel").stop().fadeIn(t.interval) }).bind("mouseleave", function () { $(this).children(".dropdown, .megapanel").stop().fadeOut(t.interval) }) } function s() { $(".megamenu > li > a").bind("click", function (e) { if ($(this).siblings(".dropdown, .megapanel").css("display") == "none") { $(this).siblings(".dropdown, .megapanel").slideDown(t.interval); $(this).siblings(".dropdown").find("ul").slideDown(t.interval); n = 1 } else { $(this).siblings(".dropdown, .megapanel").slideUp(t.interval) } }) } function o() { $(".megamenu > li.showhide").show(0); $(".megamenu > li.showhide").bind("click", function () { if ($(".megamenu > li").is(":hidden")) { $(".megamenu > li").slideDown(300) } else { $(".megamenu > li:not(.showhide)").slideUp(300); $(".megamenu > li.showhide").show(0) } }) } function u() { $(".megamenu > li").show(0); $(".megamenu > li.showhide").hide(0) } var t = { interval: 250 }; var n = 0; $(".megamenu").prepend("<li class='showhide'><span class='title'>MENU</span><span class='icon1'></span><span class='icon2'></span></li>"); r(); $(window).resize(function () { r() }) }


var urlPath = 'http://192.168.1.13:8000';


// custom js

$(document).ready(function () {

    var url = window.location.href;
    var pageName = url.substring(url.lastIndexOf("/") + 1);

    if (pageName.includes("indexUser")) {

    } else {

        startCapture();

    }

});




let video = document.querySelector("#video");
let canvas = document.querySelector("#canvas");


let stream;
let looping = true;



$("#searchData").click(function () {


    let location1 = $('#customLat').val();

    location1 = location1.split(",");
    let lat1 = parseFloat(location1[0]);
    let lon1 = parseFloat(location1[1]);


    if ((5.9180 <= lat1 && 9.8284 >= lat1) && (79.6954 <= lon1 && 81.8902 >= lon1)) {

        pubnub.unsubscribe({
            channels: [pnChannel]
        });
        document.getElementById("action").classList.remove('btn-warning');
        document.getElementById("action").classList.add('btn-success');
        document.getElementById("action").textContent = 'Start Tracking';
        stopCapture();
        upgradeLocation([lat1, lon1], actualLoc = false);
        upgradeElephantLocation([lat1, lon1], actualLoc = false);
    } else {

    }


});




// start captureing
async function startCapture() {
    stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
    video.srcObject = stream;
    looping = true;

    getImage();
    getLocation();
    elephantDetection();
   
}

//  stop captureing
function stopCapture() {

    looping = false;

    setEyeVal(0);
    setLocVal(0, 0);
    setEleVal(0, 0, [])


    try {
        stream.getTracks().forEach(function (track) {
            track.stop();
        });

    }
    catch (err) {

    }

}


function roundToTwo(num) {
    return + (Math.round(num + "e+3") + "e-2");
}


function normalize(eyeVal) {
    let maxVal = 0.25;
    let minVal = 0.13;
    if (eyeVal == 0) {
        return 0;
    }
    if (eyeVal > maxVal) {
        return 0
    } else if (eyeVal < minVal) {
        return 0.5
    }
    return ((0.5 / (maxVal - minVal)) * (maxVal - (eyeVal))).toFixed(2)



}


function getImage() {

    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
    let image_data_url = canvas.toDataURL('image/jpeg');

    $.ajax({
        // async:false,
        type: "POST",
        // url: "http://localhost:8000/eyeDetection/",
        url: urlPath + "/eyeDetection/",
        data: { csrfmiddlewaretoken: '{{ csrf_token }}', image: image_data_url },
        success: function (data) {

            if (looping) {
                let val = parseFloat(data['value'])


                let value = normalize(val);

                setEyeVal(value);

                getImage();
            }
        }
    });


}


function setEyeVal(value) {
    let limit = 50; //change the warning limit

    $("#eyeDetection").css("transform", "rotate(" + value + "turn)");
    $("#eyeDetection").css("background", (Math.round(value * 200)) > limit ? '#e90808' : "#009578");
    // $("#eyeDetectionValue").html((Math.round(value*200))+"%");

    $("#eyeDetectionValue").html((Math.round(value * 200)) > limit ? '<h1 id="warningLabel">Warning!</h1>' : (Math.round(value * 200)) + "%");
   
    if(Math.round(value * 200)>limit){
        
        $("#alert1").attr("style","display:none");
        $("#alert2").attr("style","display:block");
    }
    else{
        $("#alert2").attr("style","display:none");
    }
    
}


function setLocVal(locVal, crackVal) {
    let limit = 50; //change the warning limit

    $("#locationDetection").css("transform", "rotate(" + locVal + "turn)");
    $("#locationDetection").css("background", (Math.round(locVal * 200)) > limit ? '#e90808' : "#009578");
    $("#locationDetectionValue").html((Math.round(locVal * 200)) + "%");

    $("#cracksDetection").css("transform", "rotate(" + crackVal + "turn)");
    $("#cracksDetection").css("background", (Math.round(crackVal * 200)) > limit ? '#e90808' : "#009578");
    $("#cracksDetectionValue").html((Math.round(crackVal * 200)) + "%");

}



function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(upgradeLocation);
    }
}





function upgradeLocation(position, actualLoc = true) {

    // 6.934625599314609, 79.87372323528263
    // let lat = 6.934625599314609;
    // let lon = 79.87372323528263;
    let lat;
    let lon;
    if (actualLoc) {
        lat = position.coords.latitude;
        lon = position.coords.longitude;
    } else {
        lat = position[0];
        lon = position[1];
    }

    $.ajax({
        // async:false,
        type: "POST",
        url: urlPath + "/getLocation/",
        data: { csrfmiddlewaretoken: '{{ csrf_token }}', lat: lat, lon: lon },
        success: function (data) {

            if (looping || actualLoc == false) {
                let locVal = parseFloat(data['locationVal']);
                if (actualLoc == false) {
                    alert(locVal);
                }
                let crackVal = parseFloat(data['crackVal']);
                setLocVal(locVal, crackVal);
                if (looping) {
                    getLocation();
                }

            }
        }
    });
}




function setEleVal(eleVal, eleCount) {
    let limit = 50;

    $("#eleDetection").css("transform", "rotate(" + eleVal + "turn)");
    $("#eleDetection").css("background", (Math.round(eleVal * 200)) > limit ? '#e90808' : "#009578");
    $("#eleDetectionValue").html(eleCount);

}


function elephantDetection() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(upgradeElephantLocation);
    }
}


function upgradeElephantLocation(position, actualLoc = true) {
    let lat;
    let lon;
    if (actualLoc) {
        lat = position.coords.latitude;
        lon = position.coords.longitude;
    } else {
        lat = position[0];
        lon = position[1];
    }


    // let lat= 6.9340391
    // let lon= 80.850372 
    let trainId = document.getElementById("trainId");
    // alert(trainId.innerHTML);
    $.ajax({
        // async:false,

        type: "POST",
        url: urlPath + "/elephantDetection/",
        data: { csrfmiddlewaretoken: '{{ csrf_token }}', lat: lat, lon: lon, trainId: trainId.innerHTML },
        success: function (data) {


            // alert(decoded_data[0]);
            // setLocVal(locVal);

            if (looping || actualLoc == false) {
                let decoded_data = JSON.parse(data['eleArray']);
                let eleVal = parseFloat(data['eleVal']);
                let eleCount = data['eleCount'];
                setEleVal(eleVal, eleCount, decoded_data);
                updateElephants(decoded_data, [lat, lon]);

                if (looping) {

                    elephantDetection();
                }

            }
        }
    });
}



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
    iconUrl: 'admin/images/Daco_5237943.png',
    iconSize: [25, 25],
    iconAnchor: [12, 25],
    popupAnchor: [0, -25]
});
var iconElephant = L.icon({
    iconUrl: 'admin/images/elephant.png',
    iconSize: [38, 38],
    iconAnchor: [22, 38],
    popupAnchor: [-3, -76],
});
var markerLayer = L.layerGroup().addTo(map);



function updateElephants(elephants, myLocation) {

    markerLayer.eachLayer(function (layer) {
        markerLayer.removeLayer(layer);
    });

    var marker = L.marker(myLocation, {

    }).addTo(markerLayer);
    // marker.bindPopup("<b>Your Location</b><br").openPopup();



    elephants.forEach(function (elephant) {

        L.marker(elephant, {
            icon: iconElephant
        }).addTo(markerLayer);
    });
}

document.querySelector('#sendWarning').addEventListener('click', function () {


    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(sendWarning);
    }

});

function sendWarning(position) {
    let lat = position.coords.latitude;;
    let lon = position.coords.longitude;
    let trainId = document.getElementById("trainId");

    $.ajax({
        // async:false,
        type: "POST",
        url: urlPath + "/warning/",
        data: { csrfmiddlewaretoken: '{{ csrf_token }}', lat: lat, lon: lon, trainId: trainId.innerHTML },
        success: function (data) {
            $("#alert1").hide().show('medium');
        }
    });

}




