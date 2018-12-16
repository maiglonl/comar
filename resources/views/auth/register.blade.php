@extends('layouts.auth')

@section('content')
<div class="container align-self-center">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    @include('app.users.create')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
