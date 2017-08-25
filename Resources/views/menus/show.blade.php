@extends('admin::layouts.master')

@section('content')

<div class="row">
    <div class="col-md-8">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">Edit menu: {{$menu->name}}</span>
            </div>

            <div class="panel-body" style="padding:20px;">
                <div class="dd">
                    @include('admin::menus._list',['items' => $items])
                </div>
            </div>

        </div>
    </div>
    <div class="col-md-4">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">Add new menu item</span>
            </div>

            <div class="panel-body" style="padding:20px;">

                <select name="" class="form-control">
                    <option value="">-- select --</option>
                    <option value="route">route</option>
                    <option value="url">custom url</option>
                    <option value="page">page</option>
                    <option value="empty">empty</option>
                    <option value="seperator">seperator</option>
                </select>

                <hr>

            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script type="text/javascript" src="{{asset('/assets/admin/js/jquery.nestable.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('.dd').nestable().on('change', function (e) {
                $.post('{{route('admin::menu.save-order', $menu->id)}}', {
                    order: JSON.stringify($('.dd').nestable('serialize')),
                }, function (data) {
                    toastr.success("Menu order updated");
                });
            });
        });
    </script>
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/admin/css/nestable.css')}}">
@endsection