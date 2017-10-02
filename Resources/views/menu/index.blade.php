@extends('admin::layouts.master')

@section('content')

    <div class="panel">
        <div class="panel-heading">
            <span class="panel-title">Menus</span>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach( $menus as $menu )
                <tr>
                    <td>{{ $menu->name }}</td>
                    <td>
                        <a href="{{ route('admin::menu.show', $menu->id) }}" class="btn">Edit</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection
