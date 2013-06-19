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
            var editor = $('#invite-letter').ckeditor().ckeditorGet();
            if($(this).text()=="Edit"){
                $(this).text('Save');
            }else{
                editor.updateElement()
                $.post(".invite/edit", {
                    'content' : editor.getData(),
                    'subject' : '',
                    'type' : 'letter'
                } );
                $(this).text('Edit');
                editor.destroy();
            }
        });
        
        $( '#wellcome-letter-button' ).click(function(){
            var editor = $('#wellcome-letter').ckeditor().ckeditorGet();
            if($(this).text()=="Edit"){
                $(this).text('Save');
            }else{
                editor.updateElement()
                $.post("/user-page/edit", {
                    'content' : editor.getData(),
                    'subject' : '',
                    'type' : 'page'
                } );
                $(this).text('Edit');
                editor.destroy();
            }
        });
        
        $( '#invitelist-button' ).click(function(){
            console.log($( '#invitelist' ).val());
        });
         
        $( '#purchase_btn' ).click(function(){
            $('#wizzard_step1').hide();
            $('#wizzard_step2').fadeIn("slow");
            $('#wizzard_step2 input[name="ap_amount"]').val($('#fee').val());
        });
        $( '#withdraw_btn' ).click(function(){
            $('#balance_step1').hide();
            $('#balance_step2').fadeIn("slow");
        });
        
         
    });
}(window.jQuery);