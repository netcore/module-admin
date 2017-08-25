
<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

    <title>{{ config('app.name') }} : Admin</title>

    <link rel="stylesheet" href="/assets/admin/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/admin/css/pixeladmin.min.css">
    <link rel="stylesheet" href="/assets/admin/css/widgets.min.css">
    <link rel="stylesheet" href="/assets/admin/css/themes/{{config('admin.theme')}}.min.css">
    <link rel="stylesheet" href="/assets/admin/demo/demo.css">

    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/holder/2.9.0/holder.js"></script>
    <script src="/assets/admin/pace/pace.min.js"></script>
    <script src="/assets/admin/demo/demo.js"></script>

    <!-- Custom styling -->
    <style>
        .page-signin-header {
            box-shadow: 0 2px 2px rgba(0,0,0,.05), 0 1px 0 rgba(0,0,0,.05);
        }

        html[dir="rtl"] .page-signin-header .btn {
            right: auto;
            left: 15px;
        }

        .page-signin-container {
            width: auto;
            margin: 30px 10px;
        }

        .page-signin-container form {
            border: 0;
            box-shadow: 0 2px 2px rgba(0,0,0,.05), 0 1px 0 rgba(0,0,0,.05);
        }

        @media (min-width: 544px) {
            .page-signin-container {
                width: 350px;
                margin: 60px auto;
            }
        }

    </style>
    <!-- / Custom styling -->
</head>
<body>
<div class="page-signin-header p-a-2 text-sm-center bg-white">
    <a class="px-demo-brand px-demo-brand-lg text-default" href="{{route('admin::auth.login')}}"><span class="px-demo-logo bg-primary m-t-0"><span class="px-demo-logo-1"></span><span class="px-demo-logo-2"></span><span class="px-demo-logo-3"></span><span class="px-demo-logo-4"></span><span class="px-demo-logo-5"></span><span class="px-demo-logo-6"></span><span class="px-demo-logo-7"></span><span class="px-demo-logo-8"></span><span class="px-demo-logo-9"></span></span>{{ config('app.name') }}</a>
</div>

@yield('content')

<!-- jQuery -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<script src="/assets/admin/js/bootstrap.min.js"></script>
<script src="/assets/admin/js/pixeladmin.min.js"></script>

<script>
    // -------------------------------------------------------------------------
    // Initialize page components

    $(function() {
        pxDemo.initializeBgsDemo('body', 0, '#000', function(isBgSet) {
            $('h2')[isBgSet ? 'addClass' : 'removeClass']('text-white font-weight-bold');

            $('h4')
                    .addClass(isBgSet ? 'text-white' : 'text-muted')
                    .removeClass(isBgSet ? 'text-muted' : 'text-white');
        });


        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
</body>
</html>
