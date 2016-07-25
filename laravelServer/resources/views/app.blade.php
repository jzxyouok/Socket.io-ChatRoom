<html>
    <head>
        <title>App Name - @yield('title')</title>
        <link rel="stylesheet" type="text/css" href="/css/allcss.css">
    </head>
    <body>
    <script src="https://cdn.socket.io/socket.io-1.3.4.js"></script>
    <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>

  <script>
     //var socket = io.connect('http://localhost:8890');
   $.ajaxSetup({
                   headers: {
                       'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                   }
               });
   </script>
          <div class="container">
            @yield('content')
        </div>
    </body>
</html>