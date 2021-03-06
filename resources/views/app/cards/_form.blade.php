<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="form-label-group">
			<input type="text" class="form-control" id="cc_number" name="cc_number" placeholder="Número do cartão" v-model="card.number"  v-mask="['################']" required minlength="16">
			<label for="cc_number">Número do cartão</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="form-label-group">
			<input type="text" class="form-control" id="cc_name" name="cc_name" placeholder="Nome e sobrenome" v-model="card.name" required>
			<label for="cc_name">Nome e sobrenome</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-6">
		<div class="form-label-group">
			<input type="text" class="form-control" id="cc_date_due" name="cc_date_due" placeholder="Vencimento" v-model="card.date_due" v-mask="['##/##']" required>
			<label for="cc_date_due">Vencimento</label>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-label-group">
			<input type="text" class="form-control" id="cc_cvv" name="cc_cvv" placeholder="Cvv" v-model="card.cvv" v-mask="['###', '####']" required minlength="3">
			<label for="cc_cvv">Cvv</label>
		</div>
	</div>
</div>
@if(!isset($formOnly))
	<div class="row">
		<div class="col-sm-12">
			<div class="form-group">
				<button type="submit" class="btn btn-success float-right" title="Salvar">{!! ICONS_OK !!}</i></button>
				<button type="button" class="btn btn-danger float-left closeFancybox" title="Cancelar">{!! ICONS_CANCEL !!}</i></button>
			</div>
		</div>
	</div>
@endif