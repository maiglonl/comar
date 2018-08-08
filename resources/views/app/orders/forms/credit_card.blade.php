<div>
	<div id="orderCardApp">
		<form action="{{ route('cards.store') }}" id="formOrderCard" data-prefix="cc">
			@include('app.cards._form')
		</form>
	</div>

	<script type="text/javascript">
		var orderCardApp = new Vue({
			el: '#orderCardApp',
			data: {
				card: {!! $order->toJson() !!}
			},
			mounted: function(){
				var self = this;
				$("#formOrderCard").cValidate({
					data: self.card,
				});
			},
			watch:{
				'card.number': function(val){
					var self = this;
					if(val.length == 9){
						setCardData(val, self.card);
					}
				}
			}
		});
	</script>
</div>