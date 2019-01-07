
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Cashier</title>
    <link rel="icon" href="{{asset('img/rest_icon.png')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">

    <!-- Bootstrap core CSS -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Material Design Bootstrap -->
    <link href="{{asset('css/mdb.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/style.min.css')}}" rel="stylesheet">
    <script type="text/javascript" src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
    <style>
        .side-nav {
            transform: translateX(0%); width: 270px; padding: 0px 1.5rem 1.5rem; background-color:#fff; background-image:none;
        }
        .list-group-item active{
            
        }
        .side-nav a {
            line-height: 32px;
        }
        .navbar , main, .page-footer{
            padding-left: 15px;
        }
        .menu-container {
        display: flex;
        flex-wrap: wrap;
        }.btn-item{
            width: min-content;
        }
    </style>    
</head>

        
<body class="grey lighten-3">

    <!--Main Navigation-->
    <header>
        
        <!-- Navbar -->
        <nav class="navbar fixed-top navbar-toggleable-md navbar-expand-lg navbar-light white scrolling-navbar double-nav">
            <div class="float-left">
                <a href="#" data-activates="slide-out" class="button-collapse black-text">
                    <i class="fa fa-bars"></i>                    
                </a>
            </div>
          
            <!-- Links -->
            <ul class="nav navbar-nav nav-flex-icons" style="margin-left:28px"><h4>Nishan Hotel Nivithigala </h4></ul>
            <!-- Right -->
            <ul class="nav navbar-nav nav-flex-icons ml-auto">
                <li class="nav-item">
                    <a class="nav-link"><i class="fa fa-envelope"></i> <span class="clearfix d-none d-sm-inline-block">Notifications</span></a>
                </li>                      
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <i class="fa fa-user"></i>
                                {{ Auth::user()->fname }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
            </ul>           
        </nav>
        <!-- Navbar -->
       <!-- Sidebar -->
       {{-- <div class="side-nav sn-bg-4 " id="slide-out"  >
            <img src="{{asset('img/main_logo2.png')}}" class="img-fluid" alt="" style="max-width:64%;margin-left:33px;">
            <div class="list-group list-group-flush">
                <a href="{{url('/inventory')}}" class="list-group-item active waves-effect" style="webkit-box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);
                box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);
                -webkit-border-radius: 5px;
                border-radius: 5px;height: 50px;
                line-height: 25px;">
                    <i class="fa fa-pie-chart mr-3"></i>Dashboard
                </a>
                <a href="{{url('inventory/update')}}" class="list-group-item list-group-item-action waves-effect">
                    <i class="fa fa-user mr-3"></i>Update Stock</a>
                <a href="{{url('inventory/addnew')}}" class="list-group-item list-group-item-action waves-effect">
                    <i class="fa fa-table mr-3"></i>Add New Food-Items</a>
                <a href="{{url('inventory/recipe')}}" class="list-group-item list-group-item-action waves-effect">
                    <i class="fa fa-map mr-3"></i>Create Recipes</a>                
            </div>       
        </div> --}}
    <!-- Sidebar --> 
    </header>

    <main class="pt-5 mx-lg-5">
        <div class="container-fluid mt-5">
            
                    @include('inc.messages')
                    @yield('content')
                
        </div>    
    </main>       
    <!-- SCRIPTS -->
    <!-- JQuery -->
    
    <!-- Tooltips -->
    <script type="text/javascript" src="{{asset('js/popper.min.js')}}"></script>

    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>

    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="{{asset('js/mdb.min.js')}}"></script>
    <script>
        // SideNav Button Initialization
        $(".button-collapse").sideNav();
        // SideNav Scrollbar Initialization
        var sideNavScrollbar = document.querySelector('.custom-scrollbar');
        Ps.initialize(sideNavScrollbar);
      
    
    </script>

</body>

</html>
