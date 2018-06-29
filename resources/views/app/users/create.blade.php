<div>
	<div id="userCreateApp">
		<h2 class="page-title">Usuário <small> | Cadastro de novo Usuário</small></h2>
		<form action="{{ route('users.store') }}" id="formCreateUser" data-prefix="usr">
			@include('app.users._form')
		</form>
	</div>

	<script type="text/javascript">
		new Vue({
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
						setAddressData(val, self.user);
					}
				}
			}
		});
		async function setAddressData(cep, data) {
			try {
				const response = await axios.get("http://www.viacep.com.br/ws/"+ cep.replace(/\W/g, '') +"/json").then(function (response) {
					data.street = dados.logradouro;
					data.district = dados.bairro;
					data.city = dados.localidade;
					data.state = dados.uf;
				}).catch(function(response){});
			} catch (error) {}
		}
	</script>
</div>