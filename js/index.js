function index(){
    if(sessionStorage.authenticated == 'false' || !sessionStorage.hasOwnProperty('authenticated')){
        alert('You should log in');
        location = 'index.html';
    }
}