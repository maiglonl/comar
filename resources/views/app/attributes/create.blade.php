<div>
	<div id="attributeCreateApp">
		<h2 class="page-title">Atributo <small> | Cadastro Atributo</small></h2>
		<form action="{{ route('attributes.store') }}" id="formCreateAttribute" data-prefix="att">
			@include('app.attributes._form')
		</form>
	</div>

	<script type="text/javascript">
		new Vue({
			el: '#attributeCreateApp',
			data: {
				attribute: {
					product_id: '{{ $product_id }}'
				}
			},
			mounted: function(){
				var self = this;
				$("#formCreateAttribute").cValidate({
					data: self.attribute
				});
			}
		});
	</script>
</div>