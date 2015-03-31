(function() {
	$(document).ready(function() {
		inittinymce('basic', '.htmleditor_basic', 800, 400);
		inittinymce('full', '.htmleditor_full', 800, 600);
		
		$('body').delegate('#blogpost', 'click', function(){
			var title = $.trim($('#blogtitle').val());
			var content = tinymce.EditorManager.get('blogcontent').getBody().innerHTML;
			if (!title.length) {
				alert('请填写标题');
				return false;
			}
			$.post('/blog/post', {'title':title, 'content':content}, function(data, status){
				if (data.errno) {
					alert(data.error);
				} else {
				}
			}, 'json');
		});
	});
	function saveContent(content) {
		console.log(content);
		$.post('/user/draft', {'content':content}, function(data, status){
			if (data.errno) {
				alert(data.error);
				editor.isNotDirty = false;
				return false;
			} else {
				return true;
			}
		}, 'json');
	}
	function inittinymce(mode, selector, width, height) {
		var config = {
			'selector': selector,
			'language': 'zh_CN',
			'width': width,
			'height': height,
			'menubar': false,
			'save_onsavecallback': function(editor) {
				var content = editor.getBody().innerHTML;
				saveContent(content);
			},
			'setup': function(editor) {
				editor.on('init', function(e) {
					initplupload(editor);
				});
				editor.on('ProgressState', function(e) {
					if (e.state) {
						$('#mceu_imageko').css('z-index', 'auto');
					}
				});
				editor.on('change', function(e) {
					var content = editor.getBody().innerHTML;
					saveContent(content);
				});
				editor.addButton('imageko', {
					id: 'mceu_imageko',
					icon: 'image',
					tooltip: '插入图片'
				});
			}
		};
		if ('full' == mode) {
			config.plugins = 'advlist anchor autosave charmap colorpicker code directionality emoticons fullscreen hr insertdatetime link media nonbreaking pagebreak preview print save searchreplace table textcolor visualblocks visualchars';
			config.toolbar1 = 'undo redo | styleselect formatselect fontselect fontsizeselect | save';
			config.toolbar2 = 'visualblocks visualchars | anchor insertdatetime nonbreaking hr pagebreak charmap emoticons table link unlink imageko media | print searchreplace fullscreen code preview';
			config.toolbar3 = 'bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | forecolor backcolor | blockquote subscript superscript | ltr rtl | removeformat';
		} else {
			config.plugins = 'advlist colorpicker link preview save textcolor';
			config.toolbar1 = 'styleselect fontselect fontsizeselect forecolor backcolor | bullist numlist outdent indent | link imageko preview';
		}
		tinymce.init(config);
	}
	function initplupload(editor) {
		var imgsize = 600;
		var uploader = new plupload.Uploader({
			browse_button: 'mceu_imageko',
			url: '/image/upload',
			filters: {
				mime_types: [{title: '图片文件', extensions: 'jpg,gif,png'}],
				max_file_size: '50mb'
			},
			init: {
				FilesAdded: function(up, files) {
					plupload.each(files, function(file) {
						if ('image/gif' == file.type) {
							var img = new mOxie.FileReader();
							img.onload = function() {
								var data = {
									id: 'imageko_' + file.id,
									onload: 'if (this.width > this.height && this.width > ' + imgsize + ') {this.width = ' + imgsize + ';} else if (this.height > ' + imgsize + ') {this.height = ' + imgsize + ';}',
									src: img.result
								};
								editor.undoManager.transact(function() {
									editor.selection.setContent(editor.dom.createHTML('img', data));
								});
							};
							img.readAsDataURL(file.getSource());
						} else {
							var img = new mOxie.Image();
							img.onload = function() {
								var data = {
									id: 'imageko_' + file.id,
									src: img.getAsDataURL()
								};
								if (img.width > img.height && img.width > imgsize) {
									data.width = imgsize;
								} else if (img.height > imgsize){
									data.height = imgsize;
								}
								editor.undoManager.transact(function() {
									editor.selection.setContent(editor.dom.createHTML('img', data));
								});
								img.destroy();
							};
							img.load(file.getSource());
						}
					});
					uploader.start();
					editor.setProgressState(true);
				},
				FileUploaded: function(up, file, info) {
					eval('var data = ' + info.response);
					if (data.errno) {
						editor.dom.select('#imageko_' + file.id)[0].remove();
					} else {
						editor.dom.select('#imageko_' + file.id)[0].src = data.data.file600;
					}
				},
				UploadComplete: function(up, files) {
					editor.setProgressState(false);
					uploader.destroy();
					initplupload(editor);
				},
				Error: function(up, err) {
					alert(err.message);
				}
			}
		});
		uploader.init();
	}
})();
