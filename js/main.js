function getSites(){
    //create request
    var x = new XMLHttpRequest();
    //prepare request
    x.open('GET', 'http://localhost/BAJABN/controllers/PropertyController.php', true);
    //send request
    x.send();
    //handle ready state change event
    x.onreadystatechange = function(){
        //check status
        if(x.status == 200 && x.readyState == 4){
            showSites(x.responseText); 
        }
    }
}

//Show sites
function showSites(data){
    //select
    var select = document.getElementById('cbSites')
    //parse to JSON
    var JSONdata =JSON.parse(data);
    //get data array
    var sites = JSONdata.site;
    //read data
    for(var i = 0; i <= sites.length; i++){
        console.log(sites[i]);
        //create option
        var option = document.createElement('option');
        option.value = sites[i].id_property;
        option.innerHTML = sites[i].propertyName;
        select.appendChild(option);
    }
}

function centerMap(data){
    var id = document.getElementById('cbSites').value;
    id = "1";

    //parse to JSON
    var JSONdata =JSON.parse(data);
    //get data array
    var marker = JSONdata.site;
    
    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: marker[id_property].latitude, lng: marker[id_property].longitude },
        zoom: 15,
    });}