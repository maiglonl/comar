<div class="card col-xs-11 col-sm-3 rounded-0">
	<div class="py-5 px-4 mt-3">
		<p class="mb-2"><b>Resumo da Compra</b></p>
		<hr class="mt-0 mb-4">
		<p class="mb-2">Produtos(@{{ order.items.length }}) <span class="float-right" v-html="$options.filters.currency_sup(order.total_items)"></span></p>
		<p v-if="order.total_delivery > 0">Envio <span class="float-right" v-html="$options.filters.currency_sup(order.total_delivery, true)"></span></p>
		<p v-else>Envio <span class="float-right text-success">Grátis</span></p>
		{{-- <p>Desconto <span class="float-right"></span></p> --}}
		<hr class="my-4">
		<p class="h5">Você pagará 
			<span class="float-right">
				<span v-for="(val, key) in payment_installments_groups.sem" class="float-right">
					@{{ key }} <span v-html="$options.filters.currency_sup(val)"></span><br>
					<small class="text-success float-right">sem juros</small>
				</span>
				<span v-for="(val, key) in payment_installments_groups.com" class="float-right">@{{ key }} <span v-html="$options.filters.currency_sup(val)"></span></span>
			</span>
		</p>
		<hr class="my-4">
		<p>Total <span class="float-right" v-html="$options.filters.currency_sup(order.total)"></span></p>
	</div>
	<div class="px-sm-4 text-right">
		<button class="btn btn-block btn-primary" @click="confirmBuy">Confirmar compra</button>
	</div>
</div>