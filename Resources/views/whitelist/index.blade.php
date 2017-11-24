@extends('admin::layouts.master')

@section('content')
    {!! Breadcrumbs::render('admin.whitelist') !!}

    <div class="page-header">
        <h1>
            <span class="text-muted font-weight-light">
                <i class="page-header-icon fa fa-list-ol"></i>
                IP Whitelist
            </span>
        </h1>
    </div>

    @include('admin::_partials._messages')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="{{ route('admin::whitelist.create') }}" class="btn btn-xs btn-success">
                            <i class="fa fa-plus"></i> Add
                        </a>
                    </div>
                    <h4 class="panel-title">IP whitelist</h4>
                </div>
                <div class="panel-body">
                    <div class="table-primary">
                        <table class="table table-bordered datatable">
                            <thead>
                            <tr>
                                <th>IP</th>
                                <th>Type</th>
                                <th>Comment</th>
                                <th width="15%" class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($whitelists as $whitelist)
                                <tr>
                                    <td>
                                        {{ $whitelist->ip }}
                                    </td>
                                    <td width="10%">
                                        {{ $whitelist->type }}
                                    </td>
                                    <td>
                                        {{ str_limit($whitelist->comment, 100) }}
                                    </td>
                                    <td width="15%" class="text-center">
                                        <a href="{{ route('admin::whitelist.edit', $whitelist) }}"
                                           class="btn btn-xs btn-primary">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        <a href="{{ route('admin::whitelist.destroy', $whitelist) }}"
                                           class="btn btn-danger btn-xs confirm-delete" data-id="{{ $whitelist->id }}">
                                            <i class="fa fa-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ versionedAsset('assets/admin/js/whitelist_index.js') }}" type="text/javascript"></script>
@endsection
