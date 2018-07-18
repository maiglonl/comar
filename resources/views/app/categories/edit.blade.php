<div>
	<div id="categoryEditApp">
		<h2 class="page-title">Categoria <small> | Edição de Categoria</small></h2>
		<form action="{{ route('categories.update', [$category->id]) }}" id="formEditCategory" method="PUT" data-prefix="cat">
			@include('app.categories._form')
		</form>
	</div>

	<script type="text/javascript">
		new Vue({
			el: '#categoryEditApp',
			data: {
				category: {!! $category->toJson() !!}
			},
			mounted: function(){
				var self = this;
				$("#formEditCategory").cValidate({
					data: self.category
				});
			}
		});
	</script>
</div>