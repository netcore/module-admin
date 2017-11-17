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
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Key</th>
                                <th>Name</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($menus as $menu)
                                <tr>
                                    <td>{{ $menu->key }}</td>
                                    <td>{{ $menu->name }}</td>
                                    <td width="15%" class="text-center">
                                        <a href="{{ route('admin::menus.show', $menu->id) }}"
                                           class="btn btn-default btn-sm">
                                            <i class="fa fa-eye"></i> Items ({{ $menu->items->count() }})
                                        </a>
                                        @if ($menu->type !== 'admin')
                                            <a href="{{ route('admin::menus.edit', $menu->id) }}"
                                               class="btn btn-primary btn-sm">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                        @endif
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
