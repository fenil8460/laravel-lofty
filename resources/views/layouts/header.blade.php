   <!-- Topbar Start -->
   <div class="navbar-custom topnav-navbar topnav-navbar-dark">
       <style>
           .add-dropdwon {
               display: flex;
               justify-content: space-between;
           }

           .group-drop-down {
               background-color: #3c4655;
               border: 1px solid #414d5d;
               padding: 20px;
           }

           .select-group {
               background-color: transparent;
               border: none;
               color: #98a6ad;
           }

           /*the container must be positioned relative:*/
           .custom-select {
               position: relative;
               font-family: Arial;
               display: flex;
               padding-right: 25px;
           }

           .custom-select select {
               display: none;
               /*hide original SELECT element:*/
           }



           /*style the arrow inside the select element:*/
           .select-selected:after {
               position: absolute;
               content: "";
               top: 45%;
               right: 10px;
               width: 0;
               height: 0;
               border: 6px solid transparent;
               border-color: #8fa0aa transparent transparent transparent;
           }

           /*point the arrow upwards when the select box is open (active):*/
           .select-selected.select-arrow-active:after {
               border-color: transparent transparent #8fa0aa transparent;
               top: 0px;
           }

           /*style the items (options), including the selected item:*/
           .select-items div,
           .select-selected {
               color: #92a0a7;
               padding: 0px 16px;
               border: 0px solid transparent;
               border-color: transparent transparent rgba(0, 0, 0, 0.1) transparent;
               cursor: pointer;
               user-select: none;
               width: auto;
               font-size: 14px;
           }

           .select-items div {
               width: 100%;
               padding: 8px 15px;
           }

           /*style items (options):*/
           .select-items {
               position: absolute;
               background-color: #fff;
               top: 100%;
               left: 0;
               right: 0;
               z-index: 99;
           }

           /*hide the items when the select box is closed:*/
           .select-hide {
               display: none;
           }

           .select-items div:hover,
           .same-as-selected {
               background-color: rgba(0, 0, 0, 0.1);
           }
       </style>
       <div class="container-fluid add-dropdwon">

           <!-- LOGO -->
           <a href="/" class="topnav-logo">
               <span class="topnav-logo-lg">
                   <img src="{{ asset('assets/images/logo-light.png')}}" alt="" height="16">
               </span>
               <span class="topnav-logo-sm">
                   <img src="{{ asset('assets/images/logo_sm.png') }}" alt="" height="16">
               </span>
           </a>
           @php
           $datas = getUserpermission();
           @endphp
           @if(isset($datas['group']))
           @if(count($datas['group']) > 1 || (count($datas['group']) >1 && Auth::user()->user_role == 'Super Admin'))
           @php
           $group = $datas['group'];
           $i = 0;
           @endphp
           <div class="group-drop-down">
               <div id='top_group'>
                   <label for="Group" class="select-group">Group:
                       <select id="group_name" class="select-group" onchange="location = this.value;">
                           <option default disabled>Select Group</option>
                           <!-- <option value="{{route('home')}}?key=allgroup"><a href="{{route('home')}}?key=allgroup">All</a></option> -->
                           @foreach($group as $data)
                           <option value="{{route('home')}}?key={{$i}}"><a href="{{route('home')}}?key={{$i}}">{{$data->group_name}}</a></option>
                           @php
                           $i++;
                           @endphp
                           @endforeach
                       </select>
                   </label>
               </div>
           </div>
           <!-- <div class="group-drop-down">
               <div class="custom-select">
                   <label for="Group" class="select-group">Group:</label>
                   <select onchange="location = this.value;">
                       <option default>Select car:</option>
                       @foreach($group as $data)
                       <option value="{{route('home')}}?key={{$i}}"><a href="{{route('home')}}?key={{$i}}">{{$data->group_name}}</a></option>
                       @php
                       $i++;
                       @endphp
                       @endforeach
                   </select>
               </div>
           </div> -->
           @elseif(count($datas['group']) == 1 || Auth::user()->user_role == 'Super Admin')
           @php
           Session::forget('key');
           @endphp
           <div class="group-drop-down">
               @foreach($datas['group'] as $data)
               <label for="Group" class="select-group">Group: {{$data->group_name}}</label>
               @endforeach
           </div>
           @endif
           @endif
           <ul class="list-unstyled topbar-menu float-end mb-0">
               <li class="dropdown notification-list">
                   <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown" id="topbar-userdrop" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                       <span class="account-user-avatar">
                           <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="user-image" class="rounded-circle">
                       </span>
                       <span>
                           <span class="account-user-name">{{ Auth::user()->name }}</span>
                       </span>
                   </a>
                   <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown" aria-labelledby="topbar-userdrop">
                       <!-- item-->
                       <a href="javascript:void(0);" class="dropdown-item notify-item">
                           <i class="mdi mdi-account-circle me-1"></i>
                           <span>My Account</span>
                       </a>

                       <a href="/billing" class="dropdown-item notify-item">
                           <i class="mdi mdi-lock-outline me-1"></i>
                           <span>Billing</span>
                       </a>
                       <form method="POST" action="{{ route('logout') }}">
                           @csrf
                           <!-- item-->
                           <a href="{{ route('logout') }}" id="logout-data" class="dropdown-item notify-item" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                               <i class="mdi mdi-logout me-1"></i>
                               <span>Logout</span>
                           </a>
                       </form>
                   </div>
               </li>
           </ul>
           <a class="button-menu-mobile disable-btn">
               <div class="lines">
                   <span></span>
                   <span></span>
                   <span></span>
               </div>
           </a>

       </div>
   </div>
   <!-- end Topbar -->
   <!-- Start Content-->
   <div class="container-fluid">

       <!-- Begin page -->
       <div class="wrapper">