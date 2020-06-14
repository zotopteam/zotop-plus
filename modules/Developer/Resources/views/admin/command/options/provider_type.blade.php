<div class="form-group">
    <label for="--type" class="form-label">{{trans('developer::command.provider.type.label')}}</label>
    <div class="form-field">
        <z-field type="radiogroup" name="--type" options="Module::data('developer::provider.types')" column="1"/>

        @if ($errors->has('type'))
        <span class="form-help text-error">{{ $errors->first('type') }}</span>
        @else
        <span class="form-help">{{trans('developer::command.provider.type.help')}}</span>                     
        @endif
    </div>                      
</div>
