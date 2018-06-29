
/** 
 * Validate Form
 */
(function ( $ ) {
	$.fn.cValidate = function(options){
		var form = this;
		var action = options.hasOwnProperty("route") ? options.route : $(form).attr('action');
		var prefix = $(form).is('[data-prefix]') ? $(form).attr('data-prefix')+'_' : '';
		var success = options.hasOwnProperty("success") ? options.success : 'Ação realizada com sucesso!';
		var error = options.hasOwnProperty("error") ? options.error : 'Falha ao realizar ação!';
		var redirect = options.hasOwnProperty("redirect") ? options.redirect : null;
		var method = options.hasOwnProperty("method") ? options.method : 'post';
		var reload = options.hasOwnProperty("reload") ? options.reload : false;
		var isPut = options.hasOwnProperty("isPut") ? options.isPut : false;
		isPut = $(form).attr('method') == 'PUT' ? true : isPut;
		var handler = options.hasOwnProperty("handler") ? options.handler : function(data) {	
			if(data.error){
				var msgs = {};
				$.each(data.message, function(index, val) {
					 msgs[prefix+index] = val[0];
				});
				form.validate().showErrors(msgs);
				if (typeof error === "function") {
					error();
				}else{
					error = data.message ? data.message : error;
					toastr.error(error);
				}
			}else{
				if(redirect != null && redirect != false){
					location.href = redirect;
					return true;
				}
				if(reload){
					location.reload();
					return true;
				}
				if (typeof success === "function") {
					success();
				}else{
					success = data.message ? data.message : success;
					toastr.success(success);
				}
				parent.jQuery.fancybox.close();
			}
		};
		form.validate({
			errorClass: 'invalid-feedback',
			errorElement: 'div',
			submitHandler: function(){
				if(isPut){ 
					$.put(action, options.data, handler); 
				}else{
					$.post(action, options.data, handler);
				}
			},
			highlight: function(element) {
				if ( element.type === "radio" ) {
					$( element ).parent().parent().addClass('is-invalid').removeClass('is-valid');
				} else {
					$( element ).addClass('is-invalid').removeClass('is-valid');
				}
			},
			unhighlight: function(element) {
				if ( element.type === "radio" ) {
					$( element ).parent().parent().removeClass('is-invalid').addClass('is-valid');
				} else {
					$( element ).removeClass('is-invalid').addClass('is-valid');
				}
			},
			errorPlacement: function(error, element) {
				if ( element[0].type == "radio" && element.parent("label").hasClass('btn')) {
					error.insertAfter(element.parent().parent());
				}else{
					error.insertAfter(element[0]);
				}
			}
		});
	}
}( jQuery ));
