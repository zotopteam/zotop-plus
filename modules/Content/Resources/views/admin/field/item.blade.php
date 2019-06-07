<div class="field-card {{$field->width}}" id="{{$field->id}}">
    <div class="card {{$field->disabled ? 'disabled' : ''}}">
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
                                <i class="fa fa-edit"></i> {{trans('master.edit')}}
                            </a>
                            @if ($field->disabled)                                 
                            <a class="manage-item js-confirm" href="{{route('content.field.status', [$model->id, $field->id])}}">
                                <i class="fa fa-check-circle"></i> {{trans('master.enable')}}                                    
                            </a>                                                                
                            @if(!$field->system)
                            <a class="manage-item js-delete" href="javascript:;" data-url="{{route('content.field.destroy', [$model->id, $field->id])}}">
                                <i class="fa fa-times"></i> {{trans('master.delete')}}
                            </a>
                            @endif
                            @else
                            <a class="manage-item js-confirm" href="{{route('content.field.status', [$model->id, $field->id])}}">
                                <i class="fa fa-times-circle"></i> {{trans('master.disable')}}
                            </a>                                     
                            @endif
                        </div>                    
                    </td>
                    <td width="30%" class="typename">
                        {{$field->type_name}}
                    </td>
                    <td width="10%" class="required">
                        @if(array_get($field->settings, 'required'))
                        <i class="fa fa-check-circle fa-2x text-success" title="{{trans('content::field.required.label')}}" data-toggle="tooltip"></i>
                        @else
                        @endif                    
                    </td>
                    <td width="10%" class="width text-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-light" title="{{trans('content::field.width.label')}}" data-toggle="tooltip">
                                {{str_after($field->width,'-')}}%
                            </button>
                            <button type="button" class="btn btn-sm btn-light dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item js-post" href="{{route('content.field.change',[$field->id])}}" data-post='{"width":"w-25"}'>
                                    <i class="fa fa-arrows-alt-h fa-fw"></i>  25%
                                </a>
                                <a class="dropdown-item js-post" href="{{route('content.field.change',[$field->id])}}" data-post='{"width":"w-33"}'>
                                    <i class="fa fa-arrows-alt-h fa-fw"></i> 33%
                                </a>
                                <a class="dropdown-item js-post" href="{{route('content.field.change',[$field->id])}}" data-post='{"width":"w-50"}'>
                                    <i class="fa fa-arrows-alt-h fa-fw"></i> 50%
                                </a>
                                <a class="dropdown-item js-post" href="{{route('content.field.change',[$field->id])}}" data-post='{"width":"w-66"}'>
                                    <i class="fa fa-arrows-alt-h fa-fw"></i> 66%
                                </a>
                                <a class="dropdown-item js-post" href="{{route('content.field.change',[$field->id])}}" data-post='{"width":"w-75"}'>
                                    <i class="fa fa-arrows-alt-h fa-fw"></i> 75%
                                </a>
                                <a class="dropdown-item js-post" href="{{route('content.field.change',[$field->id])}}" data-post='{"width":"w-100"}'>
                                    <i class="fa fa-arrows-alt-h fa-fw"></i> 100%
                                </a>
                            </div>
                        </div>                        
                    </td>
                </tr>
            </tbody>
        </table>                                                
    </div>
</div>
