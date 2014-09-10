<aside id="left-panel">
	<nav>
		<ul>
	@foreach(SystemModules::getSidebarModules() as $name => $module)
		@if(!Module::where('name', $name)->exists())
			@if(empty($module[2]) || allow::valid_access($module[2]))
				@if($module[2] == 'catalogs' && isset($module[3]) && !empty($module[3]))
					@foreach($module[3] as $catalog_name => $catalog_module)
						@if($catalog_module[2] == 'products')
							<li{{ (Request::segment(3) == $catalog_name)?' class="active"':''}}>
								<a href="{{slink::createLink($catalog_name)}}" title="{{{$catalog_module[0]}}}">
									<i class="fa fa-lg fa-fw {{$catalog_module[1]}}"></i> <span class="menu-item-parent">{{{$catalog_module[0]}}}</span>
								</a>
							</li>
						@endif
					@endforeach
				@else
				<li{{ (Request::segment(2) == $name)?' class="active"':''}}>
					<a href="{{slink::createLink($name)}}" title="{{{$module[0]}}}">
						<i class="fa fa-lg fa-fw {{$module[1]}}"></i> <span class="menu-item-parent">{{{$module[0]}}}</span>
					</a>
				</li>
				@endif
			@endif
		@endif
	@endforeach
		</ul>
	</nav>
	<span class="minifyme"> <i class="fa fa-arrow-circle-left hit"></i> </span>
</aside>