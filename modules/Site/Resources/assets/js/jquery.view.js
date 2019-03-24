// 视图字段
(function($) {

	$.fn.view = function(options) {

		return this.each(function() {
			
			var input    = $(this).find('input:first');			
			var select   = $(this).find('.btn-select');

			var callback = function(value) {
				input.val(value);
			}

			// 文件选择
			select.on('click', function() {

				// 属性
				var url   = $(this).data('url');
				var title = $(this).data('title');

				// 对话框
		        var dialog = $.dialog({
			            title        : title,
			            url          : url,
			            width        : '95%',
			            height       : '75%',
			            padding      : 0,
			            ok           : $.noop,
			            cancel       : $.noop,
			            oniframeload : function() {
			                this.loading(false);
			            },
			            opener       :window
		        }, true).loading(true); 				

		        // 监听对话框关闭传值
				dialog.addEventListener('close', function () {
					var selected = this.returnValue;
	            	if (selected && selected.length) {
	                	callback(selected[0].view);
	                }
				});		        

			});
		});

	}

})(jQuery);
