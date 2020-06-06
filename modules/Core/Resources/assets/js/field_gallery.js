// 图集编辑器
$.fn.gallery_field = function (name, value, params) {
	return this.each(function () {
		new gallery_field($(this), name, value, params);
	});
}

function gallery_field($this, name, value, params) {
	var self = this;
	var empty = $this.find('.gallery-field-empty');
	var list = $this.find('.gallery-field-list');
	var select = $this.find('.gallery-field-select');
	var upload = $this.find('.gallery-field-upload');
	var description = $this.find('.gallery-field-description');
	var progress = $this.find('.gallery-field-progress');
	var upload_default = {
		multi_selection: true, //是否可以选择多个文件
		autostart: true, //自动开始
		filters: {
			max_file_size: '20mb',
			mime_types: [{
				title: "Image files",
				extensions: "jpg,jpeg,gif,png"
			}, ],
			prevent_duplicates: false //阻止多次上传同一个文件
		},
		progress: function (up, file) {
			progress.removeClass('d-none');
			progress.find('.progress-bar').width(up.total.percent + '%').html(up.total.percent + '%');
		},
		uploaded: function (up, file, response) {

			// 单个文件上传完成 返回信息在 response 中
			if (response.result.state) {
				self.add(response.result.url, response.result.description);
			} else {
				$.error(response.result.content);
			}
		},
		complete: function (up, files) {
			// 全部上传完成
			progress.addClass('d-none');
			progress.find('.progress-bar').width('0%').html('');
		},
		error: function (error, detail) {
			$.error(detail);
		}
	};

	var upload_options = $.extend({}, upload_default, params);
	var n = 0;

	// API methods
	$.extend(self, {
		init: function () {
			value = value || [];

			// 初始化数据
			$.each(value, function (i, data) {
				self.add(data.image, data.description);
			});

			// 拖动
			list.sortable({
				items: ".gallery-field-list-item",
				placeholder: "gallery-field-list-item ui-sortable-placeholder",
				update: function () {
					self.updatenumber();
				}
			});

		},
		// update selectall state
		add: function (image, description) {

			var c = '<div class="gallery-field-list-item">';
			c += '		<div class="gallery-field-list-item-image bg-image-preview"><img src="' + image + '"/><input type="hidden" name="' + name + '[' + n + '][image]" value="' + image + '"></div>';
			c += '		<div class="gallery-field-list-item-description"><textarea rows="2" class="form-control" name="' + name + '[' + n + '][description]" placeholder="' + field_gallery.description + '">' + (description || '') + "</textarea></div>";
			c += '		<div class="gallery-field-list-item-manage">';
			c += '			<b class="number">#' + (n + 1) + '</b>';
			c += '			<a class="js-delete-this float-right" href="javascript:;" title="' + field_gallery.delete + '"><i class="fa fa-times"></i></a>';
			c += '			<a class="js-upload-this float-right" href="javascript:;" title="' + field_gallery.upload_replace + '"><i class="fa fa-upload"></i></a>';
			c += '			<a class="js-preview-this float-right" href="javascript:;" title="' + field_gallery.preview + '"><i class="fa fa-search-plus"></i></a>';
			c += '		</div>';
			c += '</div>';

			var item = $(c).appendTo(list);
			var item_upload_options = $.extend({}, upload_options, {
				multi_selection: false,
				uploaded: function (up, file, response) {
					if (response.result.state) {
						item.find('img').attr('src', response.result.url);
						item.find('input').val(response.result.url);
					} else {
						$.error(response.result.content);
					}
				}
			});

			item.find('.js-upload-this').plupload(item_upload_options);
			empty.hide();

			n++;
		},

		// 重排行号
		updatenumber: function () {

			list.find(".gallery-field-list-item").each(function (d, a) {
				$(a).find("b.number").text('#' + (d + 1));
			});

			if (list.find('.gallery-field-list-item').length > 0) {
				empty.hide();
			} else {
				empty.show();
				n = 0;
			}
		}
	});

	// 初始化
	self.init();

	//上传
	upload.plupload(upload_options);

	// 图库
	select.on('click', function (e) {
		e.preventDefault();

		// 属性
		var url = $(this).data('url');
		var title = $(this).data('title');

		// 对话框
		var dialog = $.dialog({
			title: title,
			url: url,
			width: '95%',
			height: '75%',
			ok: $.noop,
			cancel: $.noop
		}, true).loading(true);

		// 监听对话框关闭传值
		dialog.addEventListener('close', function () {
			var selected = this.returnValue;
			if (selected && selected.length) {
				$.each(selected, function (i, data) {
					self.add(data.url, data.description);
				});
			}
		});
	});

	description.on('click', function (e) {
		e.preventDefault();

		var prompt = $(this).attr('title') || $(this).data('original-title');
		var title = $(this).text();
		var value = description.data('defaultvalue');

		$.prompt(prompt, function (val) {
			description.data('defaultvalue', val);
			list.find('textarea').val(val);
		}, value, 'textarea').title(title);
	})

	// 删除
	list.on('click', '.js-delete-this', function () {
		$(this).tooltip('dispose');
		$(this).parent().parent().remove();
		self.updatenumber();
	});

	// 预览
	list.on('click', '.js-preview-this', function () {
		$(this).tooltip('dispose');
		var image = $(this).parent().parent().find('input').val();
		$.image(image);
	});
}
