<div class="leftside-menu leftside-menu-detached">

    <div class="leftbar-user">
        <a href="javascript: void(0);">
            <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="user-image" height="42" class="rounded-circle shadow-sm">
            <span class="leftbar-user-name">{{ Auth::user()->name }}</span>
        </a>
    </div>

    <!--- Sidemenu -->
    <ul class="side-nav">
        <!-- <li class="side-nav-title side-nav-item mt-1">Components</li> -->
        @if(Auth::user()->user_role == 'Super Admin')
        <li class="side-nav-item">
            <a data-bs-toggle="collapse"  id="user" href="#sidebarTables" aria-expanded="false" aria-controls="sidebarTables" class="side-nav-link">
                <i class="uil-user-square"></i>
                <span> Admin </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="sidebarTables">
                <ul class="side-nav-second-level">
                    <li>
                        <a id="user-link" href="{{route('users')}}"><i class="uil-users-alt"></i>Manage Users</a>
                    </li>
                    <!-- <li>
                        <a href="{{route('insertrole')}}"><i class="uil-user-plus"></i>Add User</a>
                    </li> -->
                    <li>
                        <a id="group-link" href="{{route('list-group')}}"><i class="uil-users-alt"></i>Manage Groups</a>
                    </li>
                    <!-- <li>
                        <a href="{{route('groups')}}"><i class="uil-user-plus"></i>Add Group</a>
                    </li> -->
                </ul>
            </div>
        </li>
        @endif
        <li class="side-nav-item">
            <a data-bs-toggle="collapse" href="#sidebarMaps" aria-expanded="false" aria-controls="sidebarMaps" class="side-nav-link">
                <i class=" uil-chart-bar"></i>
                <span> Reports </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="sidebarMaps">
                <ul class="side-nav-second-level">
                    @php
                    $key = Session::get('key');
                    $group_data = getUserpermission($key);
                    $cadence = null;
                    $reps = null;
                    if(isset($group_data['single_group'])){
                    $cadence = isset($group_data['single_group']->sl_cadence_ids) ? count($group_data['single_group']->sl_cadence_ids) : 0;
                    $reps = isset($group_data['single_group']->sl_user_ids) ? count($group_data['single_group']->sl_user_ids) : 0;
                    }
                    @endphp
                    @if($cadence >= 1 || Auth::user()->user_role == 'Super Admin' || $group_data['single_group']->group_name == 'All')
                    <li>
                        <a href="{{route('cadencereport')}}"><i class="uil-chart-line"></i>Cadence Report</a>
                    </li>
                    @endif
                    @if(Auth::user()->user_role == 'Super Admin' || $reps >= 1 || $group_data['single_group']->group_name == 'All')
                    <li>
                        <a href="{{route('executivereport')}}"><i class="uil-chart-line"></i>Executive Report</a>
                    </li>
                    @endif
                    @if($reps >= 1 || Auth::user()->user_role == 'Super Admin' || $group_data['single_group']->group_name == 'All')
                    <li>
                        <a href="{{route('singlerep')}}"><i class="uil-chart-line"></i>Reps Report</a>
                    </li>
                    @endif
                </ul>
            </div>
        </li>
    </ul>


    <!-- End Sidebar -->

    <div class="clearfix"></div>
    <!-- Sidebar -left -->

</div>
