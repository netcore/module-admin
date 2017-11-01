@extends('admin::layouts.master')

@section('content')

    <div class="panel">
        <div class="panel-heading">
            <span class="panel-title">Menus</span>
        </div>
        <div class="panel-body">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#admin-menus" data-toggle="tab" aria-expanded="true">
                        Admin <span class="label label-success">{{$adminMenus->count()}}</span>
                    </a>
                </li>
                <li>
                    <a href="#public-menus" data-toggle="tab" aria-expanded="true">
                        Public <span class="label label-success">{{$publicMenus->count()}}</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content tab-content-bordered">
                <div class="tab-pane fade active in" id="admin-menus">
                    @if($adminMenus->count() == 0)
                        <p>No menus available</p>
                    @else
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Items</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $adminMenus as $menu )
                                <tr>
                                    <td>{{ ucwords(preg_replace(array('/(?<=[^A-Z])([A-Z])/', '/(?<=[^0-9])([0-9])/'), ' $0', $menu->name)) }}</td>
                                    <td>
                                        {{$menu->items->count()}}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin::menu.show', $menu->id) }}" class="btn">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <div class="tab-pane fade" id="public-menus">
                    @if($publicMenus->count() == 0)
                        <p>No menus available</p>
                    @else
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Items</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $publicMenus as $menu )
                                <tr>
                                    <td>{{ ucwords(preg_replace(array('/(?<=[^A-Z])([A-Z])/', '/(?<=[^0-9])([0-9])/'), ' $0', $menu->name)) }}</td>
                                    <td>
                                        {{$menu->items->count()}}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin::menu.show', $menu->id) }}" class="btn">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection