/*! Global js */

// Laravel的VerifyCsrfToken验证
$(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        }
    });
});


$(function(){
    
    // tooltip
    $(document).tooltip({placement:function(tip, element){
        return $(element).data('placement') ? $(element).data('placement') : 'bottom';
    },selector:'[data-toggle="tooltip"],a[title]',html:true,trigger:'hover'});   
 
    // niceScroll
    $('.scrollable').niceScroll();

    // maxlength
    $('input[maxlength],textarea[maxlength]').maxlength({alwaysShow:true,appendToParent:true,threshold:10,separator:'/',placement:'bottom-right-inside'});  

    $('textarea[maxlength]').on('autosize.resized', function() {
        $(this).trigger('maxlength.reposition');
    });    
});

// Validation 扩展
$(function(){

    // 增加正则验证
    $.validator.addMethod("pattern", function(value, element, param) {
        if (this.optional(element)) {
            return true;
        }
        if (typeof param === "string") {
            param = new RegExp("^(?:" + param + ")$");
        }
        return param.test(value);
    }, $.validator.messages.pattern);

    // 使用bootstrap tooltip 作为错误提示
    $.extend(jQuery.validator.defaults, {
        ignoreTitle: true,
        showErrors: function(errorMap, errorList) {
            
            $.each(this.successList, function(index, value) {
                return $(value).removeClass('error').tooltip("dispose");
            });

            return $.each(errorList, function(index, value) {

                $(value.element).removeClass('error').tooltip("dispose");
                
                var tooltip = $(value.element).addClass('error').tooltip({
                    trigger: "manual",
                    container: $(value.element).parents('div:first'),
                    html: true,
                    title: function(element){
                        var title='';               
                        if (typeof(value.message) == 'object') {
                            $.each(value.message,function(i,message){
                                title += '<div class="tooltip-item">'+message+'</div>';
                            });
                        } else {
                            title = value.message;
                        }
                        return title;
                    },
                    placement: function(tip, element){
                        return $(element).data('placement') ? $(element).data('placement') : 'bottom';
                    }            
                }).on('show.bs.tooltip', function () {
                    $($(this).data('bs.tooltip').getTipElement()).addClass('tooltip-error');
                });

                //tooltip.data("bs.tooltip").options.title = value.message;

                return $(value.element).tooltip("show");
            });
        }
    });


    $.validator.prototype.__resetForm = $.validator.prototype.resetForm;

    $.extend($.validator.prototype, {
        
        resetForm : function(){

            $.each(this.errorList, function (index, value) {
                $(value.element).tooltip('dispose');
            });

            this.__resetForm();
            return this;
        }
    });    
});


//dialog
$(function(){
    
    // ajax post 点击链接使用post链接，并返回提示信息
    $(document).on('click', 'a.js-post',function(event){
        event.preventDefault();

        var icon = $(this).find('.fa');
        var href = $(this).data('url') || $(this).attr('href');
        var data = $(this).data('post') || {};

        if ( icon.length > 0 ){
            icon.addClass('fa-spin fa-spinner');
        }else{
            $.loading();
        }       
        
        $.post(href, data, function(msg){
            $.msg(msg);

            if ( icon.length > 0 ){
                icon.removeClass('fa-spin fa-spinner');
            }          
        },'json');

        event.stopPropagation();
    });

    $(document).on('click', 'a.js-confirm', function(event){
        event.preventDefault();

        var href    = $(this).data('url') || $(this).attr('href');
        var text    = $(this).attr('title') || $(this).data('original-title') || $(this).text();
        var confirm = $(this).data('confirm') || $.trans('您确定要 [{0}] 嘛?', text);
        var method  = $(this).data('method') || 'POST';

        var $dialog = $.confirm(confirm,function(){
            $dialog.loading(true);
            $.ajax({url:href,type:method,dataType:'json',success:function(msg){
                $dialog.close().remove();
                $.msg(msg);
            }});
            return false;
        }).title(text);

        event.stopPropagation();
    });    

    $(document).on('click', 'a.js-delete', function(event){
        event.preventDefault();

        var href    = $(this).data('url') || $(this).attr('href');
        var text    = $(this).attr('title') || $(this).data('original-title') || $(this).text();
        var confirm = $(this).data('confirm') || $.trans('您确定要 {0} 嘛?', text);
        var method  = $(this).data('method') || 'DELETE';

        var $dialog = $.confirm(confirm,function(){
            $dialog.loading(true);
            $.ajax({url:href,type:method,dataType:'json',success:function(msg){
                $dialog.close().remove();
                $.msg(msg);
            }});
            return false;
        }).title(text);

        event.stopPropagation();
    });

    $(document).on('click', 'a.js-open',function(event){
        event.preventDefault();

        var url     = $(this).data('url') || $(this).attr('href');
        var title   = $(this).attr('title') || $(this).data('original-title') || $(this).text();
        var width   = $(this).data('width') || 'auto';
        var height  = $(this).data('height') || 'auto';     
        var $dialog = $.dialog({
            title:title,
            url:url,
            width:width,
            height:height,
            ok:$.noop,
            cancel:$.noop,
            oniframeload: function() {
                this.loading(false);
            },
            opener:window
        },true).loading(true);

        event.stopPropagation();
    });

    $(document).on('click', 'a.js-prompt', function(event){
        event.preventDefault();

        var href   = $(this).data('url') || $(this).attr('href');
        var value  = $(this).data('value');
        var prompt = $(this).data('prompt');
        var type   = $(this).data('type') || 'text';
        var title  = $(this).attr('title') || $(this).data('original-title') || $(this).text();        
        var $dialog = $.prompt(prompt,function(newvalue){

            var input = type=='textarea' ? this._$('content').find('textarea')[0] : this._$('content').find('input')[0];

            if( $.trim(newvalue) == '' ) {
                $dialog.shake();
                input.select();
                input.focus();
            }else{              
                $dialog.loading(true);
                $.post(href,{newvalue:newvalue},function(msg){
                    if( msg.state ){
                        $dialog.close().remove();
                    }else{
                        $dialog.loading(false);
                    }
                    $.msg(msg);
                },'json').fail(function(jqXHR){
                    input.select();
                    input.focus();
                    $dialog.loading(false);
                    $.error(jqXHR.responseJSON.newvalue[0]);
                });
            }

            return false;

        }, value, type).title(title);

        event.stopPropagation();
    });

    $(document).on('click', 'a.js-image', function(event){
        event.preventDefault();

        var url   = $(this).data('url');
        var title = $(this).data('title');
        var $dialog = $.image(url, title);

        event.stopPropagation();
    });
})


//表格行排序 sortable
$(function(){

    $("table.table-sortable").each(function(index,table){
        $(table).sortable({
            items: "tbody > tr",
            handle: "td.drag",
            axis: "y",
            placeholder:"ui-sortable-placeholder",
            helper: function(e,tr){
                // tr.children().each(function(){
                //     $(this).width($(this).width());
                // });
                return tr;
            },
            update:function(){
                var action = $(this).parents('form').attr('action');
                var data   = $(this).parents('form').serialize();

                $.post(action, data, function(msg){
                    $.msg(msg);
                },'json');
            }
        });        
    });

});
