<div class="row">
	<div class="col-sm-12">
		<div class="form-label-group">
			<input type="text" class="form-control" id="cat_name" name="cat_name" placeholder="Nome" v-model="category.name" required>
			<label for="cat_name">Nome</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="form-group text-center">
			<button type="submit" class="btn btn-success float-right" title="Salvar">{!! ICONS_OK !!}</i></button>
			<button type="button" class="btn btn-danger float-left closeFancybox" title="Cancelar">{!! ICONS_CANCEL !!}</i></button>
		</div>
	</div>
</div>