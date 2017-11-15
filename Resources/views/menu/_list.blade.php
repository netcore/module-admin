<ol class="dd-list">
    @foreach( $items as $item )
        <li class="dd-item" data-id="{{ $item->id }}">
            <div class="pull-right">
                <div class="btn-sm btn-danger pull-right delete">
                    <i class="fa fa-trash"></i> Delete
                </div>
                <div class="btn-sm btn-primary pull-right edit">
                    <i class="fa fa-edit"></i> Edit
                </div>
            </div>
            <div class="dd-handle">
                @if( $item->icon )
                    <span><i class="{{ $item->icon }}"></i> </span>
                @endif
                <span>{{ $item->name }}</span>
                {{--<small class="url"></small>--}}
            </div>
            @if( $item->children )
                @include('admin::menu._list', ['items' => $item->children])
            @endif
        </li>
    @endforeach
</ol>
