// 单个图片上传
(function($) {

	$.fn.upload_image = function(options) {

		return this.each(function(){
			
			var upload  = $(this).find('button.btn-upload');
			var input   = $(this).find('input:first');
			var percent = $(this).find('.progress-percent');

			//图片预览
			input.popover({placement:'bottom',html:true,trigger:'hover',content:function(){
				
				var value = input.val();
				
				if(value){
					return input.hasClass('error') ? false : '<img src="'+ value +'" style="max-width:400px;max-height:200px"/>';
				}

				return false;
			}});			

			// 上传属性
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
					percent.show().html(up.total.percent+'%');
				},
		        uploaded : function(up, file, response){

		            // 单个文件上传完成 返回信息在 response 中
		            if ( response.result.status )
		            {
		            	input.val(response.result.url);
		            }
		            
		        },
		        complete : function(up,files){
		            // 全部上传完成
		            percent.hide().html('');
		        },
				error : function(error,detail){
					$.error(detail);
				}
    		};

    		options = $.extend({}, defaults, options);

			
			upload.plupload(options);
		});

	}

})(jQuery);