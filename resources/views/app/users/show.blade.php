@extends('layouts.app')

@section('content')
	<div id="userShowApp">
		<div class="page-title">
			<h3>
				Usuários | <small class="text-muted">Descrição do usuário</small>
				<button type="button" class="btn btn-sm btn-primary float-right" title="Alterar Cadastro" @click="openFormEditUser()">{!! ICONS_EDIT !!}</button>
			</h3>
		</div>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuários</a></li>
				<li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
			</ol>
		</nav>
		<div class="row justify-content-center">
			<div class="col-sm-3">
				<div class="card">
					<div class="card-body">
						<div class="row justify-content-center">
							<div class="col-10">
								@if(count(Illuminate\Support\Facades\Storage::files($path = env('FILES_PATH_USERS')."/".$user->id."/")) > 0)
									<img src="{{ env('FILES_PATH_USERS').'/'.$user->id.'/' }}" class="img-fluid img-thumbnail rounded-circle">
								@else
									<img v-if="user.gender == 'female'" src="{{ DEFAULT_IMAGE_USERS_FEMALE }}" class="img-fluid img-thumbnail border-2 rounded-circle" :class="[user.status == 1 ? 'border-success' : 'border-danger']">
									<img v-else src="{{ DEFAULT_IMAGE_USERS_MALE }}" class="img-fluid img-thumbnail border-2 rounded-circle" :class="[user.status == 1 ? 'border-success' : 'border-danger']">
								@endif
							</div>
						</div>
						<div class="row">
							<div class="col">
								<hr>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<h5 class="text-center">[ @{{ user.id | default }} ] @{{ user.name | name }}<br><small>[ @{{ user.role | name }} ] @{{ user.username }}<br></small></h5>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<hr>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<label class="label-plaintext label-sm" for="usr_email">E-mail:</label>
								<p class="form-control-plaintext" id="usr_email">@{{ user.email | default }}</p>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<label class="label-plaintext label-sm" for="usr_phone">Telefones:</label>
								<p class="form-control-plaintext" id="usr_phone">@{{ user.phone1 | phone }} / @{{ user.phone2 | phone }}</p>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<label class="label-plaintext label-sm" for="usr_birthdate">Data de Nascimento:</label>
								<p class="form-control-plaintext" id="usr_birthdate">@{{ user.birthdate | date }}</p>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<label class="label-plaintext label-sm" for="usr_city">Bairro - Cidade/UF:</label>
								<p class="form-control-plaintext" id="usr_city">@{{ user.district | default }} - @{{ user.city | default }}/@{{ user.state | default }}</p>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<label class="label-plaintext label-sm" for="usr_zipcode">CEP:</label>
								<p class="form-control-plaintext" id="usr_zipcode">@{{ user.zipcode | cep }}</p>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<label class="label-plaintext label-sm" for="usr_street">Endereço:</label>
								<p class="form-control-plaintext" id="usr_street">@{{ user.street | default }}, @{{ user.number | default }}</p>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<label class="label-plaintext label-sm" for="usr_complement">Complemento:</label>
								<p class="form-control-plaintext" id="usr_complement">@{{ user.complement | default }}</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-9">
				<div class="card">
					<div class="card-body">
						<div class="form-controler">
							<label>Imagens:</label>
							<div class="files-input">
								<input id="fileupload" type="file" name="files[]" data-url="server/php/" multiple>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		new Vue({
			el: '#userShowApp',
			data: {
				user: {!! $user->toJson() !!}
			},
			mounted: function(){
				var self = this;
				validaForm("#formImageUpload", function() {
					var formData = new FormData($('#formImageUpload')[0]);
					$.ajax({
						url: "{ { route('users.image.upload', [$user->id]) }}",
						type: 'POST',
						success: completeHandler = function(data) { 
							self.reloadData();
						},
						data: formData,
						cache: false,
						contentType: false,
						processData: false,
						async: true
					}, 'json');
				});
				$('#fileInput').change(function(event) {
					if($('#fileInput').val()){
						$('#formImageUpload').submit();
					}
				});
			},
			methods:{
				reloadData: function (){
					var self = this;
					$.get('{{ route('users.find', [$user->id]) }}', function(data) {
						if(data.error){
							toastr.error('Falha ao atualizar usuário!');
						}else{
							self.user = data;
						}
					});
				},
				openFormEditUser: function(){
					var self = this;
					$.fancybox.open({
						src: '{{ route('users.edit', [$user->id]) }}',
						type: 'ajax',
						opts: { 
							clickOutside: false,
							clickSlide: false,
							afterClose : function(){
								self.reloadData(); 
							},
						}
					});
				},
				openFormImage: function(){
					$('#fileInput').click();
				}
			},
			filters: filters
		});
	</script>
@endsection
