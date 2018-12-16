@extends('layouts.app')

@section('content')
	<div class="container-fluid" id="appHome">
		<div class="row">
			<div class="col">
				<div class="page-title">
					<h3>Resumo | <small>Minhas informações</small></h3>
				</div>
				<div class="row">
					<div class="col">
						<div class="card">
							<div class="card-body">
								<h3 class="card-title">À Pagar</h3>
								<h1 class="float-right text-danger" style="font-weight: bold;" v-html="$options.filters.currency_sup(valueToPay)"></h1>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="card">
							<div class="card-body">
								<h3 class="card-title">À Receber</h3>
								<h1 class="float-right text-info" style="font-weight: bold;" v-html="$options.filters.currency_sup(valueToReceive)"></h1>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="card">
							<div class="card-body">
								<h3 class="card-title">Recebido no mês anterior</h3>
								<h1 class="float-right text-success" style="font-weight: bold;" v-html="$options.filters.currency_sup(valueReceived)"></h1>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		new Vue({
			el: '#appHome',
			data: {
				toPay: {!! json_encode($toPay) !!},
				toReceive: {!! $toReceive !!},
				received: {!! $received !!},
				valueToPay: 0,
				valueToReceive: 0,
				valueReceived: 0
			},
			mounted: function(){
				let self = this;
				console.log(self.toPay);
				$.each(self.toPay, function(index, val) {
					self.valueToPay += val.order.total;
				});
				$.each(self.toReceive, function(index, val) {
					self.valueToReceive += val.value;
				});
				$.each(self.received, function(index, val) {
					self.valueReceived += val.value;
				});
			},
			filters: filters
		});
	</script>
@endsection
