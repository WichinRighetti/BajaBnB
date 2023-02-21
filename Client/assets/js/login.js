document.querySelector("#show-login").addEventListener("click",function(){
    document.querySelector(".popup").classList.add("active");
});

document.querySelector(".popup .close-btn").addEventListener("click",function(){
    document.querySelector(".popup").classList.remove("active");
});

function validEmail() {
    //Expresión regular para verificar correo electrónico
    res = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if(res.test(email.value)){
        alert("La direccion de email " + email.value + " es correcta.");
    } else {
        alert("La direccion de email es incorrecta.");
    }
}

function login(){
    console.log('Getting token...');
    
    //correo = document.getElementById('email').value;
    //console.log('user: ' + correo);
    //claveAcceso = document.getElementById('password').value;
    //console.log('password: ' + claveAcceso);
    
    //create request
    var x = new XMLHttpRequest();
    //prepare request
    x.open('GET', 'http://localhost:8080/BAJABNB/controllers/loginController.php', true);
    //request header
    //x.setRequestHeader('user', document.getElementById('txtUser').value);
    x.setRequestHeader('user', document.getElementById('email').value);
    //x.setRequestHeader('password', document.getElementById('txtPassword').value);
    x.setRequestHeader('password', document.getElementById('password').value);
    //send request
    x.send();
    //event handler
    
    //console.log('ReadyState', x.readyState);
    //console.log('Status' + x.status);
    
    x.onreadystatechange = function(){
        if(x.readyState == 4 && x.status == 200){
            //parse to json
            var JSONdata = JSON.parse(x.responseText);

            if(JSONdata.status == 0){
            //save session info
            sessionStorage.authenticated = true;
            sessionStorage.id = JSONdata.user.id;
            sessionStorage.name = JSONdata.user.name + ' ' + JSONdata.user.lastaname;
            sessionStorage.phone = JSONdata.user.phone;
            sessionStorage.email = JSONdata.user.email;
            sessionStorage.status = JSONdata.user.status;
            sessionStorage.token = JSONdata.token;
            location = 'index.html';
            }else{
                alert(JSONdata.errorMessage)
            }

        }
    }
}