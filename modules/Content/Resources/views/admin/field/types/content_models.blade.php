<div class="form-control">
    <table class="table table-inside table-nowrap table-hover">
    <thead>
        <tr>
            <td width="15%">{{trans('content::model.name.label')}}</td>
            <td>{{trans('content::model.template.label')}}</td>
            <td width="15%" class="text-center">{{trans('content::model.select.label')}}</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($models as $model)
        <tr>
            <td>
                <strong>{{$model->name}}</strong>
            </td>
            <td>
                @if ($model->template)                
                {field type="template" name="$name.'['.$model->id.'][template]'" value="$model->template ?? null" required="required"}
                @endif
            </td>
            <td class="text-center">
                @if ($model->enabled)
                    {field type="toggle" name="$name.'['.$model->id.'][enabled]'" value="1" checked="checked"}
                @else
                    {field type="toggle" name="$name.'['.$model->id.'][enabled]'" value="1"}
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>  
    </table>
</div>
