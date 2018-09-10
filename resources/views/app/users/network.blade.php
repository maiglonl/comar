@extends('layouts.app')

@section('content')
	<style type="text/css">
		#networkCollapse {
			width: 40px;
			height: 40px;
			border:none;
		}
		#networkCollapse span {
			width: 12px;
			height: 2px;
			margin: 0 auto;
			display: block;
			background: #555;
			transition: all 0.8s cubic-bezier(0.810, -0.330, 0.345, 1.375);
		}
		#networkCollapse span:first-of-type {
			transform: translate(-4px, 1px) rotate(45deg);
		}
		#networkCollapse span:last-of-type {
			transform: translate(4px, -1px) rotate(-45deg);
		}
		#networkCollapse.active span:first-of-type {
			transform: translate(-4px, 4px) rotate(-45deg);
		}
		#networkCollapse.active span:last-of-type {
			transform: translate(4px, -3px) rotate(45deg);
		}
		#networkCollapse.active span {
			transform: none;
			opacity: 1;
			margin: 5px auto;
		}
		@media (max-width: 768px) {

			#networkCollapse span:first-of-type,
			#networkCollapse span:nth-of-type(2),
			#networkCollapse span:last-of-type {
				transform: none;
				opacity: 1;
				margin: 5px auto;
			}
			#networkCollapse.active span {
				margin: 0 auto;
			}
			#networkCollapse.active span:first-of-type {
				transform: rotate(45deg) translate(2px, 2px);
			}
			#networkCollapse.active span:last-of-type {
				transform: rotate(-45deg) translate(1px, -1px);
			}
		}
	</style>

	<div class="container-fluid p-0 bg-white" id="appOrdersList">
		<div class="page-title">
			<h3>Minha Rede | <small>Lista de parceiros cadastrados</small></h3>
		</div>
		<div class="card p-4 m-4" v-for="user in users">
			<network-menu
				:depth="0"
				:obj=user
			></network-menu>
		</div>
	</div>

	<script type="text/x-template" id="network-menu">
		<div class="network-menu">
			<div class="name-wrapper" @click="toggleChildren">
				<div :style="indent" :class="nameClasses">	
					<button :class="{'invisible': obj.childrens.length == 0 }" type="button" id="networkCollapse" class="btn-link pointer" :class="iconClasses">
						<span></span> <span></span>
					</button>
					@{{ obj.name }}
				</div>
			</div>
			<network-menu 
				v-if="showChildren"
				v-for="(children, key) in obj.childrens"  
				:obj="children"
				:depth="depth + 1"
			>
			</network-menu>
		</div>
	</script>
	<script type="text/javascript">
		Vue.component('network-menu', { 
			template: '#network-menu',
			props: [ 'obj', 'depth' ],
			data() {
				 return {
					showChildren: false,
				 }
			},
			computed: {
				nameClasses() {
					return { 
						'has-children': this.obj.childrens,
						'border-top': this.depth > 0,
					}
				},
				iconClasses() {
					return { 
						'active': this.showChildren
					}
				},
				indent() {
					return { 'margin-left': `${this.depth * 30}px` }
				}
			},
			methods: {
				toggleChildren() {
					 this.showChildren = !this.showChildren;
				}
			}
		});

		new Vue({
			el: '#appOrdersList',
			data: {
				users: {!! $users->toJson() !!}
			},
			mounted: function(){
				console.log(this.users);
			},
			methods:{
			},
			filters: filters
		});
	</script>
@endsection
