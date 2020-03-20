<div class="form-group">
    <label for="--type" class="form-label">{{trans('developer::command.test.type.label')}}</label>
    <div class="form-field">
        {field type="radiogroup" name="--type" options="Module::data('developer::test.types')" column="1"}

        @if ($errors->has('--type'))
        <span class="form-help text-error">{{ $errors->first('--type') }}</span>
        @else
        <span class="form-help">{{trans('developer::command.test.type.help')}}</span>                     
        @endif
    </div>                      
</div>
