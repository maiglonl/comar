/*
 * Functions to manage list of selectable items
 */
window.toogleHeader = function (elHeader){
		var elBase = $(elHeader).parent();
		elBase.find('.unselected-item').slideToggle();
		elBase.find('.card-header').slideToggle(function(){
			elBase.removeClass('opened-card').addClass('closed-card');
		});
}

window.toogleItem = function (elItem, handler){
	var elBase = $(elItem).parent().parent();
	var isOpen = elBase.hasClass('opened-card');
	if(!isOpen){
		elBase.find('.unselected-item').slideToggle();
		elBase.find('.card-header').slideToggle();
		$(elBase).removeClass('closed-card').addClass('opened-card');
	}else{
		elBase.find('.selected-item').removeClass('selected-item').addClass('unselected-item');
		$(elItem).removeClass('unselected-item').addClass('selected-item');
		elBase.find('.unselected-item').slideToggle();
		elBase.find('.card-header').slideToggle(function(){
			elBase.removeClass('opened-card').addClass('closed-card');
		});
		handler();
	}
}
