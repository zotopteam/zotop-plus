<div class="form-group">
    <label for="--queued" class="form-label">{{trans('developer::command.listener.queued.label')}}</label>
    <div class="form-field">
        <z-field type="bool" name="--queued"/>

        @if ($errors->has('--queued'))
        <span class="form-help text-error">{{ $errors->first('--queued') }}</span>
        @else
        <span class="form-help">{{trans('developer::command.listener.queued.help')}}</span>                     
        @endif
    </div>                      
</div>
