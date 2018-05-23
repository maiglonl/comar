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
			<input type="number" step="0.01" class="form-control" id="prod_value_seller" name="prod_value_seller" placeholder="Valor - Vendedor" v-model="product.value_seller">
			<label for="prod_value_seller">Valor - Vendedor</label>
		</div>
	</div>
	<div class="col">
		<div class="form-label-group">
			<input type="number" step="0.01" class="form-control" id="prod_value_partner" name="prod_value_partner" placeholder="Valor - Parceiro" v-model="product.value_partner">
			<label for="prod_value_partner">Valor - Parceiro</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-label-group">
			<select class="form-control" id="prod_category" name="prod_category" v-model="product.category">
				<option v-for="category in categories" value="category.ir">@{{ category.name }}</option>
			</select>
			<label for="prod_category">Categoria</label>
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