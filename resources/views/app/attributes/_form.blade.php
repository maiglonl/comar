<div class="row">
	<div class="col-sm-6">
		<div class="form-label-group">
			<input type="text" class="form-control" id="att_name" name="att_name" placeholder="Nome" v-model="attribute.name" required>
			<label for="att_name">Nome</label>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-label-group">
			<input type="text" class="form-control" id="att_value" name="att_value" placeholder="Valor" v-model="attribute.value">
			<label for="att_value">Valor</label>
		</div>
	</div>
</div>