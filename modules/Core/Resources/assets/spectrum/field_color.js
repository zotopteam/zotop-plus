// 颜色选择器
(function($) {

    $.fn.field_color = function(options) {

        return this.each(function() {

            var input = $(this).find('input:first');
            var color = $(this).find('button.btn-color');
            

            var changecolor = function(value){
                
                if(value) {
                    value = value.toHexString();
                    color.addClass('active');
                    color.find('i').css({'color':value});                    
                } else {
                    color.removeClass('active');
                    color.find('i').css({'color':''});
                }

                input.val(value);                  
            }            

            // 色板属性
            var defaults = {
                color                  : input.css('color'),
                showPalette            : true,
                theme                  : 'zotop',
                maxSelectionSize       : 10,
                hideAfterPaletteSelect : true,
                showButtons            : false,
                showInitial            : false,
                preferredFormat        : 'hex',
                showInput              : true,
                allowEmpty             : true,
                showPaletteOnly        : false,
                showSelectionPalette   : true,                
                palette: [
                    ["#000000", "#434343", "#666666", "#999999", "#b7b7b7", "#cccccc", "#d9d9d9", "#efefef", "#f3f3f3", "#ffffff"],
                    ["#980000", "#ff0000", "#ff9900", "#ffff00", "#00ff00", "#00ffff", "#4a86e8", "#0000ff", "#9900ff", "#ff00ff"],
                    ["#e6b8af", "#f4cccc", "#fce5cd", "#fff2cc", "#d9ead3", "#d9ead3", "#c9daf8", "#cfe2f3", "#d9d2e9", "#ead1dc"],
                    ["#dd7e6b", "#ea9999", "#f9cb9c", "#ffe599", "#b6d7a8", "#a2c4c9", "#a4c2f4", "#9fc5e8", "#b4a7d6", "#d5a6bd"],
                    ["#cc4125", "#e06666", "#f6b26b", "#ffd966", "#93c47d", "#76a5af", "#6d9eeb", "#6fa8dc", "#8e7cc3", "#c27ba0"],
                    ["#a61c00", "#cc0000", "#e69138", "#f1c232", "#6aa84f", "#45818e", "#3c78d8", "#3d85c6", "#674ea7", "#a64d79"],
                    ["#85200c", "#990000", "#b45f06", "#bf9000", "#38761d", "#134f5c", "#1155cc", "#0b5394", "#351c75", "#741b47"],
                    ["#5b0f00", "#660000", "#783f04", "#7f6000", "#274e13", "#0c343d", "#1c4587", "#073763", "#20124d", "#4c1130"]
                ],                
                change: changecolor,
                move: changecolor
            };

            options = $.extend({}, defaults, options);
            
            color.addClass(function(){
                if(input.val()) {
                    color.find('i').css('color', input.val());
                    return 'active';
                }
            }).spectrum(options);
        });

    }

})(jQuery);
