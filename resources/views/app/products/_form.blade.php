<div class="row">
	<div class="col">
		<div class="form-label-group">
			<input type="text" class="form-control" id="name" name="name" placeholder="Nome" v-model="product.name" required>
			<label for="name">Nome</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-label-group">
			<input type="number" step="0.01" class="form-control" id="value" name="value" placeholder="Valor" v-model="product.value">
			<label for="value">Valor</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-label-group">
			<textarea rows="4" class="form-control" id="description" name="description" placeholder="Descrição" v-model="product.description" required></textarea>
			<label for="description">Descrição</label>
		</div>
	</div>
</div>