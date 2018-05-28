<div class="row">
	<div class="col-8">
		<div class="form-label-group">
			<input type="text" class="form-control" id="prod_name" name="prod_name" placeholder="Nome" v-model="product.name" required>
			<label for="prod_name">Nome</label>
		</div>
	</div>
	<div class="col-4">
		<div class="form-label-group">
			<select class="form-control float-label-select" id="prod_category" name="prod_category" v-model="product.category_id">
				<option value selected></option>
				<option v-for="category in categories" :value="category.id">@{{ category.name }}</option>
			</select>
			<label for="prod_category">Categoria</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="input-group appends">
			<div class="form-label-group form-control">
				<input type="number" step="1" class="form-control" id="prod_height" name="prod_height" placeholder="Altura" v-model="product.height">
				<label for="prod_height">Altura</label>
			</div>
			<div class="input-group-append">
				<div class="input-group-text">Cm</div>
			</div>			
		</div>
	</div>
	<div class="col">
		<div class="input-group appends">
			<div class="form-label-group form-control">
				<input type="number" step="1" class="form-control" id="prod_width" name="prod_width" placeholder="Largura" v-model="product.width">
				<label for="prod_width">Largura</label>
			</div>
			<div class="input-group-append">
				<div class="input-group-text">Cm</div>
			</div>			
		</div>
	</div>
	<div class="col">
		<div class="input-group appends">
			<div class="form-label-group form-control">
				<input type="number" step="1" class="form-control" id="prod_length" name="prod_length" placeholder="Comprimento" v-model="product.length">
				<label for="prod_length">Comprimento</label>
			</div>
			<div class="input-group-append">
				<div class="input-group-text">Cm</div>
			</div>			
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="input-group appends">
			<div class="form-label-group form-control">
				<input type="number" step="0.01" class="form-control" id="prod_weight" name="prod_weight" placeholder="Peso" v-model="product.weight">
				<label for="prod_weight">Peso</label>
			</div>
			<div class="input-group-append">
				<div class="input-group-text">Kg</div>
			</div>
		</div>
	</div>
	<div class="col">
		<div class="input-group appends">
			<div class="form-label-group form-control">
				<input type="number" step="1" class="form-control" id="prod_diameter" name="prod_diameter" placeholder="Diâmetro" v-model="product.diameter">
				<label for="prod_diameter">Diâmetro</label>
			</div>
			<div class="input-group-append">
				<div class="input-group-text">Cm</div>
			</div>			
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="input-group appends">
			<div class="input-group-prepend">
				<div class="input-group-text">R$</div>
			</div>
			<div class="form-label-group form-control">
			<input type="number" step="0.01" class="form-control" id="prod_value_seller" name="prod_value_seller" placeholder="Valor - Vendedor" v-model="product.value_seller">
			<label for="prod_value_seller">Valor - Vendedor</label>
			</div>
			<div class="input-group-append">
				<div class="input-group-text">,00</div>
			</div>
		</div>
	</div>
	<div class="col">
		<div class="input-group appends">
			<div class="input-group-prepend">
				<div class="input-group-text">R$</div>
			</div>
			<div class="form-label-group form-control">
				<input type="number" step="0.01" class="form-control" id="prod_value_partner" name="prod_value_partner" placeholder="Valor - Parceiro" v-model="product.value_partner">
				<label for="prod_value_partner">Valor - Parceiro</label>
			</div>
			<div class="input-group-append">
				<div class="input-group-text">,00</div>
			</div>
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