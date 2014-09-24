
<aside id="left-panel">
	<nav>
		<ul>
    	@foreach(SystemModules::getSidebarModules() as $name => $module)
<? #echo (string)Request::segment(2) . " == " . $module['link'] . " > " . (int)((string)Request::segment(2) == $module['link']); ?>
<? #dd(Request::segment(2)); ?>
<?php
#Helper::d($_SERVER['REQUEST_URI']);
#Helper::d("/" . AuthAccount::getStartPage($module['link']));
#Helper::d( (string)Request::segment(2)."/".(string)Request::segment(3) . " == " . $module['link'] );
#Helper::d( $module );
#Helper::d(URL::to(AuthAccount::getStartPage().'/'.$module['link']));
#Helper::d( $module['link']  == (string)Request::segment(2)."/".(string)Request::segment(3) );
$menu_child_active = false;
$class = false;
if (
    $module['link'] == (string)Request::segment(2) ## pages
    || $module['link'] == (string)Request::segment(2)."/".(string)Request::segment(3) ## 48hours/places
    #|| @$_SERVER['REQUEST_URI'] == "/" . AuthAccount::getStartPage($module['link']) ## admin/48hours/places
)
    #$class = 'active';
    $menu_child_active = "active open";
$have_childs = isset($module['menu_child']) && is_array($module['menu_child']) && count($module['menu_child']);
?>
			<li class='{{ @$menu_child_active }}'>
				<a href="{{ URL::to(link::auth($module['link'])) }}" title="{{{ $module['title'] }}}"{{ (isset($module['menu_child']) && !empty($module['menu_child']) && $module['link'] == '#') ? ' onclick="return false;"' : '' }}>
					<i class="fa fa-lg fa-fw {{ $module['class'] }}">
                        @if (@is_callable($module['icon_badge']))
                            {{ $module['icon_badge']() }}
                        @endif
					</i>
                    <span class="menu-item-parent">{{{ $module['title'] }}}</span>
                    @if (@is_callable($module['badge']))
                        {{ $module['badge']() }}
                    @endif
                    @if ($have_childs)
                    {{-- <b class="collapse-sign"><em class="fa fa-plus-square-o"></em></b> --}}
                    @endif
				</a>
			    @if($have_childs)
				<ul{{ $menu_child_active ? ' style="display:block;"' : '' }}>
				    @foreach($module['menu_child'] as $child_name => $child_module)
<? #echo Request::segment(3) . " == " . $child_module['link'] . " > " . (int)(Request::segment(3) == $child_module['link']); ?>

					<li class="sidebar_child_menu{{
                        (
                            (Request::segment(2) != '' && Request::segment(2) == $child_module['link'])
                            || (Request::segment(2) != '' && Request::segment(3) != '' && Request::segment(2)."/".Request::segment(3) == $child_module['link'])
                            || strpos($_SERVER['REQUEST_URI'], $child_module['link'])
                        )
                        ? ' active'
                        : ''
                        }}">
						<a href="{{ URL::to(link::auth($child_module['link'])) }}" title="{{{ $child_module['title'] }}}">
							<i class="fa fa-lg fa-fw {{ $child_module['class'] }}"></i> <span class="menu-item-parent">{{{ $child_module['title'] }}}</span>
						</a>
					</li>
				    @endforeach
				</ul>
			    @endif
			</li>
    	@endforeach
		</ul>
	</nav>
	<span class="minifyme"> <i class="fa fa-arrow-circle-left hit"></i> </span>
</aside>
