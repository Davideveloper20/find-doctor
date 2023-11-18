/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 * 
 */

"use strict";
window.baseName = function(str) {
  str = decodeURIComponent(str);
  var base = new String(str).substring(str.lastIndexOf('/') + 1); 
  //if(base.lastIndexOf(".") != -1)       
    //base = base.substring(0, base.lastIndexOf("."));
  return base;
}
window.fileExtension = function(str) {
  str = decodeURIComponent(str);
  var base = new String(str).substring(str.lastIndexOf('.') + 1); 
  if(base.lastIndexOf(".") < 0)       
    return false;
  return base;
}
if($('#chatstatus').length > 0) {
	$('#chatstatus').on('change', function(evt) {
		var chk = this.checked;
		//$('#frm-chgchatstatus').submit();
		solstar.ajaxRequest(`Doctor/ChatStatus/${chk?1:0}`, 'POST', function(resp) {
		    if(resp.success) {
		      alertify.alert(`El Chat ha sido ${chk?'activado':'deactivado'}! `);
		    } else {
		      alertify.warning('Ha ocurrido un error! '+resp.message, 10);
		      this.checked = !this.checked;
		    }
		  }, {
		  }, true, null, function(err) {
		    alertify.warning('Ha ocurrido un error! '+(err.message || ''), 10);
		  }
		);
	});
}
$.extend(true, $, {
  readURL: function(input, img) {
    if (input.files && input.files[0] && img != '') {
      var reader = new FileReader();
      
      reader.onload = function(e) {
        $(img).attr('src', e.target.result);
      }
      
      reader.readAsDataURL(input.files[0]);
    }
  }
});
//var location = { origin: "http://localhost/@solstar/find_doctor_backup/fdadmin/"}