<div class="form-group">
    <label for="--view" class="form-label">{{trans('developer::command.component.view.label')}}</label>
    <div class="form-field">
        {field type="checkboxgroup" name="--view" options="Module::data('developer::component.views')" value="['backend']" column="1"}

        @if ($errors->has('--view'))
        <span class="form-help text-error">{{ $errors->first('--view') }}</span>
        @else
        <span class="form-help">{{trans('developer::command.component.view.help')}}</span>                     
        @endif
    </div>                      
</div>
