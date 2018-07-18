<div>
	<div id="categoryCreateApp">
		<h2 class="page-title">Categoria <small> | Cadastro Categoria</small></h2>
		<form action="{{ route('categories.store') }}" id="formCreateCategory" data-prefix="cat">
			@include('app.categories._form')
		</form>
	</div>

	<script type="text/javascript">
		new Vue({
			el: '#categoryCreateApp',
			data: {
				category: {}
			},
			mounted: function(){
				var self = this;
				$("#formCreateCategory").cValidate({
					data: self.category
				});
			}
		});
	</script>
</div>