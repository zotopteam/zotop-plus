/**
 * jquery.upload.js
 *
 * Copyright 2010, zotop team
 * Released under GPL License.
 */

/**
jquery upload api

@example

	<script>
		$('#uploader-button').upload({
			url : '../upload.php',
			multi_selection : true, //是否可以选择多个文件
			autostart : true, //是否自动开始
			multipart_params : {username:'admin'},
			filters: {
				max_file_size : '10mb',
				mime_types : [
					{ title : "Image files", extensions : "jpg,gif,png" },
					{ title : "Zip files", extensions : "zip" }
				],
				prevent_duplicates:true //阻止多次上传同一个文件
			},			
			resize : {width : 200, height : 200, quality : 90, crop: false},
			dragdrop : '.dragdrop-area',
			uploaded : function(up, file){
				// 单个文件上传完成
			},
			complete : function(up,files){
				// 全部上传完成
			}
		});
	</script>

@example
	// Retrieving a reference to plupload.Uploader object
	var uploader = $('#uploade-button').upload();

	uploader.bind('FilesAdded', function() {

		// Autostart
		setTimeout(uploader.start, 1); // "detach" from the main thread
	});

@class upload
@constructor
@param {Object} 详细设置选项说明
	@param {String} [settings.runtimes="html5,flash,silverlight,html4"] 上传运行时，依次加载
	@param {String} [settings.url] 服务器端的接收上传数据的url
	@param {String} [settings.fileext] 允许上传的文件格式，默认为图片："jpg,jpeg,gif,png" 错误：`plupload.FILE_EXTENSION_ERROR`
	@param {String} [settings.filedescription] 允许上传的文件说明，默认为："Image file"
	@param {Object} [settings.params] 文件上传时传递的参数
	@param {Number|String} [settings.maxsize=20MB] 选择文件时的文件大小限制, 默认为byte ,支持 b, kb, mb, gb, tb 等单位. 如： "10mb" 或者 "100kb"`. 错误： `plupload.FILE_SIZE_ERROR`.
	@param {Number|String} [settings.maxcount=0] 选择文件个数限制，默认为不限制
	@param {Boolean} [settings.multiple=true] 是否允许一次选择多个文件
	@param {Object} [settings.resize] 客户端图片缩放. 仅支持 `image/jpeg` 和 `image/png` 的图片类型文件， 如： {width : 200, height : 200, quality : 90, crop: true}`
		@param {Number} [settings.resize.width] 文件最大宽度，超出自动缩放
		@param {Number} [settings.resize.height] 文件最大
		@param {Number} [settings.resize.quality=90] jpg图片压缩质量1-100
		@param {Boolean} [settings.resize.crop=false] 是否采用裁剪方式缩放图片，默认是缩放
	@param {Boolean|String} [settings.dragdrop=true] 支持拖动上传,默认元素为：[buttonid]_dragdrop
	@param {Boolean} [settings.autostart=true] 选择文件后自动开始上传
	@param {Number|String} [settings.chunk_size] 上传大文件时候切分文件上传可以突破上传限制
*/

(function($) {
	// 全部实例化的上传
	plupload.uploaders = {};

	// 文件数错误代码
	plupload.FILE_COUNT_ERROR = -9001;

	// 获取plupload的根路径
	plupload.basepath = function(){
		var els = document.getElementsByTagName('script'), src;
		for (var i = 0, len = els.length; i < len; i++) {
			src = els[i].src || '';
			if (/plupload[\w\-\.]*\.js/.test(src)) {
				return src.substring(0, src.lastIndexOf('/') + 1);
			}
		}
		return '';
	}

	//语言翻译
	plupload.t = function(str, args) {
		str = plupload.translate(str) || str;
		str = plupload.format(str, args);
		return str;
	}

	//格式化 "{0} {1} {0}"
	plupload.format = function(str,args){
		if ( typeof args == 'object' ){
			return str.replace(/\{(\d+)\}/g, function(m,i){
				return args[i];
			});
		}
		return str;
	};

	//获取文件格式
	plupload.ext = function(o){
		return o.replace(/.+\./,"").toLowerCase();
	}

	// 数字转化
	plupload.parseInt = function(i, d){
		return isNaN(parseInt(i)) ? ( d || 0 ) : parseInt(i);
	}

	//转换resize
	plupload.resize = function(r,w,h,q){
		r = plupload.parseInt(r);
		w = plupload.parseInt(w);
		h = plupload.parseInt(h);
		q = plupload.parseInt(q, 90);

		if ( r && w && h ){
			c = ( r == 2 ) ? true : false;
			return {width:w, height:h, quality:q, crop : c}
		}

		return false;
	}

	// 处理返回数据
	plupload.parseJSON = function(data) {
		var obj;
		try {
			obj = $.parseJSON(data);
		} catch (e) {
			obj = {state:false,content:data};
		}
		return obj;
	}

    //返回uploader对象
    $.fn.plupload = function(options) {
        if (options){

            $(this)._upload(options);
        }
        return $(this)._upload();
    }

	//该插件不能直接返回uploader对象
	$.fn._upload = function(options){

		if (!options) return plupload.uploaders[$(this[0]).attr('id')];

		var defaults = {
			runtimes : 'html5,flash,html4',
			multipart_params:{},
            chunk_size : '2mb',
            unique_names : true, // 唯一文件名
			dragdrop : true,
            autostart : true,
			maxcount : 100,
			headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    },			
			error : function(error,detail){
				alert(error +' '+ detail);
			}
		}

		options = $.extend({}, defaults, options);

		// 载入flash 和 silverlight 上传文件
		options.basepath = options.basepath || plupload.basepath();
		options.flash_swf_url = options.flash_swf_url || options.basepath + 'Moxie.swf';
		options.silverlight_xap_url = options.silverlight_xap_url || options.basepath + 'Moxie.xap';

		// 生成实例
		return $(this).each(function(){

			var $this = $(this);
			var id = $this.attr('id');

			// 给容器赋予id
			if(!id){
				id = plupload.guid();
				$this.attr('id', id);
			}

			// 设置选择文件id为当前对象ID
			options.browse_button = id;

			// 设置默认拖拽上传区域，如果不存在，则将按钮本身设为拖拽区域
			options.drop_element = id + "-dragdrop";

			if ( options.dragdrop && $('#'+options.drop_element).length == 0 )
			{
				options.drop_element = id;
			}

			console.log(options);

			// 创建上传对象
			var uploader = new plupload.Uploader(options);
				uploader.self = $this;
				uploader.id = id;
				uploader.content = $this.html();

				//设置附加属性
				uploader.params = function(key,val){
					if( val === undefined ){
						return uploader.settings.multipart_params[key];
					}else{
						return uploader.settings.multipart_params[key] = val;
					}
				}

				//默认为AJAX模式
				uploader.params('IS_AJAX',true);
				

			// 存储对象
			plupload.uploaders[id] = uploader;

			// 删除实例
			function destroy() {
				delete plupload.uploaders[id];
				uploader.destroy();
				$this.html(uploader.content);
				uploader = $this = null;
			}

			//初始化
			uploader.bind('Init', function(up, res) {

				//zotop.debug(up);

			});

			uploader.bind("PostInit", function(up) {

				// 拖放区域设置
				console.log(up.settings.drop_element);
				if ( up.features.dragdrop && up.settings.drop_element ){
					$(up.settings.drop_element).on('dragover',function(){
						$(this).addClass('upload-dragover');
					}).on('drop',function(){
						$(this).addClass('upload-drop');
						$(this).removeClass('upload-dragover');
					}).on('dragleave',function(){
						$(this).removeClass('upload-drop upload-dragover');
					}).addClass('upload-dropbox');
				}

				typeof options.init == 'function' && options.init(up);

				uploader.content = $this.html();
			});


			// 检查文件个数
			if ( uploader.settings.maxcount ) {
				uploader.settings.multiple_queues = false; // one go only

				uploader.bind('FilesAdded', function(up, selectedFiles) {
					var selectedCount = selectedFiles.length;
					var extraCount = up.files.length + selectedCount - up.settings.max_file_count;

					if (extraCount > 0) {
						selectedFiles.splice(selectedCount - extraCount, extraCount);

						up.trigger('Error', {
							code : plupload.FILE_COUNT_ERROR,
							message :plupload.t('File count error.')
						});
					}
				});
			}

			uploader.init();


			// 添加事件
			uploader.bind('FilesAdded', function(up, files) {

				typeof options.add == 'function' && options.add(up, files);

				//自动上传
				if (up.settings.autostart) {
					setTimeout(function(){uploader.start();}, 10);
				}

				up.refresh(); // Reposition Flash/Silverlight
			});

			uploader.bind("Error", function(up, err) {

				var file = err.file, message = err.message, details = "";

				switch (err.code) {
					case plupload.FILE_EXTENSION_ERROR:
						details = plupload.t("Invalid file extension: {0}", [file.name]);
						break;

					case plupload.FILE_SIZE_ERROR:
						details = plupload.t("File {0} is too large,max file size: {1}" , [file.name, up.settings.filters.max_file_size]);
						break;

					case plupload.FILE_DUPLICATE_ERROR:
						details = plupload.t("{0} already present in the queue" , [file.name]);
						break;

					case plupload.FILE_COUNT_ERROR:
						details = plupload.t("Upload element accepts only {0} file(s) at a time. Extra files were stripped", [up.settings.max_file_count]);
						break;

					case plupload.IMAGE_FORMAT_ERROR :
						details = plupload.t("Image format either wrong or not supported");
						break;

					case plupload.IMAGE_MEMORY_ERROR :
						details = plupload.t("Runtime ran out of available memory");
						break;

					case plupload.HTTP_ERROR:
						details = plupload.t("Upload URL might be wrong or doesn't exist");
						break;
				}

				if (err.code === plupload.INIT_ERROR) {
					setTimeout(function(){self.destroy();}, 1);
				}else {
					options.error(message, details);
				}

			});


			//状态
			uploader.bind('StateChanged', function() {
				if ( uploader.state === plupload.STARTED ) {

					// 禁用上传按钮
					uploader.disableBrowse(true);

					typeof options.started == 'function' && options.started(uploader);
				}else{

					//启用上传按钮
					uploader.disableBrowse(false);

					typeof options.stoped == 'function' && options.stoped(uploader);
				}
			});

			//队列变化
			uploader.bind('QueueChanged', function(){
				typeof options.changed == 'function' && options.changed(up, file);
			});

			// 初始化当前文件上传
			uploader.bind("BeforeUpload", function(up, file){

				//开启unique_names时，将原文件名通过params传入
				up.settings.multipart_params['filename'] = file.name;

				typeof options.beforeupload == 'function' && options.beforeupload(up, file);
			});

			// 设置上传进度
			uploader.bind("UploadProgress", function(up, file) {
				// 自定义进度
				typeof options.progress == 'function' && options.progress(up, file);			
			});

			//文件上传文成
			uploader.bind('FileUploaded', function(up, file, info){

				var data = plupload.parseJSON(info.response);

			    if (typeof console != 'undefined'){
			        console.log(data);
			    }
			    
				//每个上传成功都会调用该函数
				typeof options.uploaded == 'function' && options.uploaded(up,file,data);

			});

			// Set file specific progress
			uploader.bind("UploadComplete", function(up, files) {
				typeof options.complete == 'function' && options.complete(up, files);
			});
		});
	}

})(jQuery);


// 单个图片上传
(function($) {

	$.fn.fileimage = function(options) {

		return this.each(function(){
			
			var upload  = $(this).find('button.btn-upload');
			var input   = $(this).find('input:first');
			var percent = $(this).find('.progress-percent');

			// 图片预览
			input.popover({placement:'bottom',html:true,trigger:'hover',title:false,content:function(){
				
				var value = input.val();
				
				if(value){
					return input.hasClass('error') ? false : '<img src="/uploads'+ value +'" style="max-width:400px;max-height:200px"/>';
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
					percent.html(up.total.percent+'%');
				},
		        uploaded : function(up, file, response){
		            // 单个文件上传完成 返回信息在 response 中

		            if ( response.result.status )
		            {
		            	input.val(response.result.path);
		            } 
		            
		        },
		        complete : function(up,files){
		            // 全部上传完成
		            percent.html('');
		        },
				error : function(error,detail){
					alert(detail);
				}
    		};

    		options = $.extend({}, defaults, options);

			
			upload.plupload(options);
		});

	}

})(jQuery);

// 多图片上传
(function($) {

	$.fn.filegallery = function(name, value, options) {

		return this.each(function(){
			
			value = value || [];

			var upload           = $(this).find('.btn-upload');
			var progress         = $(this).find('.progress');
			var progress_bar     = $(this).find('.progress-bar');
			var progress_percent = $(this).find('.progress-percent');
			var body             = $(this).find('.gallery-body');

			function showimage(path){
				body.append('<div class="gallery-item"><div class="preview"><img src="/uploads'+ path +'"><input type="hidden" name="'+name+'[]" value="'+ path +'"></div><span class="delete" title="删除">&times;</span></div>');
				body.find('.gallery-empty').hide();
			}

			//默认值
			$.each(value, function(i,path){
				showimage(path);
			});

			// 拖动
			body.sortable({
				items: ".gallery-item",
				placeholder:"gallery-item sortable-placeholder",
				update:function(){
				}				
			});

			// 删除
			body.on('click','.delete',function(){
				  $(this).parent().remove();
			})				

			// 上传属性
			var defaults = {
		        multi_selection : true, //是否可以选择多个文件
		        autostart : true, //自动开始
		        filters: {
		            max_file_size:'20mb',
		            mime_types : [
		                { title : "Image files", extensions : "jpg,jpeg,gif,png"},
		            ],
		            prevent_duplicates:false //阻止多次上传同一个文件
		        },
				progress : function(up,file){
					progress.show();
					progress_bar.css('width',up.total.percent + '%');
					progress_percent.html(up.total.percent+'%');
				},
		        uploaded : function(up, file, response){
		            // 单个文件上传完成 返回信息在 response 中
		            if ( response.result.status )
		            {
		            	showimage(response.result.path);
		            }		            
		        },
		        complete : function(up,files){
		            // 全部上传完成
		            progress.hide();
		        },
				error : function(error,detail){
					alert(detail);
				}
    		};

    		options = $.extend({}, defaults, options);
			
			upload.plupload(options);
		});

	}

})(jQuery);