<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>@yield('admin_title')</title>
  <!-- plugins:css -->
    @yield('head_css')
  <!-- endinject -->
  <link rel="shortcut icon" href={{asset('assets/adminhtml/images/favicon.png')}} />
  @yield("head_js")
</head>
<body>

    @yield('body')

    @yield('bottom_js')
</body>

</html>

