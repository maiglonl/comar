<div class="row">
	<div class="col">
		<div class="form-label-group">
			<input type="text" class="form-control" id="prod_name" name="prod_name" placeholder="Nome" v-model="product.name" required>
			<label for="prod_name">Nome</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-label-group">
			<input type="number" step="0.01" class="form-control" id="prod_value" name="prod_value" placeholder="Valor" v-model="product.value">
			<label for="prod_value">Valor</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-label-group">
			<textarea rows="4" class="form-control" id="prod_description" name="prod_description" placeholder="Descrição" v-model="product.description" required></textarea>
			<label for="prod_description">Descrição</label>
		</div>
	</div>
</div>