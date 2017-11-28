@extends('admin::layouts.master')

@section('content')
    {!! Breadcrumbs::render('admin.whitelist.create') !!}

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
                        <a href="{{ route('admin::whitelist.index') }}" class="btn btn-xs btn-primary">
                            <i class="fa fa-undo"></i> Back to list
                        </a>
                    </div>
                    <h4 class="panel-title">Add IP whitelist</h4>
                </div>
                <div class="panel-body">
                    {!! Form::open(['route' => 'admin::whitelist.store', 'class' => 'form-horizontal']) !!}

                    @include('admin::whitelist._form')

                    <button type="submit" class="btn btn-success pull-right"><i class="fa fa-save"></i> Save</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
