@extends('layouts.auth')

@section('content')
<div class="container align-self-center" id="loginApp">
	<div class="row justify-content-center">
		<div class="col-10 col-sm-8 col-md-5">
			<div class="shadow">
				<form action="{{ route('login') }}" id="formLogin" data-prefix="login">
					<div class="card border-0 ">
						<div class="card-body border rounded-2 rounded-top border-primary">
							<div class="row">
								<div class="col-sm-12">
									<div class="form-label-group">
										<input type="text" class="form-control" id="login_email" name="login_email" placeholder="Nome de usuário" v-model="login.email" required autofocus>
										<label for="login_email">Nome de usuário</label>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="form-label-group">
										<input type="password" class="form-control" id="login_password" name="login_password" placeholder="Senha" v-model="login.password" required>
										<label for="login_password">Senha</label>
									</div>
								</div>
							</div>
							<div class="form-group row mb-0">
								<div class="col text-center">
									<a class="btn btn-link" href="{{ route('password.request') }}">
										Esqueceu a senha?
									</a>
								</div>
							</div>
						</div>
					</div>
					<button type="submit" class="shadow btn btn-primary btn-block p-3" style="border-top-left-radius: unset; border-top-right-radius: unset">
						Login
					</button>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	new Vue({
		el: '#formLogin',
		data: {
			login: {}
		},
		mounted: function(){
			var self = this;
			$("#formLogin").cValidate({
				data: self.login,
				redirect: "{{ route('home') }}"
			});
		}
	});
</script>
@endsection
