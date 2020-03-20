<div class="form-group">
    <label for="--event" class="form-label">{{trans('developer::command.listener.event.label')}}</label>
    <div class="form-field">
        {field type="text" name="--event"}

        @if ($errors->has('--event'))
        <span class="form-help text-error">{{ $errors->first('--event') }}</span>
        @else
        <span class="form-help">{{trans('developer::command.listener.event.help')}}</span>                     
        @endif
    </div>                      
</div>
