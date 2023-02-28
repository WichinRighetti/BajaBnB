let map;
var sideMarkerVisible = false;

function initMap() {
    //var id = document.getElementById("cbSites").value;
    //create request
    var x = new XMLHttpRequest();
    //prepare request
    x.open('GET', 'http://localhost/BaJaBnB/controllers/PropertyController.php?id=' + sessionStorage.siteId, true);
    //send request
    x.send();
    //handle ready state change event
    x.onreadystatechange = function(){
        //check status
        if(x.status == 200 && x.readyState == 4){
            showMap(x.responseText);
        }
    }
}

function showLocation() {
    var id = document.getElementById("cbSites").value;
    console.log("Getting datta...")
    //create request
    var x = new XMLHttpRequest();
    //prepare request
    x.open('GET', 'http://localhost/BajaBnB/controllers/PropertyController.php?id=' + id, true);

    //send request
    x.send();
    //handle ready state change event
    x.onreadystatechange = function(){
        //check status
        if(x.status == 200 && x.readyState == 4){
            showMap(x.responseText);
        }
    }
}

function showMap(data){
    //parse to JSON
    var JSONdata =JSON.parse(data);
    //get data array
    var marker = JSONdata.property;
    
    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: marker.latitude, lng: marker.longitude },
        zoom: 15,
    });

    var contentPopup = '<h2><a href="property-details.html"> ' + marker.propertyName + '</a></h2>' +  
    '<h3>' + marker.propertyDescription + '</h3>' + 
    '<img width=200 height=100 src=Client/assets/images/image' + marker.id_property + '.jpg>';
    var infoWindows = new google.maps.InfoWindow({
        content: contentPopup
    });

    var marker = new google.maps.Marker({
        animation: google.maps.Animation.DROP,
        position: {lat: marker.latitude, lng: marker.longitude},
        draggable: false,
        title: marker.description,
        icon: 'Client/assets/images/house.png'
    })

    marker.addListener('click', function(){
        if(!sideMarkerVisible){
            sideMarkerVisible = true;
            infoWindows.open(map, marker);
        }else{
            sideMarkerVisible = false;
            infoWindows.close();
        }
    });

    google.maps.event.addListener(map, 'click', function(){
        sideMarkerVisible = false;
        infoWindows.close();
    })

    marker.setMap(map);
    //console.log("len", marker)

    //Para varios puntos
    /*
    for(let i = 0 ; i < marker.length ; i++){
        console.log("Index", i);
        console.log("marker", marker[i]);
        //console.log("Latitude", marker[i].latitude);
        //console.log("Longitude", marker[i].longitude);

        var sites = new google.maps.Marker({
            animation: google.maps.Animation.DROP,
            position: {lat: marker[i].latitude, lng: marker[i].longitude},
            draggable: false,
            title: marker[i].description
        })
        
    sites.setMap(map);
    }*/
}

window.initMap = initMap;
