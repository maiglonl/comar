@extends('layouts.order')

@section('body_top')
	<div id="orderDeliveryApp" class="container-fluid bg-gray-100">
		<div class="row justify-content-md-center">
			<div class="col-xs-11 col-sm-7">
				<div class="py-5 px-4 mt-3">
					<h4 class="pb-4">Opções de envio para</h4>
					<div class="col rounded bg-gray-50">
						<div class="row py-2 align-items-center">
							<div class="col-2 px-4 text-center">
								<div class="col text-primary"><i class="fas fa-map-marker-alt fa-2x rounded px-2 py-1 border border-primary"></i></div>
							</div>
							<div class="col">DESCRIÇÃO</div>
							<div class="col-sm-4 col-xs-12 text-right">Alterar</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-11 col-sm-3 bg-gray-50">
				<div class="py-5 px-4 mt-3">
					<p class="mb-2"><b>Resumo da Compra</b></p>
					<hr class="mt-0 mb-4">
					<p class="mb-2">Produtos(X) <span class="float-right">R$123,12</span></p>
					<p>Envio <span class="float-right">R$123,12</span></p>
					<hr class="my-4">
					<p>Total <span class="float-right">R$123,12</span></p>
				</div>
			</div>
		</div>
		
	</div>
	
@endsection
