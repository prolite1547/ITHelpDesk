<header class="header">
    <div class="row-flex u-padding-big">
        <div class="header__logo-box">
            <img src="{{asset('images/icon.png')}}" alt="Citihardware Logo" class="header__logo">
        </div>
        <div class="header__user">
            <div class="user">
                <div class="user__name">{{Auth::user()->full_name}}</div>
                <span class="user__position">{{Auth::user()->role->role}}</span>
            </div>
            <div class="header__settings">
                <div class="header__dropdown">
                    <svg class="icon">
                        <use xlink:href="{{asset('svg/sprite.svg#icon-caret-down')}}"></use>
                        <ul class="header__list">
                            <li class="header__item"><a href="{{route('userProfile',['id' => Auth::id()])}}" class="header__link">Profile</a></li>
                            <li class="header__item">
                                <a href="!#" class="header__link" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">Logout</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </svg>

                </div>
            </div>
        </div>
    </div>

@if(Auth::user()->role_id != 5 AND Auth::user()->role_id != 6  AND  Auth::user()->role_id != 7  AND  Auth::user()->role_id != 8 )
    <div class="row-flex row-flex__ai--fe">
        <div class="left">
            <nav class="nav">
                <ul class="nav__ul">
                    <li class="nav__li">
                        <a href="{{route('dashboard')}}" class="nav__a {{Route::currentRouteName() == 'dashboard' ? 'nav__a--active' : ''}}">Dashboard</a>
                    </li>
                    <li class="nav__li">
                        <a href="{{route('myTickets')}}" class="nav__a {{in_array(Route::currentRouteName(),$ticketRoutes) ? 'nav__a--active' : ''}}">Tickets</a>
                    </li>
                    <li class="nav__li">
                        <a href="javascript:void(0);" class="nav__a">Requests</a>
                    </li>
                    <li class="nav__li">
                        <a href="{{route('datacorrectons.sdcDeployment')}}"class="nav__a {{in_array(Route::currentRouteName(),$dcRoutes) ? 'nav__a--active' : ''}}">Data Corrections</a>
                    </li>
                    <li class="nav__li">
                        <a href="{{route('reportsPage')}}" class="nav__a {{Route::currentRouteName() == 'reportsPage' ? 'nav__a--active' : ''}}">Reports</a>
                    </li>
                    @if(in_array(Auth::user()->role_id,[$user_roles['admin'],$user_roles['tower']]))
                        <li class="nav__li">
                            <a href="{{route('maintenancePage')}}" class="nav__a {{Route::currentRouteName() == 'maintenancePage' ? 'nav__a--active' : ''}}">Maintenance</a>
                        </li>
                    @endif
                    <li class="nav__li">
                        <a href="#" class="nav__a">Manual</a>
                    </li>
                    @if(Auth::user()->role_id === $user_roles['admin'])
                    <li class="nav__li">
                        <a href="{{route('adminPage')}}" class="nav__a {{Route::currentRouteName() == 'adminPage' ? 'nav__a--active' : ''}}">Admin</a>
                    </li>
                    @endif
                </ul>
            </nav>
        </div>

        <div class="right">
            <a href="{{route('addTicketView')}}" id="addTicketPageBtn" class="btn btn--green btn--add"><i class="fas fa-plus"></i> New Ticket</a>
            {{Form::open(['method' => 'GET','url'=>'/search','class' => 'form form--search'])}}
                <i class="fas fa-search form--search__icon"></i>
                {{Form::text('q',null,array('class' => 'form__search','placeholder' => 'search... (or ticket ID)'))}}
            {{Form::close()}}
        </div>
    </div>

    <nav class="submenu">
        @section('submenu')
            <ul class="submenu__ul">
                <li class="submenu__li">
                    <a href="{{route('myTickets')}}" class="submenu__a {{Route::currentRouteName() == 'myTickets' ? 'submenu__a--active' : ''}}">
                        My Tickets <span>({{$ticketUserTicketsCount}})</span>
                        @if($notificationContent)
                        <span class="notif">{{$notificationContent}}
                            <svg class="notif__caret">
                                <use xlink:href="{{asset('svg/sprite.svg#icon-caret-down')}}">
                                </use>
                            </svg>
                        </span>
                        @endif
                    </a>
                </li>
                <li class="submenu__li">
                    <a href="{{route('openTickets')}}" class="submenu__a {{Route::currentRouteName() == 'openTickets' ? 'submenu__a--active' : ''}}">Open <span>({{$ticketCounts['Open']}})</span></a>
                </li>
                <li class="submenu__li">
                    <a href="{{route('ongoingTickets')}}" class="submenu__a {{Route::currentRouteName() == 'ongoingTickets' ? 'submenu__a--active' : ''}}">Ongoing <span>({{$ticketCounts['Ongoing']}})</span></a>
                </li>
                <li class="submenu__li">
                    <a href="{{route('expiredTickets')}}" class="submenu__a {{Route::currentRouteName() == 'expiredTickets' ? 'submenu__a--active' : ''}}">Expired <span>({{$ticketCounts['Expired']}})</span></a>
                </li>
                {{--<li class="submenu__li">--}}
                    {{--<a href="{{route('verificationTickets')}}" class="submenu__a {{Route::currentRouteName() == 'verificationTickets' ? 'submenu__a--active' : ''}}">For Verification <span>(0)</span></a>--}}
                {{--</li>--}}
                @if(in_array(Auth::user()->role_id,[2,4]))
                <li class="submenu__li">
                    <a href="{{route('closedTickets')}}" class="submenu__a {{Route::currentRouteName() == 'closedTickets' ? 'submenu__a--active' : ''}}">Resolved <span>({{$ticketCounts['Closed']}})</span></a>
                </li>
                <li class="submenu__li">
                    <a href="{{route('fixedTickets')}}" class="submenu__a {{Route::currentRouteName() == 'fixedTickets' ? 'submenu__a--active' : ''}}">To Resolve<span>({{$ticketCounts['Fixed']}})</span></a>
                </li>
                @endif
                <li class="submenu__li">
                    <a href="{{route('allTickets')}}" class="submenu__a {{Route::currentRouteName() == 'allTickets' ? 'submenu__a--active' : ''}}">All <span>({{$ticketCounts['All']}})</span></a>
                </li>
            </ul>
        @show
    </nav>
@else
<div class="row-flex">
    <div class="left">
        <nav class="nav">
            <ul class="nav__ul">
                @if (Auth::user()->role_id === 5)
                    <li class="nav__li">
                        <a href="{{route('datacorrectons.treasuryPENDING')}}"class="nav__a {{in_array(Route::currentRouteName(),$tyRoutes) ? 'nav__a--active' : ''}}">Data Corrections</a>
                    </li>
                @elseif (Auth::user()->role_id === 6)
                    <li class="nav__li">
                        <a href="{{route('datacorrectons.treasury2PENDING')}}"class="nav__a {{in_array(Route::currentRouteName(),$ty2Routes) ? 'nav__a--active' : ''}}">Data Corrections</a>
                    </li>
                   
                @elseif (Auth::user()->role_id === 7)
                     <li class="nav__li">
                        <a href="{{route('datacorrectons.govcompPENDING')}}"class="nav__a {{in_array(Route::currentRouteName(),$gcRoutes) ? 'nav__a--active' : ''}}">Data Corrections</a>
                     </li>
                @elseif(Auth::user()->role_id === 8)
                      <li class="nav__li">
                        <a href="{{route('datacorrectons.approverPENDING')}}"class="nav__a {{in_array(Route::currentRouteName(),$appRoutes) ? 'nav__a--active' : ''}}">Data Corrections</a>
                     </li>
                @endif
                    
            </ul>
        </nav>
    </div>
    <div class="right">
        {{Form::open(['method' => 'GET','url'=>'/search/sdc','class' => 'form form--search'])}}
            <i class="fas fa-search form--search__icon"></i>
            {{Form::text('q',null,array('class' => 'form__search','placeholder' => 'Search.. (sdc number)'))}}
        {{Form::close()}}
    </div>
</div>

<nav class="submenu">
    @section('submenu')
     
    @show
</nav>


@endif

</header>
