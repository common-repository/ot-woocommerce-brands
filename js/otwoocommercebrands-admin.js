(function($){
$(document).ready(function() {

	function setCookie (name, value, expires, path, domain, secure) {
	      document.cookie = name + "=" + escape(value) +
	        ((expires) ? "; expires=" + expires : "") +
	        ((path) ? "; path=" + path : "") +
	        ((domain) ? "; domain=" + domain : "") +
	        ((secure) ? "; secure" : "");
	}
	function getCookie(name) {
		var cookie = " " + document.cookie;
		var search = " " + name + "=";
		var setStr = null;
		var offset = 0;
		var end = 0;
		if (cookie.length > 0) {
			offset = cookie.indexOf(search);
			if (offset != -1) {
				offset += search.length;
				end = cookie.indexOf(";", offset)
				if (end == -1) {
					end = cookie.length;
				}
				setStr = unescape(cookie.substring(offset, end));
			}
		}
		return(setStr);
	}

	if(!getCookie("uwbhidemessage")) {
		$('.uwb-message').css("display","block");
	}

	$('.uwb-message button.notice-dismiss').click(
		function() {
			setCookie("uwbhidemessage", 1);
			$('.uwb-message').hide();
		}
	);
	
	var custom_uploader;
    $('#otwcbr-image-button').click(function(e) {
        e.preventDefault();
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: true
        });
        custom_uploader.on('select', function() {   
            attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#otwcbr-image').val(attachment.url);
        });
        custom_uploader.open();

    });
});
})(jQuery);