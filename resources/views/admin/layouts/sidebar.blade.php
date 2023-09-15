<div class="vertical-menu">
    <div data-simplebar class="h-100 sidebar-div">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Menu</li>                
                @foreach(side_menu() as $key => $value)
                <li>
                    <a  @if(!empty($value->route)) href="{{ !empty($value->route) ? route($value->route) : '' }}" @endif class="waves-effect {{ sub_menu_check($value->id) ? 'has-arrow' : ''}}">
                        <i class="{{ !empty($value->menu_icon) ? $value->menu_icon : '' }} icon-col"></i><span class="badge rounded-pill bg-info float-end"></span>
                        <span key="t-dashboards">{{$value->menu_name}}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                    @foreach(sub_menu() as $k => $val)
                    @if($value->id == $val->parent_id)
                        <li>
                            <a href="{{ !empty($val->route) ? route($val->route) : '' }}" key="t-default">
                                <i class="{{ !empty($val->menu_icon) ? $val->menu_icon : '' }} icon-col"></i>
                                <span key="t-dashboards">{{$val->menu_name}}</span>
                            </a>
                        </li>
                   
                    @endif
                    @endforeach
                 </ul>
                <li>
                    @endforeach
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>