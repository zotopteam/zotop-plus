<div class="form-control">
    <table class="table table-sm table-inside table-nowrap table-hover">
        <thead>
            <tr>
                <td width="15%">{{trans('content::model.name.label')}}</td>
                <td>{{trans('content::model.view.label')}}</td>
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
                    @if ($model->view)
                    <z-field type="view" name="$name.'['.$model->id.'][view]'" value="$model->view ?? null"
                        required="required" />
                    @endif
                </td>
                <td class="text-center">
                    <z-field type="toggle" name="$name.'['.$model->id.'][enabled]'" value="$model->enabled" />
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
