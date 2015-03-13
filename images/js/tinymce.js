(function() {
	$(document).ready(function() {
		tinymce.init({
			selector: '.htmleditor_common',
			language: 'zh_CN',
			height: 400,
			menubar: false,
			plugins: 'advlist anchor autolink charmap code colorpicker directionality emoticons example example_dependency fullpage fullscreen hr image insertdatetime link lists media nonbreaking pagebreak preview print save searchreplace tabfocus table textcolor textpattern visualchars',
			image_advtab: true,
			toolbar: 'image link media',
			file_picker_callback: function(callback, value, meta) {
				// Provide file and text for the link dialog
				if (meta.filetype == 'file') {
					callback('mypage.html', {text: 'My text'});
				}

				// Provide image and alt text for the image dialog
				if (meta.filetype == 'image') {
					callback('myimage.jpg', {alt: 'My alt text'});
				}

				// Provide alternative source and posted for the media dialog
				if (meta.filetype == 'media') {
					callback('movie.mp4', {source2: 'alt.ogg', poster: 'image.jpg'});
				}
			}
		});
	});
})();
