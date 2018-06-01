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
	<div class="col-xs-12 col-sm-6">
		<div class="form-label-group">
			<input type="text" class="form-control" id="usr_name" name="usr_name" placeholder="Nome" v-model="user.name" required>
			<label for="name">Nome</label>
		</div>
	</div>
	<div class="col-xs-12 col-sm-6">
		<div class="form-label-group">
			<input type="email" class="form-control" id="usr_email" name="usr_email" placeholder="E-mail" v-model="user.email" required>
			<label for="usr_email">E-mail</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<div class="btn-group btn-group-toggle border rounded d-flex" role="group" data-toggle="buttons" style="width: 100%;">
				<label class="btn btn-light w-100" :class="{active : user.gender == 'male'}">
					<input type="radio" name="usr_gender" id="usr_gender_male" autocomplete="off" v-model="user.gender" value="male" required> Masculino
				</label>
				<label class="btn btn-light w-100" :class="{active : user.gender == 'female'}">
					<input type="radio" name="usr_gender" id="usr_gender_female" autocomplete="off" v-model="user.gender" value="female" required> Feminino
				</label>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-label-group">
			<input type="text" class="form-control" id="usr_parent_id" name="usr_parent_id" placeholder="Responsável" v-model="user.parent_id" required>
			<label for="usr_parent_id">Responsável</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-label-group">
			<input type="date" class="form-control" id="usr_birthdate" name="usr_birthdate" placeholder="Data de Nascimento" v-model="user.birthdate" required>
			<label for="usr_birthdate">Data de Nascimento</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-label-group">
			<input type="text" class="form-control" id="usr_phone1" name="usr_phone1" placeholder="Fone 1" v-model="user.phone1" required>
			<label for="usr_phone1">Fone 1</label>
		</div>
	</div>
	<div class="col">
		<div class="form-label-group">
			<input type="text" class="form-control" id="usr_phone2" name="usr_phone2" placeholder="Fone 2" v-model="user.phone2" required>
			<label for="usr_phone2">Fone 2</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-3">
		<div class="form-label-group">
			<input type="text" class="form-control" id="usr_zipcode" name="usr_zipcode" placeholder="CEP" v-model="user.zipcode" required>
			<label for="usr_zipcode">CEP</label>
		</div>
	</div>
	<div class="col-3">
		<div class="form-label-group">
			<input type="text" class="form-control" id="usr_district" name="usr_district" placeholder="Bairro" v-model="user.district" required>
			<label for="usr_district">Bairro</label>
		</div>
	</div>
	<div class="col-4">
		<div class="form-label-group">
			<input type="text" class="form-control" id="usr_city" name="usr_city" placeholder="Cidade" v-model="user.city" required>
			<label for="usr_city">Cidade</label>
		</div>
	</div>
	<div class="col-2">
		<div class="form-label-group">
			<input type="text" class="form-control" id="usr_state" name="usr_state" placeholder="UF" v-model="user.state" required>
			<label for="usr_state">UF</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-10">
		<div class="form-label-group">
			<input type="text" class="form-control" id="usr_street" name="usr_street" placeholder="Endereço" v-model="user.street" required>
			<label for="usr_street">Endereço</label>
		</div>
	</div>
	<div class="col-2">
		<div class="form-label-group">
			<input type="text" class="form-control" id="usr_number" name="usr_number" placeholder="Nº" v-model="user.number" required>
			<label for="usr_number">Nº</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-label-group">
			<input type="text" class="form-control" id="usr_complement" name="usr_complement" placeholder="Complemento" v-model="user.complement" required>
			<label for="usr_complement">Complemento</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-label-group">
			<input type="text" class="form-control" id="usr_username" name="usr_username" placeholder="Login" v-model="user.username" required>
			<label for="usr_username">Login</label>
		</div>
	</div>
	<div class="col">
		<div class="form-label-group">
			<input type="password" class="form-control" id="usr_password" name="usr_password" placeholder="Senha" v-model="user.password" required>
			<label for="usr_password">Senha</label>
		</div>
	</div>
</div>
