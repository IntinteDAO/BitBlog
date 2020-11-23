$(document).ready(function(){

    $('.btn').attr('disabled',true);
    
$(document).keyup(function() {
        if(($("#title").val().length != 0) && ($(".badge")[0])){
            $('.btn').attr('disabled', false);
        }
        else
        {
            $('.btn').attr('disabled', true);
        }
    })
});