
function propertySelected() {
    var id =   document.getElementById("cbSites").value;
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
            preparePage(x.responseText)
        }
    }
}

function preparePage(data){
        //parse to JSON
        var JSONdata =JSON.parse(data);
        //get data array
        var sites = JSONdata.property;
        const opcion = sites.id_property;
        localStorage.setItem('opc',opcion);
        sessionStorage.siteId = sites.id_property;
}


function pageID(){
    const id = localStorage.getItem('opc');
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
        console.log(x.responseText)
        buildPage(x.responseText)
        }
    }
}

function buildPage(data){
    //parse to JSON
    var JSONdata =JSON.parse(data);
    //get data array
    var sites = JSONdata.property;
        
    var titulo = document.getElementById('tituloPropiedad');
    titulo.textContent = sites.propertyName
    var descripcion = document.getElementById('bloqueDescripcion');
    descripcion.textContent =  sites.propertyDescription;
    var ubicacion = document.getElementById('bloqueUbicacion');
    ubicacion.textContent = sites.city.cityName + ", "+sites.city.state.stateName;
    
}
