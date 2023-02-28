function login(){
    console.log('Getting token...');
    //create request
    var x = new XMLHttpRequest();
    //prepare request
    x.open('GET', 'http://localhost/BajaBnB/controllers/loginController.php', true);
    //request header
    x.setRequestHeader('user', document.getElementById('email').value);
    x.setRequestHeader('password', document.getElementById('password').value);
    //send request
    x.send();
    //event handler
    x.onreadystatechange = function(){
        if(x.readyState == 4 && x.status == 200){
            //parse to json
            var JSONdata = JSON.parse(x.responseText);

            if(JSONdata.status == 0){
            //save session info
            sessionStorage.authenticated = true;
            sessionStorage.id = JSONdata.user.id;
            sessionStorage.name = JSONdata.user.name + ' ' + JSONdata.user.lastname;
            sessionStorage.phone = JSONdata.user.phone;
            sessionStorage.email = JSONdata.user.email;
            sessionStorage.status = JSONdata.user.status;
            sessionStorage.token = JSONdata.token;
            //location = 'index.html';
            alert('Sesion iniciada')
            }else{
                alert(JSONdata.errorMessage);
            }

        }
    }
}

function logout(){
    //save session info
    sessionStorage.authenticated = false;
    sessionStorage.id = '';
    sessionStorage.name = '';
    sessionStorage.phone = '';
    sessionStorage.email = '';
    sessionStorage.status = '';
    sessionStorage.token = '';
    location = 'login.html';
}