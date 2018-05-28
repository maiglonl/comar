@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">Dashboard</div>
				<div class="card-body">
					@if (session('status'))
						<div class="alert alert-success">
							{{ session('status') }}
						</div>
					@endif

					<div class="p-3 mb-2 bg-gradient-primary text-white">.bg-gradient-primary</div>
					<div class="p-3 mb-2 bg-gradient-secondary text-white">.bg-gradient-secondary</div>
					<div class="p-3 mb-2 bg-gradient-success text-white">.bg-gradient-success</div>
					<div class="p-3 mb-2 bg-gradient-danger text-white">.bg-gradient-danger</div>
					<div class="p-3 mb-2 bg-gradient-warning text-dark">.bg-gradient-warning</div>
					<div class="p-3 mb-2 bg-gradient-info text-white">.bg-gradient-info</div>
					<div class="p-3 mb-2 bg-gradient-light text-dark">.bg-gradient-light</div>
					<div class="p-3 mb-2 bg-gradient-dark text-white">.bg-gradient-dark</div>


					<form class="">
						<select class="form-control selectpicker">
							<option>Mustard</option>
							<option>Ketchup</option>
							<option>Relish</option>
						</select>

						<input type="text" placeholder="teste input" class="form-control">

						<label class="sr-only" for="inlineFormInputName2">Name</label>
						<input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="Jane Doe">

						<label class="sr-only" for="inlineFormInputGroupUsername2">Username</label>
						<div class="input-group mb-2 mr-sm-2">
							<div class="input-group-prepend">
								<div class="input-group-text">R$</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGroupUsername2" placeholder="Username">
							<div class="input-group-append">
								<div class="input-group-text">,00</div>
							</div>
						</div>
						<button type="submit" class="btn btn-primary mb-2">Submit</button>
					</form>
					<div class="row">
						<div class="col">
							<div class="form-label-group">
								<input type="text" class="form-control" id="prod_name3" name="prod_name3" placeholder="Nome" v-model="product.name" required>
								<label for="prod_name3">Nome</label>
							</div>
						</div>
						<div class="col">
							<div class="input-group">
								<div class="input-group-prepend">
									<div class="input-group-text">R$</div>
								</div>
								<div class="form-label-group form-control">
									<input type="text" id="prod_name" name="prod_name" placeholder="Nome" v-model="product.name" required>
									<label for="prod_name">Nome</label>
								</div>
								<div class="input-group-append">
									<div class="input-group-text">,00</div>
								</div>
							</div>
						</div>
					</div>
<div class="row">
	<div class="col">
		<div class="form-label-group">
			<select class="form-control float-label-select" id="prod_category" name="prod_category" v-model="product.category_id">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
			</select>
			<label for="prod_category">Categoria</label>
		</div>
	</div>
</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
