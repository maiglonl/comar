<div>
	<div id="orderAddressApp">
		<h2 class="page-title">Endereço <small> | Cadastro de endereço de entrega</small></h2>
		<form action="{{ route('orders.address.store') }}" id="formOrderAddress" data-prefix="addr" method="POST">
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<div class="form-label-group">
						<input type="text" class="form-control" id="addr_zipcode" name="addr_zipcode" placeholder="CEP" v-model="address.zipcode"  v-mask="['#####-###']" required>
						<label for="addr_zipcode">CEP</label>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">
					<div class="form-label-group">
						<input type="text" class="form-control" id="addr_district" name="addr_district" placeholder="Bairro" v-model="address.district" required>
						<label for="addr_district">Bairro</label>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-8">
					<div class="form-label-group">
						<input type="text" class="form-control" id="addr_city" name="addr_city" placeholder="Cidade" v-model="address.city" required>
						<label for="addr_city">Cidade</label>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-label-group">
						<input type="text" class="form-control" id="addr_state" name="addr_state" placeholder="UF" v-model="address.state" v-mask="['AA']" required>
						<label for="addr_state">UF</label>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="form-label-group">
						<input type="text" class="form-control" id="addr_street" name="addr_street" placeholder="Endereço" v-model="address.street" required>
						<label for="addr_street">Endereço</label>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<div class="form-label-group">
						<input type="text" class="form-control" id="addr_number" name="addr_number" placeholder="Nº" v-model="address.number" v-mask="['######']" required>
						<label for="addr_number">Nº</label>
					</div>
				</div>
				<div class="col-sm-8">
					<div class="form-label-group">
						<input type="text" class="form-control" id="addr_complement" name="addr_complement" placeholder="Complemento" v-model="address.complement" >
						<label for="addr_complement">Complemento</label>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<button type="submit" class="btn btn-success float-right" title="Salvar">{!! ICONS_OK !!}</i></button>
						<button type="button" class="btn btn-danger float-left closeFancybox" title="Cancelar">{!! ICONS_CANCEL !!}</i></button>
					</div>
				</div>
			</div>
		</form>
	</div>

	<script type="text/javascript">
		var orderAddressApp = new Vue({
			el: '#orderAddressApp',
			data: {
				address: {!! $order->toJson() !!}
			},
			mounted: function(){
				var self = this;
				$("#formOrderAddress").cValidate({
					data: self.address,
				});
			},
			watch:{
				'address.zipcode': function(val){
					var self = this;
					if(val.length == 9){
						setAddressData(val, self.address);
					}
				}
			}
		});
	</script>
</div>