<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    @yield('title-section')
    <!-- <title>Lofty Data | Admin Dashboard</title> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- third party css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendor/toastr.min.css') }}">
    <link href="{{ asset('assets/css/vendor/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/vendor/dataTables.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/vendor/responsive.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/vendor/buttons.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/vendor/select.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <!-- third party css end -->
    <!-- App css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/developer.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app-modern.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />
    <link href="{{ asset('assets/css/app-modern-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <style>
        p.text-sm.text-gray-700.leading-5 {
            margin-top: 20px;
        }

        .DTFC_LeftBodyLiner {
            width: 600px !important
        }

        table.dataTable {
            margin-bottom: unset !important;
            margin-top: unset !important;
        }
    </style>
</head>

<body class="loading" data-layout="detached" data-layout-config='{"leftSidebarCondensed":false,"darkMode":false, "showRightSidebarOnStart": true}'>

    @include('layouts.header')
    @include('layouts.sidebar')
    <div class="content-page">
        <div class="content">
            @yield('main-section')
        </div>
        @include('layouts.footer')
    </div>
    @if(Session::has('message'))
    <script>
        var type = "{{ Session::get('alert-type', 'info') }}";
        switch (type) {
            case 'info':
                toastr.info("{{ Session::get('message') }}");
                break;

            case 'warning':
                toastr.warning("{{ Session::get('message') }}");
                break;

            case 'success':
                toastr.success("{{ Session::get('message') }}");
                break;

            case 'error':
                toastr.error("{{ Session::get('message') }}");
                break;
        }
    </script>
    @endif
    <script>
        $('#group_name').on('change', function(e) {
            var conceptName = $(this).find(":selected").text();
            sessionStorage.setItem("group_val", conceptName);
        })
        $('#group_name option').map(function() {
            if ($(this).text() == sessionStorage.getItem("group_val")) return this;
        }).attr('selected', 'selected');
        $('#logout-data').click(function() {
            sessionStorage.clear();
        })
        $('#scroll-horizontal-datatable').DataTable().destroy();
    </script>
    @yield('script-section')
</body>

</html>