window.filters = {
	role: function (cod){
		if (cod == null) return '-';
		switch(cod){
			case 'admin': return "Administrador"; break;
			case 'seller': return "Distribuidor"; break;
			case 'partner': return "Parceiro"; break;
			default: return "Cliente"; break;
		}
	},
	payment_name: function (cod){
		if (cod == null) return '-';
		switch(cod){
			case 'billet': return "Boleto"; break;
			default: return "Cartão de Crédito"; break;
		}
	},
	delivery_form: function (cod){
		if (cod == null) return '-';
		switch(parseInt(cod)){
			case 0: return "Retirar na loja"; break;
			case 1: return "Normal"; break;
			case 2: return "Rápido"; break;
			default: return "-"; break;
		}
	},
	deadline: function (days, startDay = null){
		var result = "Chegará entre ";
		var dates = startDay == null ? 
			moment().add(days, 'days').format('D')+" de "+moment().add(days, 'days').format('MMMM')+" e "+moment().add(days+4, 'days').format('D')+" de "+moment().add(days+4, 'days').format('MMMM'):
			moment(startDay, 'YYYY-MM-DD').add(days, 'days').format('D')+" de "+moment(startDay, 'YYYY-MM-DD').add(days, 'days').format('MMMM')+" e "+moment(startDay, 'YYYY-MM-DD').add(days+4, 'days').format('D')+" de "+moment(startDay, 'YYYY-MM-DD').add(days+4, 'days').format('MMMM');
		return "Chegará entre "+dates;
	},
	currency: function (n, r=false) {
		var z = n,
		c = 2, 
		d = ",", 
		t = ".", 
		s = n < 0 ? "-" : "", 
		i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
		j = (j = i.length) > 3 ? j % 3 : 0,
		x = r ? " R$ " : "",
		v = (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
		return s + x + v;
	},
	currency_sup: function (val){
		let v = filters.currency(val, true);
		let v1 = v.substr(-2, 2);
		let v2 = v.substr(0, v.length-3);
		let result = v2+"<sup>"+v1+"</sup>";
		return result;
	},
	limit_words: function(text, n){
		var words = text.split(' ');
		if(words.length <= n){
			return text;
		}
		var t = "";
		for (var i = 0; i < n-1; i++) {
			t += words[i]+" ";
		}
		t += words[n-1]+"...";
		return t;
	},
	name: function(value){
		if (value == null || value == '') return '-';
		return (value + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
			return $1.toUpperCase();
		});
	},
	default: function(value) {
		if (value == null || value == '') return '-';
		return value;
	},
	gender: function(value) {
		value = parseInt(value);
		if (value == null) return '-';
		switch(value){
			case 1: return "Masculino"; break;
			default: return "Feminino"; break;
		}
	},
	yn: function(value) {
		value = parseInt(value);
		if (value == null) return '-';
		switch(value){
			case 1: return "Sim"; break;
			default: return "Não"; break;
		}
	},
	cep: function(value) {
		if (!value) return '-';
		return value.replace(/^(\d{5})(\d{3}).*/, '$1-$2');
	},
	phone: function(value) {
		if (!value) return '-';
		return value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1)$2-$3');
	},
	date: function (value) {
		if (!value) return '-';
		date = value.split('-');
		return date[2]+"/"+date[1]+"/"+date[0];
	},
	datetime: function (value) {
		if (!value) return '-';
		dateTime = value.split(' ');
		date = dateTime[0].split('-');
		time = dateTime.length > 0 ? dateTime[1] : '';
		return date[2]+"/"+date[1]+"/"+date[0]+" "+time;
	},
	cpf: function(value) {
		if (!value) return '-';
		return value.replace(/^(\d{3})(\d{3})(\d{3})(\d{2}).*/, '$1.$2.$3-$4');
	},
	cnpj: function(value) {
		if (!value) return '-';
		return value.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2}).*/, '$1.$2.$3/$4-$5');
	}
};