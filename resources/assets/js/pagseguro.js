const pagSeguro = {
	getBrand: function(bin){
		return new Promise(function(resolve, reject){
			PagSeguroDirectPayment.getBrand({
				cardBin: bin,
				success: function(res){
					resolve({
						result: res,
						url: 'https://stc.pagseguro.uol.com.br/'
					});
				}
			});
		});
	}
}