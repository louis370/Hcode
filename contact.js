function validation(event){
    event.preventDefault();

    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;
    const number = document.getElementById("number").value;
    
    let error1 = document.getElementById("error1");
    let error2 = document.getElementById("error2");
    let error3 = document.getElementById("error3");
    let error4 = document.getElementById("error4");

    const regexNom = /^[a-zA-ZÀ-ÿ\-'\s]{2,50}$/;
    const regexNumber = /^0[89]\d{8}$/;
    const regexEmail = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
    
    if( (name == "") || (number == "") || (email == "") ){
        error4.innerHTML="please complete all fields";
        error4.style.color = "red";
        error1.innerHTML = "";
        error2.innerHTML = "";
        error3.innerHTML = "";
    }
    else if(!regexNom.test(name)){
        error1.innerHTML = "The name is not valid";
        error1.style.color = "red";
        error4.innerHTML = "";
        error2.innerHTML = "";
        error3.innerHTML = "";
    }
    else if(!regexNumber.test(number)){
        error2.innerHTML = "The number is invalid";
        error2.style.color = "red";
        error1.innerHTML = "";
        error4.innerHTML = "";
        error3.innerHTML = "";
    }
    else if(!regexEmail.test(email)){
        error3.innerHTML = "Enter a valid gmail address";
        error3.style.color = "red";
        error1.innerHTML = "";
        error4.innerHTML = "";
        error3.innerHTML = "";
        error2.innerHTML = "";
    }
    else{
        error4.innerHTML="Form sent";
        error4.style.color = "green";
        error1.innerHTML = "";
        error2.innerHTML = "";
        error3.innerHTML = "";
    }

}