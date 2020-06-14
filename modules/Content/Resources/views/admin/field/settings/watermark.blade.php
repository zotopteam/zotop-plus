    <div class="form-group row">
        <label for="settings-watermark" class="col-2 col-form-label">{{trans('content::field.watermark.label')}}</label>
        <div class="col-8">
            <z-field type="enable" name="settings[watermark]" value="$field->settings->watermark ?? $type->settings->watermark ?? 0"/>

            <span class="form-help">{{trans_find('content::field.watermark.help')}}</span>                  
        </div>
    </div>  
