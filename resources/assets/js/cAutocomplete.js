
/**
 * Autocomplete personalizado
 */
$.widget("custom.wAutocomplete", $.ui.autocomplete, {
	_create: function() {
		this._super();
		this.widget().menu("option", "items", "> :not(.ui-autocomplete-category)");
	}
});

/**
 * Autocomplete customizado
 * @param  { [items|url], searchAttr, idAttr, idField }
 */
(function ( $ ) {
	$.fn.cAutocomplete = function(options){
		var idField = options.hasOwnProperty("idField") ? options.idField : "#id_"+this.attr("id");
		var updateOnFocus = options.hasOwnProperty("updateOnFocus") ? options.updateOnFocus : true;
		var appendId = ""; 
		if($(this).hasClass('ac-append-bellow')){
			appendId = "#ui-autocomplete-container";
			$(this).after('<div id="ui-autocomplete-container"></div>');
		}
		this.wAutocomplete({
			source: function (requestObj, responseFunc) {
				var matchArray = [];
				if(options.hasOwnProperty("items")){
					/** Filtragem no JS com array de elementos */
					matchArray = options.items;
					var srchTerms = $.trim(requestObj.term).split(/\s+/);
					$.each (srchTerms, function (J, term) {
						var regX = new RegExp(term, "i");
						matchArray = $.map(matchArray, function (item) {
							return regX.test(item[options.searchAttr]) ? item : null;
						});
					}); 
				}else{
					/** Filtragem no backend */
					var srchTerms = $.trim(requestObj.term);
					$.get_sync(options.url, { search: srchTerms }, function(result){
						matchArray = result;
					});
				}
				responseFunc(matchArray);
			},
			change: function (event, ui) {
				if(!ui.item){
					$(this).val("");
					var $input = $(idField).val("");
					var e = document.createEvent('HTMLEvents');
					e.initEvent('input', true, true);
					$input[0].dispatchEvent(e);
				}
			},
			focus: function(event, ui) {
				if(updateOnFocus){
					$(this).val(ui.item[options.searchAttr]);
					var $input = $(idField).val(ui.item[options.idAttr]);
					var e = document.createEvent('HTMLEvents');
					e.initEvent('input', true, true);
					$input[0].dispatchEvent(e);
					return false;
				}
			},
			select: function(event, ui) {
				$(this).val(ui.item[options.searchAttr]);
				var $input = $(idField).val(ui.item[options.idAttr]);
				var e = document.createEvent('HTMLEvents');
				e.initEvent('input', true, true);
				$input[0].dispatchEvent(e);
				return false;
			},
			open: function (event, ui) {
				var resultsList = $("ul.ui-autocomplete > li.ui-menu-item > a");
				var srchTerm = $.trim($(this).val()).split(/\s+/).join('|');
			},
			messages: {
				noResults: '',
				results: function() {}
			},
			appendTo: appendId
		}).wAutocomplete("instance")._renderItem = function( ul, item ) {
			return $("<li>")
			.append("<div class=\"ui-menu-item-wrapper\">"+filtersVue.name(item[options.searchAttr])+"</div>")
			.appendTo( ul );
		};
	}
}( jQuery ));
