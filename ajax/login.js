$(document).ready(function(){
  $('#login-form').submit(function(e){
    e.preventDefault()
    
    var email = $("#email").val();
    var password = $("#password").val();

    //Validate client side first
    toastr.success('Here we go')

  })
})