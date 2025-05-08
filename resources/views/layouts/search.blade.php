<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" >
<head>
	<title>@yield('title','') | EMS</title>
	<!-- initiate head with meta tags, css and script -->
	@include('include.head')
</head>
<body id="app" >
   <div class="wrapper">
      <!-- initiate header-->
	    <div class="page-wrap">
	        <!-- initiate sidebar-->
	        <!-- yeild contents here -->
	        @yield('content')
	         <!-- initiate footer section-->
	        @include('include.footer')
	    </div>
   </div>
   <!-- initiate scripts-->
   @include('include.script')	
</body>
</html>