<div class="row">
	<div class="col">
		<div class="form-label-group">
			<input type="text" class="form-control" id="name" name="name" placeholder="Nome" v-model="user.name" required>
			<label for="name">Nome</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-label-group">
			<input type="email" class="form-control" id="email" name="email" placeholder="E-mail" v-model="user.email" required>
			<label for="email">E-mail</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-label-group">
			<textarea rows="4" class="form-control" id="description" name="description" placeholder="Descrição" v-model="user.description" required></textarea>
			<label for="description">Descrição</label>
		</div>
	</div>
</div>