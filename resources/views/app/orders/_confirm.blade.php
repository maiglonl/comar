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
			<span class="float-right" v-for="payment_installments_groups"></span>
		</p>
		<hr class="my-4">
		<p>Total <span class="float-right" v-html="$options.filters.currency_sup(order.total)"></span></p>
	</div>
</div>