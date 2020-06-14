<div class="form-group">
    <label for="--master" class="form-label">{{trans('developer::command.seeder.master.label')}}</label>
    <div class="form-field">
        <z-field type="bool" name="--master" value="0"/>

        @if ($errors->has('--master'))
        <span class="form-help text-error">{{ $errors->first('--master') }}</span>
        @else
        <span class="form-help">{{trans('developer::command.seeder.master.help')}}</span>                     
        @endif
    </div>                      
</div>
