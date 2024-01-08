<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title></title>

       {{-- Laravel Mix - CSS File --}}
       {{-- <link rel="stylesheet" href="{{ mix('css/customermodule.css') }}"> --}}

    </head>
    <body>
        <script>
            localStorage.theme && document.querySelector('body').setAttribute("theme", localStorage.theme);
            localStorage.dir && document.querySelector('html').setAttribute("dir", localStorage.dir);
        </script>
        @yield('content')

        {{-- Laravel Mix - JS File --}}
        {{-- <script src="{{ mix('js/customermodule.js') }}"></script> --}}
    </body>
</html>
