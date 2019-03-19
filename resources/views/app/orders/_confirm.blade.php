<div class="card col-xs-11 col-sm-3 rounded-0">
	<div class="py-5 px-4 mt-3">
		<p class="mb-2"><b>Resumo da Compra</b></p>
		<hr class="mt-0 mb-4">
		<p class="mb-2">Produtos(@{{ order.items.length }}) <span class="float-right" v-html="$options.filters.currency_sup(order.total_items)"></span></p>
		<p v-if="order.total_delivery > 0">Envio <span class="float-right" v-html="$options.filters.currency_sup(order.total_delivery, true)"></span></p>
		<p v-else>Envio <span class="float-right text-success">Grátis</span></p>
		{{-- <p>Desconto <span class="float-right"></span></p> --}}
		<hr class="my-4">
		<p class="mb-2"><b>Você pagará</b></p>
		<span v-if="order.payment_method == '{{ PAYMENT_METHOD_CREDIT_CARD }}'">
			<p class="h5 mb-2" v-for="group in order.payment_groups" v-if="group.installments.length >= group.selected -1">
				@{{ group.selected }}x <small class="text-success" v-if="group.installments[group.selected-1].interestFree">sem juros</small>
				<span class="float-right" v-html="$options.filters.currency_sup(group.installments[group.selected-1].installmentAmount)"></span>
			</p>
		</span>
		<span v-else>
			<p class="h5 mb-2">1x <span class="float-right" v-html="$options.filters.currency_sup(order.total)"></span></p>
		</span>
		</p>
		<hr class="my-4">
		<p>Total <span class="float-right" v-html="$options.filters.currency_sup(total)"></span></p>
	</div>
	<div class="px-sm-4 text-right">
		<button class="btn btn-block btn-primary" @click="confirmBuy" id="btn-confirmBuy">Confirmar compra</button>
	</div>
</div>