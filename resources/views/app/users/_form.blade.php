<div class="row">
	<div class="col">
		<div class="form-group">
			<div class="btn-group btn-group-toggle border rounded d-flex" role="group" data-toggle="buttons" style="width: 100%;">
				<label class="btn btn-light w-100" :class="{active : user.role == 'admin'}">
					<input type="radio" name="usr_role" id="usr_role_admin" autocomplete="off" v-model="user.role" value="admin" required> Admin
				</label>
				<label class="btn btn-light w-100" :class="{active : user.role == 'seller'}">
					<input type="radio" name="usr_role" id="usr_role_seller" autocomplete="off" v-model="user.role" value="seller" required> Vendedor
				</label>
				<label class="btn btn-light w-100" :class="{active : user.role == 'partner'}">
					<input type="radio" name="usr_role" id="usr_role_partner" autocomplete="off" v-model="user.role" value="partner" required> Parceiro
				</label>
			</div>
		</div>
	</div>
</div>
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