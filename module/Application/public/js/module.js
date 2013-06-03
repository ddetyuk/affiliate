!function($){"use strict";

window.CKEDITOR_BASEPATH = '/assets/js/ckeditor/';


    var rate = $('#calculator [name="rate"]');
    var freerate = $('#calculator [name="freerate"]');
    var profit = $('#calculator [name="profit"]');
    var calculate = function(){
        profit.value(rate*freerate);
    }
    rate.change(calculate);
    freerate.change(calculate);
    
   
    jQuery(document).ready(function($) {
         $( '#invitelist' ).tagsInput({'defaultText':'Add an email', 'width':'600px'});
         
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