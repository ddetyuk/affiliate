!function($){
    "use strict";

    window.CKEDITOR_BASEPATH = '/assets/js/ckeditor/';



   
    jQuery(document).ready(function($) {
        $('#rate,#fee').change(function(){
            var val = ($('#rate').val()/5+2)*$('#fee').val();
              $('#profit').val(isNaN(val)?'':val);
        });
        $( '#invitelist' ).tagsInput({
            'defaultText':'Add an email', 
            'width':'600px'
        });
         
        $( '#invite-letter-button' ).click(function(){
            $('#invite-letter').ckeditor();
        });
        $( '#wellcome-letter-button' ).click(function(){
            $('#wellcome-letter').ckeditor();
        });
        $( '#invitelist-button' ).click(function(){
            console.log($( '#invitelist' ).val());
        });
         
    });
}(window.jQuery);