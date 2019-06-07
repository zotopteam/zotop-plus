/**
 * 字段翻译
 * 
 * @author   hankx_chen
 * @created  2018-07-27
 * @version  3.0
 * @site     http://zotop.com
 */
(function($) {

    $.fn.translate = function (options) {
        var options = options || {};
        return this.each(function () {
            var source = $('[name=' + options.source+']');
            var target = $(this).find('input');
            var btn    = $(this).find('.btn-translate');

            // 获取按钮状态转换
            source.val() ? btn.removeClass('disabled') : btn.addClass('disabled');          
            source.on('change',function(){
                source.val() ? btn.removeClass('disabled') : btn.addClass('disabled');
            });

            // 数据转换
            btn.on('click',function(e){
                e.preventDefault();

                if (btn.hasClass('disabled') || target.is(':disabled')) {
                    return false;
                }

                btn.addClass('disabled').find('.translate-icon').addClass('fa-spin');
                $.post(options.url, {source:source.val(), format:options.format, from:options.from, to:options.to, separator:options.separator, maxlength:options.maxlength}, function(value){
                    target.val(value);
                    btn.removeClass('disabled').find('.translate-icon').removeClass('fa-spin');
                }).fail(function(xhr, status, error) {
                    btn.removeClass('disabled').find('.translate-icon').removeClass('fa-spin');
                });            
            });                    
        })
    }

})(jQuery);
