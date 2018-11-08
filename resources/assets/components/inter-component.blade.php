@extends('layouts.app')

@section('content')
	<style type="text/css">
		#networkCollapse {
			width: 40px;
			height: 40px;
			border:none;
		}
		#networkCollapse span {
			width: 12px;
			height: 2px;
			margin: 0 auto;
			display: block;
			background: #555;
			transition: all 0.8s cubic-bezier(0.810, -0.330, 0.345, 1.375);
		}
		#networkCollapse span:first-of-type {
			transform: translate(-4px, 1px) rotate(45deg);
		}
		#networkCollapse span:last-of-type {
			transform: translate(4px, -1px) rotate(-45deg);
		}
		#networkCollapse.active span:first-of-type {
			transform: translate(-4px, 4px) rotate(-45deg);
		}
		#networkCollapse.active span:last-of-type {
			transform: translate(4px, -3px) rotate(45deg);
		}
		#networkCollapse.active span {
			transform: none;
			opacity: 1;
			margin: 5px auto;
		}
	</style>

	<dov class="container-fluid" id="appOrdersList">
		<div class="row">
			<div class="col">
				<div class="page-title">
					<h3>
						Minha Rede | <small>Lista de parceiros cadastrados</small>
						<button type="button" class="btn btn-primary float-right" title="Adicionar novo Parceiro">
							<i class="fas fa-plus"></i>
						</button>
					</h3>
				</div>
				<div class="card p-4">
					<div class="row">
						<div class="p-3" style="width: 12rem!important;">
							@if(count(Illuminate\Support\Facades\Storage::files($path = env('FILES_PATH_USERS')."/".$user->id."/")) > 0)
								<img src="{{ env('FILES_PATH_USERS').'/'.$user->id.'/' }}" class="img-fluid img-thumbnail rounded-circle">
							@else
								<img v-if="user.gender == 'female'" src="{{ DEFAULT_IMAGE_USERS_FEMALE }}" class="img-fluid img-thumbnail border-2 rounded-circle" :class="[user.status == 1 ? 'border-success' : 'border-danger']">
								<img v-else src="{{ DEFAULT_IMAGE_USERS_MALE }}" class="img-fluid img-thumbnail border-2 rounded-circle" :class="[user.status == 1 ? 'border-success' : 'border-danger']">
							@endif
						</div>
						<div class="col-6">
							<h5>@{{ user.name | name }} <small>[ @{{ user.id | default }} |  @{{ user.role | name }} ]</small></h5>
							<div class="row">
								<div class="col">
									<label class="label-plaintext label-sm" for="usr_email">E-mail:</label>
									<p class="form-control-plaintext" id="usr_email">@{{ user.email | default }}</p>
								</div>
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
								<div class="col">
									<label class="label-plaintext label-sm" for="usr_zipcode">CEP:</label>
									<p class="form-control-plaintext" id="usr_zipcode">@{{ user.zipcode | cep }}</p>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<label class="label-plaintext label-sm" for="usr_city">Bairro - Cidade/UF:</label>
									<p class="form-control-plaintext" id="usr_city">@{{ user.district | default }} - @{{ user.city | default }}/@{{ user.state | default }}</p>
								</div>
								<div class="col">
									<label class="label-plaintext label-sm" for="usr_complement">Complemento:</label>
									<p class="form-control-plaintext" id="usr_complement">@{{ user.complement | default }}</p>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<label class="label-plaintext label-sm" for="usr_street">Endereço:</label>
									<p class="form-control-plaintext" id="usr_street">@{{ user.street | default }}, @{{ user.number | default }}</p>
								</div>
							</div>
						</div>
						<div class="col">
							Compras do mês:
							<h1 class="text-success"><strong>R$ 1.500,00</strong></h1>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<hr>
						</div>
					</div>
					<div class="card p-4 m-4" v-for="user in users">
						<network-menu
							:depth="0"
							:obj=user
						></network-menu>
					</div>
				</div>
			</div>
		</div>
	</dov>


	<script type="text/x-template" id="network-menu">
		<div class="network-menu">
			<div class="name-wrapper">
				<div :style="indent" :class="nameClasses" class="row align-items-center">	
					<button :class="{'invisible': obj.childrens.length == 0, 'active': showChildren }" type="button" id="networkCollapse" class="btn-link pointer" @click="toggleChildren">
						<span></span> <span></span>
					</button>
					<b>@{{ obj.name }}</b>
				</div>
			</div>
			<network-menu 
				v-if="showChildren"
				v-for="(children, key) in obj.childrens"  
				:obj="children"
				:depth="depth + 1"
			>
			</network-menu>
		</div>
	</script>
	<script type="text/javascript">
		Vue.component('network-menu', { 
			template: '#network-menu',
			props: [ 'obj', 'depth' ],
			data() {
				 return {
					showChildren: false,
				 }
			},
			computed: {
				nameClasses() {
					return { 
						'has-children': this.obj.childrens,
						'border-top': this.depth > 0,
					}
				},
				iconClasses() {
					return { 
						'active': this.showChildren
					}
				},
				indent() {
					return { 'margin-left': `${this.depth * 30}px` }
				}
			},
			methods: {
				toggleChildren() {
					 this.showChildren = !this.showChildren;
				}
			}
		});

		new Vue({
			el: '#appOrdersList',
			data: {
				user: {!! $user->toJson() !!},
				users: {!! $user->childrens->toJson() !!}
			},
			mounted: function(){
				console.log(this.users);
			},
			methods:{
			},
			filters: filters
		});
	</script>
@endsection
