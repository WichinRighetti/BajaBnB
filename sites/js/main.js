function getSites() {
  var x = new XMLHttpRequest();
  //prepare request
  x.open(
    "GET",
    "http://localhost/BajaBnB/sites/controllers/siteController.php",
    true
  );
  x.send();
  //handle ready stage request
  x.onreadystatechange = function () {
    //check status
    if (x.status == 200 && x.readyState == 4) {
      showSites(x.responseText);
    }
  }
}

//show Sites List
function showSites(data) {
  //select
  var select = document.getElementById("cbSites");
  //parse to JSON
  var JSONdata = JSON.parse(data);
  //marker data array
  var pointer = JSONdata.Site;
  // read 
  for(var i = 0; i < pointer.length; i++){
    console.log(pointer[i]);
    //create option 
    var option = document.createElement("option"); 
    option.value = pointer[i].Id;
    option.innerHTML = pointer[i].Description;
    select.appendChild(option);
  }
}




function requestSite() {
  var id = document.getElementById("cbSites").value;
  var x = new XMLHttpRequest();
  //prepare request
  x.open(
    "GET",
    "http://localhost/BajaBnB/sites/controllers/siteController.php?Id="+ id, true);
  x.send();
  //handle ready stage request
  x.onreadystatechange = function () {
    //check status
    if (x.status == 200 && x.readyState == 4) {
      selectSites(x.responseText);
    }
  }
}
function selectSites(data) {
  //parse to JSON
  var JSONdata = JSON.parse(data);
  //marker data array
  var pointer = JSONdata.Site;

  map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: pointer.Lat, 
              lng: pointer.Long },
    zoom: 15,    
    draggable: false,
    title: pointer.Description
    });
    var marker = new google.maps.Marker({
      animation: google.maps.Animation.DROP,
      position: {lat: pointer.Lat, 
                 lng: pointer.Long },
      draggable: false,
      title: pointer.Description,
      icon: "assets/pointers.png"
      });
    marker.setMap(map);
}