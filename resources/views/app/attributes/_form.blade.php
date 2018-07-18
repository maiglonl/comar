<div class="row">
	<div class="col-sm-6">
		<div class="form-label-group">
			<input type="text" class="form-control" id="att_name" name="att_name" placeholder="Nome" v-model="attribute.name" required>
			<label for="att_name">Nome</label>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-label-group">
			<input type="text" class="form-control" id="att_value" name="att_value" placeholder="Valor" v-model="attribute.value" required>
			<label for="att_value">Valor</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="form-group text-center">
			<button type="submit" class="btn btn-success float-right" title="Salvar">{!! ICONS_OK !!}</i></button>
			<button type="button" class="btn btn-outline-danger center" title="Excluir" @click.prevent="submitFormDeleteAttribute" v-if="attribute.id > 0">{!! ICONS_REMOVE !!}</i></button>
			<button type="button" class="btn btn-danger float-left closeFancybox" title="Cancelar">{!! ICONS_CANCEL !!}</i></button>
		</div>
	</div>
</div>