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
                    },              
                    template: '<div class="tooltip tooltip-error" role="tooltip"><div class="tooltip-arrow arrow"></div><div class="tooltip-inner"></div></div>'
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
