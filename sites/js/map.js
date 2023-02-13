

function initMap() {
    var x= new XMLHttpRequest();
    //prepare request 
    x.open("GET", "http://localhost/BajaBnB/sites/controllers/siteController.php", true);
    x.send();
    //handle ready stage request
    x.onreadystatechange = function(){
        //check status
        if(x.status == 200 && x.readyState == 4){
            showMap(x.responseText);
        }
    }
}

function showMap(data){
    //parse to JSON
    var JSONdata = JSON.parse(data);
    //marker data array
    var pointer = JSONdata.Site;
    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 31.0362,
                  lng: -115.473 },
        zoom: 15,
      });
    for(var i = 0; i < pointer.length ; i++){
        var marker = new google.maps.Marker({
        animation: google.maps.Animation.DROP,
        position: {lat: pointer[i].Lat, 
                   lng: pointer[i].Long },
        draggable: false,
        title: pointer[i].Description,
        });
        marker.setMap(map);
    }  
} 

//window.initMap = initMap;