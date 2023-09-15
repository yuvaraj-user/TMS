<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="index.html" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{URL::to('public/images/favicon.ico')}}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{URL::to('public/images/mazenet.png')}}" alt="" height="17">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>

        </div>

        <div class="d-flex">

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <!-- <div class="rounded-circle header-profile-user"></div> -->
                    <img class="rounded-circle header-profile-user" src="{{ URL::to('public/images\avatar.webp') }}" alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1" key="t-henry">{{ ucfirst(Auth::user()->name) }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <!--<a class="dropdown-item" href="#"><i class="bx bx-user font-size-16 align-middle me-1"></i> <span key="t-profile">Profile</span></a> --->
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" onclick="
                                            document.getElementById('logout-form').submit();"><i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span key="t-logout">Logout</span></a>
                    <form id="logout-form" action="{{ route('signout') }}" method="GET" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
