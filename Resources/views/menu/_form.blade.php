@include('translate::_partials._nav_tabs')

<!-- Tab panes -->
<div class="tab-content">
    @foreach(\Netcore\Translator\Helpers\TransHelper::getAllLanguages() as $language)
        <div role="tabpanel" class="tab-pane {{ $loop->first ? 'active' : '' }}"
             id="{{ $language->iso_code }}">

            <div class="form-group{{ $errors->has('translations.' . $language->iso_code . '.name') ? ' has-error' : '' }}">
                <label class="control-label">Name</label>
                {!! Form::text('translations['.$language->iso_code.'][name]', trans_model(isset($menu) ? $menu : null, $language, 'name'), ['class' => 'form-control']) !!}
            </div>
        </div>
    @endforeach
</div>
