@extends('admin::layouts.master')

@section('content')
    <h1>You don't have permissions to access this page.</h1>

    <div class="row">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">Accessible routes / urls</h4>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Routes</th>
                            <th>Urls</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($routes as $route)
                            <tr>
                                <td>{{ $route->route }}</td>
                                <td>{{ $route->uri }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection


