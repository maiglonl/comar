<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="name">Nome:</label>
			<input type="text" class="form-control" id="name" name="name" v-model="product.name" required>
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="value">Valor:</label>
			<input type="number" step="0.01" class="form-control" id="value" name="value" v-model="product.value">
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="description">Descrição:</label>
			<textarea rows="4" class="form-control" id="description" name="description" v-model="product.description" required></textarea>
		</div>
	</div>
</div>