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
                    @if ($model->viewValue)
                        <z-field type="view" :name="$model->viewName" :value="$model->viewValue" required="required"/>
                    @endif
                </td>
                <td class="text-center">
                    <z-field type="toggle" :name="$model->enabledName" :value="$model->enabledValue"/>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
