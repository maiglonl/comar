/**
 * Dispatch event when radio button group change
 */
/*
	$(this).val(ui.item[options.searchAttr]);
	var $input = $(idField).val(ui.item[options.idAttr]);
	var e = document.createEvent('HTMLEvents');
	e.initEvent('input', true, true);
	$input[0].dispatchEvent(e);
	return false;
 */

/* Define por padrão o csrf_token em todas as requisições ajax */
$(function() {
	$(window).bind("load", function() {
		$('.load-page').hide();
	});
	$.ajaxSetup({
		headers: {
			'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
		}
	});
});

/* Função para consulta de cep e atualiza dados do endereço */
window.setAddressData = function(cep, data) {
	let url = "https://viacep.com.br/ws/"+ cep.replace(/\W/g, '') +"/json";
	axios.get(url).then(function (resp) {
		if (resp.data.erro) {
			toastr.error("CEP não encontrado!");
			data.street = "";
			data.district = "";
			data.city = "";
			data.state = "";
		}
		data.street = resp.data.logradouro;
		data.district = resp.data.bairro;
		data.city = resp.data.localidade;
		data.state = resp.data.uf;
	}).catch(function(error) {
		toastr.error("Falha ao consultar cep");
	});
}

/**
 * Busca view e atribui ao elemento
 */
window.setView = function(route, el){
	$.get_sync(route, null, function(result){
		$(el).html(result);
	});
}

/**
 * Compare if a 'date' is between 'start' and 'end'
 * @param  {string} date
 * @param  {string} start
 * @param  {string} end
 * @return {[true|false]}
 */
window.dateBetween = function(date, start, end) {
	date = Date.parse(date);
	start = Date.parse(start);
	end = Date.parse(end);
	return date-start >= 0 && date-end <=0;
}

/**
 * Configuração padrão de validação
 */
window.validaForm = function(form, submitFunc){
	$(form).validate({
		errorClass: 'invalid-feedback',
		submitHandler: function(data){
			if(data.error){
				this.showErrors(data.message);
			}
			submitFunc(data);
		},
		errorElement: 'div',
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

/**
 * Formata a data do mysql para o formato brasileiro.
 *
 * @param date a ser formatada.
 * @return string data no formato nacional dd/mm/aaaa.
 */
window.formatDateBR = function (date){
	if(date == null || date == "" || date == "-"){
		return "-";
	}else{
		var date = date.replace(/^([0-9]{4})-([0-9]{2})-([0-9]{2}).*$/,'$3/$2/$1');
	}
	return date;
}

/* 
 * Extend jQuery with functions for PUT and DELETE requests.
 * Extendendo função $.ajax para suportar put, delete e get_sync 
 */
function _ajax_request(url, data, callback, type, method, async) {
	if (jQuery.isFunction(data)) {
		callback = data;
		data = {};
	}
	return jQuery.ajax({
		type: method,
		url: url,
		data: data,
		success: callback,
		dataType: type,
		async: async
	});
}
jQuery.extend({
	put: function(url, data, callback, type=null, async=false) {
		return _ajax_request(url, data, callback, type, 'PUT', async);
	},
	delete: function(url, data, callback, type=null, async=false) {
		return _ajax_request(url, data, callback, type, 'DELETE', async);
	},
	get_sync: function(url, data, callback, type=null){
		return _ajax_request(url, data, callback, type, 'GET', false);
	},
	post_sync: function(url, data, callback, type=null){
		return _ajax_request(url, data, callback, type, 'POST', false);
	}
});

/*
 * Função para fechar fancybox com classe .closeFancybox
 */
$(function(){
	$(document).on('click','.closeFancybox',function(){
		$.fancybox.close();
	});
	$().fancybox({
		selector : '[data-fancybox="gallery"]',
		loop     : true
	});
});

/*
 * Função auxiliar para form-labels
 */
$(function(){
	if($(".float-label-select").val() == null || $(".float-label-select").val() == ''){
		$(".float-label-select").removeClass('selected');
	}else{
		$(".float-label-select").addClass('selected');
	}

	$(document).on('change', '.float-label-select', function() {
		if($(this).val() == null || $(this).val() == ''){
			$(this).removeClass('selected');
		}else{
			$(this).addClass('selected');
		}
	});
});
/*
 * Métodos adicionais para mask
 */
//$.mask.definitions['h'] = "[A-Za-z]";
//$.mask.definitions['~'] = '([0-9] )?';

/*
 * Configura campos File com layout Bootstrap
 */
$(function(){
	$(document).on('change',".bsFileUpload input", function(){
		var result = "";
		$.each($(this)[0].files, function(index, val) { result += val.name+"; "; });
		$(this).parent().parent().find('.form-control').html(result);
	});
	$(document).on('click',".bsFileUpload span", function(){
		$(this).parent().find('input[type=file]').click();
	});
});

/**
 * Sort an array of object by defined properties 
 * @param string
 * @returns array sorted by property
 * @usage array.sortBy("name","age");
 */
!function() {
	function _dynamicSortMultiple(attr) {
		var props = arguments;
		return function (obj1, obj2) {
			var i = 0, result = 0, numberOfProperties = props.length;
			while(result === 0 && i < numberOfProperties) {
				result = _dynamicSort(props[i])(obj1, obj2);
				i++;
			}
			return result;
		}
	}
	function _dynamicSort(property) {
		var sortOrder = 1;
		if(property[0] === "-") {
			sortOrder = -1;
			property = property.substr(1, property.length - 1);
		}
		return function (a,b) {
			var result = (a[property] < b[property]) ? -1 : (a[property] > b[property]) ? 1 : 0;
			return result * sortOrder;
		}
	}
	Object.defineProperty(Array.prototype, "sortBy", {
		enumerable: false,
		writable: true,
		value: function() {
			return this.sort(_dynamicSortMultiple.apply(null, arguments));
		}
	});
}();

/**
 * Função para preencher select
 * @usage $('#my_div').fillOptions({options});
 */
$.fn.extend({
	fillOptions: function (opt){
		var objList;
		$.get_sync(opt.src, null, function(result){
			objList = result;
		});
		var list = "<option value>---Selecione---</option>";
		if(objList != null){
			$.each( objList, function( index, obj ) {
				list += "<option value=\""+obj[opt.value]+"\">";
				$.each(opt.text, function(index, val2) {
					if(obj[val2.field] != null){
						list += val2.prefix+obj[val2.field]+val2.suffix;
					}
				});
				list += "</option>";
			});
		}
		this.append(list);
	}
});


/**
 * Cookies Helpers
 */

window.setCookie = function(cname, cvalue, exdays) {
	var d = new Date();
	d.setTime(d.getTime() + (exdays*24*60*60*1000));
	var expires = "expires="+d.toUTCString();
	document.cookie = cname + "=" + cvalue + "; " + expires;
}

window.getCookie = function(cname){
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for(var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
		}
	}
	return "";
}

window.deleteCookie = function(cname){
	document.cookie = cname+"=;expires=Wed 01 Jan 1970"
}
