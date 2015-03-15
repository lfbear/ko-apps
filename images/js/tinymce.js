(function() {
	$(document).ready(function() {
		tinymce.init({
			selector: '.htmleditor_common',
			language: 'zh_CN',
			height: 600,
			menubar: false,
			plugins: 'code',
			toolbar: 'undo redo code imageko',
			setup: function(editor) {
				editor.on('init', function(e) {
					initplupload(editor);
				});
				editor.on('ProgressState', function(e) {
					if (e.state) {
						$('#mceu_imageko').css('z-index', 'auto');
					}
				});
				editor.addButton('imageko', {
					id: 'mceu_imageko',
					icon: 'image',
					tooltip: '插入图片'
				});
			}
		});
	});
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
									//editor.dom.select('#imageko_' + file.id)[0].onload = 'alert(123);';
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
					if (data.err) {
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
