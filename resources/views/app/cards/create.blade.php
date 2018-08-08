<div>
	<div id="cardCreateApp">
		<h2 class="page-title">Cartão de Crédito <small> | Cadastro de cartão</small></h2>
		<form action="{{ route('cards.store') }}" id="formCreateCard" data-prefix="cc">
			@include('app.cards._form')
		</form>
	</div>

	<script type="text/javascript">
		new Vue({
			el: '#cardCreateApp',
			data: {
				card: {}
			},
			mounted: function(){
				var self = this;
				$("#formCreateCard").cValidate({
					data: self.card
				});
			}
		});
	</script>
</div>