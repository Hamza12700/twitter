<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{$title}}</title>
    <script src="https://cdn.jsdelivr.net/npm/htmx.org@2.0.8/dist/htmx.min.js"></script>
    <link rel="icon" type="image/x-icon" href="favicon.png">
    <script>
    htmx.config.responseHandling = [
      {code:"204",swap:false},
      {code:"[2]..",swap:true},
      {code:"[4]..",swap:true},
      @if (config("app.debug"))
        {code:"500", swap:true},
      @endif
    ];
    </script>

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="bg-[#121212] text-[#e6e6e6]">
    {{$slot}}
  </body>
</html>
