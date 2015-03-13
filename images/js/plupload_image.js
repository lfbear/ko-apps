(function() {
	$(document).ready(function() {
		var uploader = new plupload.Uploader({
			browse_button: 'plupload_browser',
			url: '/image/upload',
			filters: {
				mime_types: [{title: '图片文件', extensions: 'jpg,gif,png'}],
				max_file_size: '50mb'
			},
			init: {
				PostInit: function() {
					$('#plupload_filelist').html('');
					$('body').delegate('#plupload_upload', 'click', function(){
						uploader.start();
						return false;
					});
					$('body').delegate('#plupload_filelist b', 'click', function(){
						var fileid = $(this).closest('div').attr('id');
						uploader.removeFile(fileid);
					});
				},
				FilesAdded: function(up, files) {
					plupload.each(files, function(file) {
						$('#plupload_filelist').html($('#plupload_filelist').html()
							+ '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size)
							+ ') <b>x</b><span></span></div>');
						if ('image/gif' == file.type) {
							var img = new mOxie.FileReader();
							img.onload = function() {
								$('#plupload_filelist [id=' + file.id + '] span').html('<img src="' + img.result + '" width="100px">');
								img.destroy();
								img = null;
							};
							img.readAsDataURL(file.getSource());
						} else {
							var img = new mOxie.Image();
							img.onload = function() {
								$('#plupload_filelist [id=' + file.id + '] span').html('<img src="' + img.getAsDataURL() + '" width="100px">');
								img.destroy();
								img = null;
							};
							img.load(file.getSource());
						}
//						var str = "FilesAdded\n";
//						plupload.each(file, function(v, k) {
//							str += k + " : " + v + "\n";
//						});
//						alert(str);
					});
				},
				FilesRemoved: function(up, files) {
					plupload.each(files, function(file) {
//						var str = "FilesRemoved\n";
//						plupload.each(file, function(v, k) {
//							str += k + " : " + v + "\n";
//						});
//						alert(str);
						$('#plupload_filelist').find('[id=' + file.id + ']').remove();
					});
				},
				BeforeUpload: function(up, file) {
					//alert(file.id);
				},
				UploadProgress: function(up, file) {
					$('#' + file.id + ' b').html('<span>' + file.percent + "%</span>");
				},
				FileUploaded: function(up, file, info) {
					//alert(info.response);
				},
				UploadComplete: function(up, files) {
					plupload.each(files, function(file) {
						var str = "UploadComplete\n";
						plupload.each(file, function(v, k) {
							str += k + " : " + v + "\n";
						});
						alert(str);
					});
				},
				Error: function(up, err) {
					$('#console').html("Error #" + err.code + ": " + err.message + "\n" + $('#console').html());
				}
			}
		});
		uploader.init();
	});
})();
