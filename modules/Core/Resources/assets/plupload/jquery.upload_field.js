// 单文件上传
(function($) {

	$.fn.upload_field = function(options) {

		return this.each(function() {
			
			var upload   = $(this).find('button.btn-upload');
			var input    = $(this).find('input:first');			
			var progress = $(this).find('.progress');
			var select   = $(this).find('.js-upload-field-select');

			var callback = function(value) {
				input.val(value);
			}

			//图片预览
			if (input.attr('preview') == 'image') {
				input.popover({placement:'bottom',html:true,trigger:'hover',content:function(){
					var value = input.val();
					if (value) {
						return input.hasClass('error') ? false : '<div class="image bg-image-preview"><img src="'+ value +'"/></div>';
					}
					return false;
				}}).on('show.bs.popover', function () {
  					$($(this).data('bs.popover').getTipElement()).addClass('popover-image-preview');
				});
			}			

			// 文件上传
			var defaults = {
		        multi_selection : false, //是否可以选择多个文件
		        autostart : true, //自动开始
		        filters: {
		            max_file_size:'20mb',
		            mime_types : [
		                { title : "Image files", extensions : "jpg,jpeg,gif,png"},
		            ],
		            prevent_duplicates:false //阻止多次上传同一个文件
		        },
				progress : function(up,file){
					progress.removeClass('d-none');
		            progress.find('.progress-bar').width(up.total.percent+'%').html(up.total.percent+'%');
				},
		        uploaded : function(up, file, response){

		            // 单个文件上传完成 返回信息在 response 中
		            if (response.result.state) {
		            	callback(response.result.url);
		            } else {
		            	$.error(response.result.content);
		            }

		        },
		        complete : function(up, files){
		            // 全部上传完成
		            progress.addClass('d-none');
		            progress.find('.progress-bar').width('0%').html('');
		        },
				error : function(error, detail){
					$.error(detail);
				}
    		};

    		options = $.extend({}, defaults, options);
			upload.plupload(options);

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
	                	callback(selected[0].url);
	                }
				});		        

			});
		});

	}

})(jQuery);
