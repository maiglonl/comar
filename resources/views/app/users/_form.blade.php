@if(Auth::user() && (Auth::user()->role == USER_ROLES_ADMIN || Auth::user()->role == USER_ROLES_SELLER))
	<div class="row">
		<div class="col">
			<div class="form-group">
				<div class="btn-group btn-group-toggle border rounded d-flex" role="group" data-toggle="buttons" style="width: 100%;">
					@if(Auth::user()->role == USER_ROLES_ADMIN)
						<label class="btn btn-light w-100" :class="{active : user.role == '{{ USER_ROLES_ADMIN }}'}">
							<input type="radio" name="usr_role" table="user" field="role" autocomplete="off" v-model="user.role" value="{{ USER_ROLES_ADMIN }}" required> Admin
						</label>
					@endif
					<label class="btn btn-light w-100" :class="{active : user.role == '{{ USER_ROLES_SELLER }}'}">
						<input type="radio" name="usr_role" table="user" field="role" autocomplete="off" v-model="user.role" value="{{ USER_ROLES_SELLER }}" required> Vendedor
					</label>
					<label class="btn btn-light w-100" :class="{active : user.role == '{{ USER_ROLES_PARTNER }}'}">
						<input type="radio" name="usr_role" table="user" field="role" autocomplete="off" v-model="user.role" value="{{ USER_ROLES_PARTNER }}" required> Parceiro
					</label>
				</div>
			</div>
		</div>
	</div>
@endif
<div class="row">
	<div class="col-xs-12 col-md-6">
		<div class="form-label-group">
			<input type="text" class="form-control" id="usr_name" name="usr_name" placeholder="Nome" v-model="user.name" required>
			<label for="name">Nome</label>
		</div>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-3">
		<div class="form-label-group">
			<input type="text" class="form-control" autocomplete="off" id="usr_username" name="usr_username" placeholder="Login" v-model="user.username" required minlength="6" maxlength="20">
			<label for="usr_username">Nome de usuário</label>
		</div>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-3">
		<div class="form-label-group">
			<input type="password" class="form-control" id="usr_password" name="usr_password" placeholder="Senha" v-model="user.password" minlength="6" required autocomplete="new-password">
			<label for="usr_password">Senha</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-6 col-md-3">
		<div class="form-label-group">
			<input type="text" class="form-control" id="usr_cp" name="usr_cp" placeholder="CPF/CNPJ" v-model="user.cp" v-mask="['###.###.###-##', '##.###.###/####-##']" required>
			<label for="usr_cp">CPF/CNPJ</label>
		</div>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-3">
		<div class="form-label-group">
			<input type="date" class="form-control" id="usr_birthdate" name="usr_birthdate" placeholder="Data de Nascimento" v-model="user.birthdate" required>
			<label for="usr_birthdate">Data de Nascimento</label>
		</div>
	</div>
	<div class="col-xs-12 col-sm-6">
		<div class="form-group">
			<div class="btn-group btn-group-toggle border rounded d-flex" role="group" data-toggle="buttons" style="width: 100%; height: 49px;">
				<label class="btn btn-light w-100" :class="{active : user.gender == 'male'}" style="padding-top:12px;">
					<input type="radio" name="usr_gender" table="user" field="gender" autocomplete="off" v-model="user.gender" value="male" required>Masculino
				</label>
				<label class="btn btn-light w-100" :class="{active : user.gender == 'female'}" style="padding-top:12px;">
					<input type="radio" name="usr_gender" table="user" field="gender" autocomplete="off" v-model="user.gender" value="female" required>Feminino
				</label>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-6">
		<div class="form-label-group">
			<input type="email" class="form-control" id="usr_email" name="usr_email" placeholder="E-mail" v-model="user.email" required>
			<label for="usr_email">E-mail</label>
		</div>
	</div>
	<div class="col">
		<div class="form-label-group">
			<input type="text" class="form-control" id="usr_phone1" name="usr_phone1" placeholder="Fone 1" v-model="user.phone1" v-mask="['(##)####-####', '(##)#####-####']" required>
			<label for="usr_phone1">Fone 1</label>
		</div>
	</div>
	<div class="col">
		<div class="form-label-group">
			<input type="text" class="form-control" id="usr_phone2" name="usr_phone2" placeholder="Fone 2" v-model="user.phone2" v-mask="['(##)####-####', '(##)#####-####']">
			<label for="usr_phone2">Fone 2</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-6 col-sm-3">
		<div class="form-label-group">
			<input type="text" class="form-control" id="usr_zipcode" name="usr_zipcode" placeholder="CEP" v-model="user.zipcode"  v-mask="['#####-###']" required>
			<label for="usr_zipcode">CEP</label>
		</div>
	</div>
	<div class="col-xs-6 col-sm-3">
		<div class="form-label-group">
			<input type="text" class="form-control" id="usr_district" name="usr_district" placeholder="Bairro" v-model="user.district" required>
			<label for="usr_district">Bairro</label>
		</div>
	</div>
	<div class="col-xs-8 col-sm-4">
		<div class="form-label-group">
			<input type="text" class="form-control" id="usr_city" name="usr_city" placeholder="Cidade" v-model="user.city" required>
			<label for="usr_city">Cidade</label>
		</div>
	</div>
	<div class="col-xs-4 col-sm-2">
		<div class="form-label-group">
			<input type="text" class="form-control" id="usr_state" name="usr_state" placeholder="UF" v-model="user.state" v-mask="['AA']" required>
			<label for="usr_state">UF</label>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-6">
		<div class="form-label-group">
			<input type="text" class="form-control" id="usr_street" name="usr_street" placeholder="Endereço" v-model="user.street" required>
			<label for="usr_street">Endereço</label>
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-label-group">
			<input type="text" class="form-control" id="usr_number" name="usr_number" placeholder="Nº" v-model="user.number" v-mask="['######']" required>
			<label for="usr_number">Nº</label>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="form-label-group">
			<input type="text" class="form-control" id="usr_complement" name="usr_complement" placeholder="Complemento" v-model="user.complement" >
			<label for="usr_complement">Complemento</label>
		</div>
	</div>
</div>
@if(!Auth::user())
	<div class="row" v-if="user.role != '{{ USER_ROLES_ADMIN }}'">
		<div class="col">
			<div class="form-label-group">
				<input type="text" class="form-control" id="usr_parent_name" name="usr_parent_name" placeholder="Responsável">
				<label for="usr_parent_id">Responsável</label>
			</div>
			<input type="hidden" id="usr_parent_id" v-model="user.parent_id">
		</div>
	</div>
@endif
<div class="row">
	<div class="col-sm-12">
		<div class="form-group">
			<button type="submit" class="btn btn-success float-right" title="Salvar">{!! ICONS_OK !!}</i></button>
			<button type="button" class="btn btn-danger float-left closeFancybox" title="Cancelar">{!! ICONS_CANCEL !!}</i></button>
		</div>
	</div>
</div>