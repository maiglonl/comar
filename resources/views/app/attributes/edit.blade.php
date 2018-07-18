<div>
	<div id="attributeEditApp">
		<h2 class="page-title">Atributo <small> | Edição de Atributo</small></h2>
		<form action="{{ route('attributes.update', [$attribute->id]) }}" id="formEditAttribute" method="PUT" data-prefix="att">
			@include('app.attributes._form')
		</form>
	</div>

	<script type="text/javascript">
		new Vue({
			el: '#attributeEditApp',
			data: {
				attribute: {!! $attribute->toJson() !!}
			},
			mounted: function(){
				var self = this;
				$("#formEditAttribute").cValidate({
					data: self.attribute
				});
			},
			methods:{
				submitFormDeleteAttribute: function (){ 
					var token = {'_token': "{{ csrf_token() }}"};
					$.delete('{{ route('attributes.destroy', [$attribute->id]) }}', token, function(data) {
						toastr.success('Atributo removido com sucesso');
						parent.jQuery.fancybox.close();
					});
				}
			}
		});
	</script>
</div>