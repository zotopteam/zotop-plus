<div class="card field-card {{$field->disabled ? 'disabled' : ''}}" id="{{$field->id}}">
    <table class="table table-sm table-noborder table-nowrap table-hover">
        <tbody>
            <tr>
                <td width="10%" class="drag"></td>
                <td class="pl-2">
                    <div class="title">
                        {{$field->label}} ({{$field->name}})
                    </div>
                    <div class="manage">
                        <a class="manage-item" href="{{route('content.field.edit', [$model->id, $field->id])}}">
                            <i class="fa fa-edit"></i> {{trans('core::master.edit')}}
                        </a>
                        @if ($field->disabled)                                 
                        <a class="manage-item js-confirm" href="{{route('content.field.status', [$model->id, $field->id])}}">
                            <i class="fa fa-check-circle"></i> {{trans('core::master.enable')}}                                    
                        </a>                                                                
                        @if(!$field->system)
                        <a class="manage-item js-delete" href="javascript:;" data-url="{{route('content.field.destroy', [$model->id, $field->id])}}">
                            <i class="fa fa-times"></i> {{trans('core::master.delete')}}
                        </a>
                        @endif
                        @else
                        <a class="manage-item js-confirm" href="{{route('content.field.status', [$model->id, $field->id])}}">
                            <i class="fa fa-times-circle"></i> {{trans('core::master.disable')}}
                        </a>                                     
                        @endif
                    </div>                    
                </td>
                <td width="30%">
                    {{$field->type_name}}
                </td>
                <td width="10%">
                    @if(array_get($field->settings, 'required'))
                    <i class="fa fa-check-circle fa-2x text-success" title="{{trans('content::field.required.label')}}" data-toggle="tooltip"></i>
                    @else
                    @endif                    
                </td>
            </tr>
        </tbody>
    </table>                                                
</div>
