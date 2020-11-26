$(document).ready(function(){

    $('#submit').attr('disabled',true);
    
$(document).keyup(function() {
        if(($("#title").val().length != 0) && ($(".badge")[0])){
            $('#submit').attr('disabled', false);
        }
        else
        {
            $('#submit').attr('disabled', true);
        }
    })
});