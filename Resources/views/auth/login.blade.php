@extends('admin::layouts.auth')

@section('content')
<!-- Sign In form -->

<div class="page-signin-container" id="page-signin-form">
    <h2 class="m-t-0 m-b-4 text-xs-center font-weight-semibold font-size-20">Sign In to your Account</h2>

    <form action="{{route('admin::auth.login')}}" method="post" class="panel p-a-4">

        @include('admin::_partials._messages')

        <fieldset class=" form-group form-group-lg">
            <input type="text" name="email" class="form-control" placeholder="Email">
        </fieldset>

        <fieldset class=" form-group form-group-lg">
            <input type="password" name="password" class="form-control" placeholder="Password">
        </fieldset>

        <div class="clearfix">
            <label class="custom-control custom-checkbox pull-xs-left">
                <input type="checkbox" class="custom-control-input">
                <span class="custom-control-indicator"></span>
                Remember me
            </label>
            <a href="{{route('admin::auth.request')}}" class="font-size-12 text-muted pull-xs-right" id="page-signin-forgot-link">Forgot your password?</a>
        </div>

        <button type="submit" class="btn btn-block btn-lg btn-primary m-t-3">Sign In</button>

        {{ csrf_field() }}

    </form>
    @if (config('netcore.module-admin.show_copyrights'))
    <div class="text-center m-t-5 font-size-12 text-muted">
        <a href="http://netcore.lv/" target="_blank">netcore development</a>
    </div>
    @endif
</div>

<!-- / Sign In form -->

@endsection