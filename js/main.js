$(document).ready(function(){
    
});

function validRegistration(){
    if($(".userRegisterFirstName, .userRegisterLastName, .userRegisterDOB, .userRegisterEmail, .userRegisterPassword, .userRegisterConfirmPassword").val() === ""){
        return "All fields are mandatory. Please do not leave fields empty.";
    } else if(!isEmail($(".userRegisterEmail").val())){
        return "Please enter a valid email id";
    } else if($(".userRegisterPassword").val().length<5){
        return "Password too short. Please use a minimum of 5 characters";
    } else if($(".userRegisterPassword").val()!==$(".userRegisterConfirmPassword").val()){
        return "Passwords do not match. Please re-enter matching passwords.";
    } else{
        return "success";
    }
}

function validLogin(){
    if($(".userLoginEmail, .userLoginPassword").val() === ""){
        return "All fields are mandatory. Please do not leave fields empty.";
    } else if(!isEmail($(".userLoginEmail").val())){
        return "Please enter a valid email id";
    } else{
        return "success";
    }
}

function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}
