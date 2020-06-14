<div class="form-group">
    <label for="--sync" class="form-label">{{trans('developer::command.job.sync.label')}}</label>
    <div class="form-field">
        <z-field type="bool" name="--sync" value="0"/>

        @if ($errors->has('--sync'))
        <span class="form-help text-error">{{ $errors->first('--sync') }}</span>
        @else
        <span class="form-help">{{trans('developer::command.job.sync.help')}}</span>                     
        @endif
    </div>                      
</div>
