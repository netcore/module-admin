@extends('admin::layouts.auth')

@section('content')

<!-- Reset form -->

<div class="page-signin-container" id="page-signin-forgot-form">
    <h2 class="m-t-0 m-b-4 text-xs-center font-weight-semibold font-size-20">Password reset</h2>
    <form action="{{route('admin::auth.reset')}}" method="post" class="panel p-a-4">

        @include('admin::_partials._messages')

        <fieldset class="form-group form-group-lg">
            <input type="email" class="form-control" placeholder="Your Email">
        </fieldset>

        <button type="submit" class="btn btn-block btn-lg btn-primary m-t-3">Resset password</button>

        {{ csrf_field() }}

        <div class="m-t-2 text-muted">
            <a href="{{route('admin::auth.login')}}" id="page-signin-forgot-back">&larr; Back</a>
        </div>
    </form>
    <div class="text-center m-t-5 font-size-12 text-muted">
        <a href="http://netcore.lv/" target="_blank">netcore development</a>
    </div>
</div>


<!-- / Reset form -->
@endsection