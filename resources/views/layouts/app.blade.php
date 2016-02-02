<?php
namespace App;
?>
<!DOCTYPE html>
<html lang="{{ \App::getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="language" content="{{ \App::getLocale() }}">

    <title>@section('title') Application @show</title>
    <meta name="description" content="@section('description') @show">
    <meta name="author" content="Jaap Moolenaar - jaapmoolenaar.nl">
    <meta name="token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="apple-touch-icon" href="/apple-touch-icon.png" />
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png" />
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png" />
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png" />

    {!! trim(Asset::css()."\n".Asset::styles()) !!}
    <link href="{{ asset("css/all.css") }}" rel="stylesheet">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

{!! trim(Asset::js('header', "\n    ")."\n".Asset::scripts('header')) !!}
</head>
<body id="app-layout">
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <a class="navbar-brand" href="{{ url('/') }}">
                    Laravel
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                @if (isset($mainMenu))
                    <ul class="nav navbar-nav">
                        @include(config('laravel-menu.views.bootstrap-items'), array('items' => $mainMenu->roots()))
                    </ul>
                @endif
                
                @if (\Auth::check() && isset($userMenu))
                    <ul class="nav navbar-nav">
                        @include(config('laravel-menu.views.bootstrap-items'), array('items' => $userMenu->roots()))
                    </ul>
                @endif
                
                <ul class="nav navbar-nav navbar-right">
                    @if (\Auth::guest())
                        @if(Settings::get('auth.loginenabled', true))
                            <li><a href="{{ url('/login') }}">Login</a></li>
                        @endif
                        @if(Settings::get('auth.registerenabled', true))
                            <li><a href="{{ url('/register') }}">Register</a></li>
                        @endif
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ \Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/profile') }}"><i class="fa fa-btn fa-user"></i>Profile</a></li>
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>

                @if (\Auth::check() && isset($adminMenu))
                    <ul class="nav navbar-nav navbar-right">
                        @include(config('laravel-menu.views.bootstrap-items'), array('items' => $adminMenu->roots()))
                    </ul>
                @endif
            </div>
        </div>
    </nav>

    @yield('content')

    {!! trim(Asset::js('footer')."\n".Asset::scripts('footer')."\n".Asset::scripts('ready')) !!}
    <script src="{{ asset("js/all.js") }}"></script>
</body>
</html>
