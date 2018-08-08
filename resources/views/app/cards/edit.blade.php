<div>
	<div id="cardEditApp">
		<h2 class="page-title">Cartão de Crédito <small> | Edição de cartão</small></h2>
		<form action="{{ route('cards.update', [$card->id]) }}" id="formEditCard" method="PUT" data-prefix="cat">
			@include('app.cards._form')
		</form>
	</div>

	<script type="text/javascript">
		new Vue({
			el: '#cardEditApp',
			data: {
				card: {!! $card->toJson() !!}
			},
			mounted: function(){
				var self = this;
				$("#formEditCard").cValidate({
					data: self.card
				});
			}
		});
	</script>
</div>