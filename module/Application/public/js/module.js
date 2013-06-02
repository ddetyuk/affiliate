!function($){"use strict";
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
    });
}(window.jQuery);