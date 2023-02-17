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