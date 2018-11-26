// 颜色选择器
(function($) {

    $.fn.field_toggle = function(options) {

        return this.each(function() {

            var checkbox = $(this).find('input:first');
            var input    = $(this).find('input:last');

            checkbox.on('change', function(){
                
                if ($(this).is(':checked')) {
                    value = checkbox.data('enable');
                } else {
                    value = checkbox.data('disable');
                }

                input.val(value);
            })
        });

    }

    $(function(){
        $('.form-control-toggle').field_toggle();
    });

})(jQuery);
