<div>
	<div id="userCreateApp">
		<h2 class="page-title">Usuário <small> | Cadastro de novo Usuário</small></h2>
		<form action="{{ route('users.store') }}" id="formCreateUser" data-prefix="usr">
			@include('app.users._form')
		</form>
	</div>

	<script type="text/javascript">
		var userCreateApp = new Vue({
			el: '#userCreateApp',
			data: {
				user: {
					role: 'partner',
				}
			},
			mounted: function(){
				var self = this;
				$("label.btn").click(function(event) {
					var child = $(this).children().first();
					self[child.attr('table')][child.attr('field')] = child.val();
				});
				$("#formCreateUser").cValidate({
					data: self.user,
					success: 'Usuário criado com sucesso!',
					error: 'Falha ao criar usuário!',
					redirect: {!! !Auth::user() ? "'".route('login')."'" : 'false' !!}
				});
			},
			methods:{
				submitFormCreateUser: function (){ 
					var self = this;
					$("#formCreateUser").submit();
				}
			},
			watch:{
				'user.zipcode': function(val){
					var self = this;
					if(val.length == 9){
						console.log(0);
						setAddressData(val, self.user);
						console.log(2);
					}
				}
			}
		});
		async function setAddressData(cep, data) {
			try {
				const response = await axios.get("https://viacep.com.br/ws/"+ cep.replace(/\W/g, '') +"/json").then(function (response) {
					console.log(response);
					data.street = response.logradouro;
					data.district = response.bairro;
					data.city = response.localidade;
					data.state = response.uf;
				}).catch(function(response){
					console.log('catch');
					console.log(response);
				});
			} catch (error) {
				console.log('error');
				console.log(error);
			}
		}
	</script>
</div>