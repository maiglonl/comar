@extends('layouts.auth')

@section('content')
<div class="container align-self-center" id="changePassApp">
	<div class="row justify-content-center">
		<div class="col-10 col-sm-8 col-md-5">
			<div class="shadow">
				<form action="{{ route('password.post_expired') }}" id="formChangePass" data-prefix="change_pass">
					<div class="card border-0 ">
						<div class="card-body border rounded-2 rounded-top border-primary">
							<h4 class="pt-4 pb-4">Alteração de senha</h4>
							<div class="row">
								<div class="col-sm-12">
									<div class="form-label-group">
										<input type="text" class="form-control" id="change_pass_current_password" name="change_pass_current_password" placeholder="Senha atual" v-model="change_pass.current_password" required autofocus>
										<label for="change_pass_current">Senha atual</label>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="form-label-group">
										<input type="password" class="form-control" id="change_pass_new_password" name="change_pass_new_password" placeholder="Nova senha" v-model="change_pass.new_password" required minlength="6">
										<label for="change_pass_new">Nova senha</label>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="form-label-group">
										<input type="password" class="form-control" id="change_pass_repeat" name="change_pass_repeat" placeholder="Repita a senha" required equalto="#change_pass_new_password">
										<label for="change_pass_new">Senha</label>
									</div>
								</div>
							</div>
						</div>
					</div>
					<button type="submit" class="shadow btn btn-primary btn-block p-3" style="border-top-left-radius: unset; border-top-right-radius: unset">
						Salvar
					</button>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	new Vue({
		el: '#formChangePass',
		data: {
			change_pass: {}
		},
		mounted: function(){
			var self = this;
			$("#formChangePass").cValidate({
				data: self.change_pass,
				redirect: "{{ route('home') }}"
			});
		}
	});
</script>
@endsection
