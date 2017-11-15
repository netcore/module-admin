@extends('admin::layouts.master')

@section('content')
    <div class="panel">
        <div class="panel-heading">
            <span class="panel-title">Menus</span>
        </div>
        <div class="panel-body">
            <ul class="nav nav-tabs">
                @foreach ($menusGrouped as $type => $menus)
                    <li class="{{ $loop->first ? 'active' : '' }}">
                        <a href="#{{ $type }}-menus" data-toggle="tab" aria-expanded="true">
                            {{ ucfirst($type) }} <span class="label label-success"></span>
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content tab-content-bordered">
                @foreach ($menusGrouped as $type => $menus)
                    <div class="tab-pane fade in {{ $loop->first ? 'active' : '' }}" id="{{ $type }}-menus">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Items</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($menus as $menu)
                                <tr>
                                    <td>{{ ucwords(preg_replace(array('/(?<=[^A-Z])([A-Z])/', '/(?<=[^0-9])([0-9])/'), ' $0', $menu->name)) }}</td>
                                    <td>
                                        {{ $menu->items->count() }}
                                    </td>
                                    <td width="15%" class="text-center">
                                        <a href="{{ route('admin::menu.show', $menu->id) }}"
                                           class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
