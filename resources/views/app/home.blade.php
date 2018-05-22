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

                    <select class="form-control selectpicker">
						<option>Mustard</option>
						<option>Ketchup</option>
						<option>Relish</option>
					</select>
					<input type="text" placeholder="teste input" class="form-control">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
