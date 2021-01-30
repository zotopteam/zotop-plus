<div class="form-group">
    <label for="--table" class="form-label">{{trans('developer::command.request.table.label')}}</label>
    <div class="form-field">
        <z-field type="text" name="--table"/>

        @if ($errors->has('--table'))
            <span class="form-help text-error">{{ $errors->first('--table') }}</span>
        @else
            <span class="form-help">{{trans('developer::command.request.table.help')}}</span>
        @endif
    </div>
</div>
