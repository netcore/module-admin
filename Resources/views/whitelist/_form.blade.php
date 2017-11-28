<div class="form-group{{ $errors->has('ip') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label">IP address</label>
    <div class="col-md-8">
        {!! Form::text('ip', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label">Type</label>
    <div class="col-md-8">
        {!! Form::select('type', ['exact' => 'Exact', 'wildcard' => 'Wildcard'], null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group{{ $errors->has('comment') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label">Comment</label>
    <div class="col-md-8">
        {!! Form::text('comment', null, ['class' => 'form-control']) !!}
    </div>
</div>
