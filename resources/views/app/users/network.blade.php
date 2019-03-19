@extends('layouts.app')

@section('content')
	<script type="text/javascript" src="{{ mix('js/charts.js') }}"></script>
	<link href="{{ mix('css/charts.css') }}" rel="stylesheet">

	<div class="container-fluid" id="appOrdersList">
		<div class="row">
			<div class="col">
				<div class="page-title">
					<h3>
						Minha Rede | <small>Lista de parceiros cadastrados</small>
						<button type="button" class="btn btn-primary float-right" title="Adicionar novo Usuário" onclick="openFormUser()">{!! ICONS_ADD !!}</button>
					</h3>
				</div>
				<div class="card p-4" v-cloak>
					<div v-if="user.parent">
						<div class="row">
							<div class="col">
								<div class="list-group">
									<div @click="goToNetwork(user.parent.id, user.parent.position)" class="list-group-item  list-item-lb list-item-bg pointer">
										<div class="row">
											<div class="col">
												<h5 class="m-0 w-100">
													@{{ user.parent.position <= 0 ? '' : user.parent.position+" - " }}@{{ user.parent.name | name }}
													<span class="float-right" v-html="$options.filters.currency_sup(user.parent.sales[Object.keys(user.parent.sales)[0]], true)"></span>
												</h5>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<hr>
							</div>
						</div>
					</div>
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
							<h5>@{{ user.position <= 0 ? '' : user.position+" - " }}@{{ user.name | name }} <small>[ @{{ user.role | role }} ]</small></h5>
							<div class="row">
								<div class="col">
									<label class="label-plaintext label-sm" for="usr_id">Código de usuário:</label>
									<p class="form-control-plaintext" id="usr_id">@{{ user.id | default }}</p>
								</div>
							</div><div class="row">
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
									<label class="label-plaintext label-sm" for="usr_city">Bairro - Cidade/UF:</label>
									<p class="form-control-plaintext" id="usr_city">@{{ user.district | default }} - @{{ user.city | default }}/@{{ user.state | default }}</p>
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
							<div id="userChart"></div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<hr>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<div class="list-group">
								<div @click="goToNetwork(children.id, children.position)" class="list-group-item  list-item-lb list-item-bg pointer" v-for="children in user.childrens" >
									<div class="row">
										<div class="col">
											<h5 class="m-0 w-100">
												@{{ children.position }} - @{{ children.name | name }}
												<span class="float-right" v-html="$options.filters.currency_sup(children.sales[Object.keys(children.sales)[0]], true)"></span>
											</h5>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			
		</div>
	</div>
	<script type="text/javascript">
		new Vue({
			el: '#appOrdersList',
			data: {
				user: {!! $user->toJson() !!}
			},
			mounted: function(){
				let self = this;
				let categories = [];
				let serie = [];
				$.get('{{route('users.position', [''])}}/'+self.user.id, null, function(data) {
					self.user.position = data;
				});
				if(self.user.parent){
					$.get('{{route('users.position', [''])}}/'+self.user.parent_id, null, function(data) {
						self.user.parent.position = data;
					});
				}
				$.each(self.user.childrens, function(index, val) {
					$.get('{{route('users.position', [''])}}/'+val.id, null, function(data) {
						self.user.childrens[index].position = data;
					});
				});
				$.each(self.user.sales, function(index, val) {
					let date = moment(index, 'YYYYMM');
					categories.push(date.format('MMM'));
					serie.push(val);
				});
				Highcharts.chart('userChart', {
					chart: {
						type: 'areaspline',
						width: 320,
						height: 180,
						margin: [0, 0, 20, 0],
					},
					plotOptions: {
						areaspline: { fillOpacity: 0.5 }
					},
					title: '',
					legend: '',
					credits: { enabled: false },
					yAxis: { 
						endOnTick: false,
						startOnTick: false,
						labels: {
							enabled: false
						},
						title: {
							text: null
						},
						tickPositions: [0]
					},
					xAxis: {
						categories: categories
					},
					series: [{
						data: serie,
						name: 'Total'
					}]
				});
			},
			methods:{
				goToNetwork: function(id, position){
					if(position >= 0){
						location.href = '{{ route('users.network', ['']) }}/'+id;
					}
				}
			},
			filters: filters
		});
		function openFormUser(){
			$.fancybox.open({
				src: '{{ route('users.create') }}',
				type: 'ajax',
				opts: { 
					clickOutside: false,
					clickSlide: false,
					afterClose : function(){
						$('#table-users').DataTable().ajax.reload(null, false); 
					},
				}
			});
		}
	</script>
@endsection
