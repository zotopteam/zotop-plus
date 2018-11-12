    <div class="form-group row">
        <label for="rows" class="col-2 col-form-label">{{trans('content::field.rows.label')}}</label>
        <div class="col-4">
            <div class="input-group">
                {field type="number" name="settings[rows]" value="$field->settings->rows ?? 3" min="2"}
                <div class="input-group-append"><span class="input-group-text">{{trans('content::field.rows.unit')}}</span></div>
            </div>                     
        </div>
    </div>  
