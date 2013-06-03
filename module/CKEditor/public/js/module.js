(function( $ ){
    $('[data-element="inlineEditor"]').live("construct.inlineEditor", function() {
        if($(this).data('disable-construct')){
            return;
        }
        if($(this).data('status')!='construct'){
            $(this).attr('contenteditable', true);
            $(this).ckeditor();
            $(this).on('instanceReady.ckeditor', function(e, editor){
                $(editor.container.$).attr("title", "");
                editor.on('focus', function() {
                    $(this.element.$).data('disable-destruct', true);
                    $(this.element.$).find('[data-element]').trigger('destruct');
                    $(this.element.$).data('disable-destruct', false);
                });
                editor.on('blur', function() {
                    var editor = this;
                    var element = $(this.element.$);
                    if (editor.checkDirty()) {
                        // send the data to backend
                        $.post('/admin/inline-editor', {
                            id      : element.data('id'),
                            entity  : element.data('entity'),
                            value   : editor.getData(),
                            type    : element.data('type')
                        }).success(function(response){
                            //replace source container
                            if(response.result){
                                element.html(response.details.content);
                                editor.resetDirty();
                                $(element).find('[data-element]').trigger('construct');
                            }
                        });
                    }else{
                        $(element).find('[data-element]').trigger('construct');
                    }
                });
            });
            $(this).data('status', 'construct');
        }
    });
    $('[data-element="inlineEditor"]').live("destruct.inlineEditor", function() {
        if($(this).data('disable-destruct')){
            return;
        }
        if($(this).data('status')!='destruct'){
            $(this).unbind('instanceReady.ckeditor');
            $(this).unbind('focus');
            $(this).unbind('blur');
            $(this).ckeditorGet().updateElement();
            $(this).ckeditorGet().destroy();
            $(this).attr('contenteditable', false);
            $(this).data('status', 'destruct');
            
            //reload dataelements
            $(this).find('[data-element]').trigger('destruct');
            $(this).data('disable-construct', true);
            $(this).find('[data-element]').trigger('construct');
            $(this).data('disable-construct', false);
        }
    });
	
    $('[data-element="inlineButton"]').live("construct.inlineButton", function() {
        if($(this).data('status')!='construct'){
            var element = $(this).click(function(){
                var text = element.data('text-switch');
                element.data('text-switch',element.text());
                element.text(text);
                $(this).parents('.cm-editable').find('[data-element="inlineEditor"]').each(function(){
                    if($(this).data('status')!='destruct'){
                        $(this).trigger('destruct');
                    }else{
                        $(this).trigger('construct');
                    }
                });
                return false;
            });
        }
    });

    jQuery(document).ready(function($) {
        $(document.body).attr('spellcheck',false);
    });
	
})( window.jQuery );