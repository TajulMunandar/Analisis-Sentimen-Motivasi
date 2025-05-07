<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SVM | Analisis Sentimen</title>
    @include('dashboard.layout.head')
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        @include('dashboard.layout.sidebar')
        <!--  Main wrapper -->
        <div class="body-wrapper">
            @include('dashboard.layout.navbar')
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </div>

    @include('dashboard.layout.script')
    @yield('script')
</body>

</html>
