<html xmlns="http://www.w3.org/1999/xhtml" lang="{{ app()->getLocale() }}">
<head>
<title>Shop Module - @yield('title')</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="access-token" content="{{ current_user_device_token() }}">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.js"></script>
</head>
<body>
      @if (Auth::check())
            <p class="User"> Chào <b>{{ Auth::user()->getAuthIdentifier() }}</b>, 
            <a href="{{route('logout')}}"><b>Thoát</b></a></p>
      @else
      		<a href="{{route('login')}}"><b>Đăng Nhập</b></a>
      @endif
      @if (!empty($message))
      	<p>{{$message}}</p>
      @endif
      @if (!empty($error_message))
      	<p>{{$error_message}}</p>
      @endif
      @yield('content')
	<script type="text/javascript" src="{{asset('js/myapp.js')}}"></script>
	@stack('scripts')
</body>
</html>