@extends('core::layouts.master')

@section('content')

<div class="main">
    <div class="main-header">
        <div class="main-back">
            <a href="{{route('content.content.index')}}"><i class="fa fa-angle-left"></i><b>{{trans('core::master.back')}}</b></a>
        </div>    
        <div class="main-title mr-auto">
            {{$title}}
        </div>
        <div class="main-action">
            <a href="{{route('content.model.create')}}" class="btn btn-primary">
                <i class="fa fa-plus"></i> {{trans('content::model.create')}}
            </a>
        </div>        
    </div>
    <div class="main-body scrollable">
        @if($models->count() == 0 && $import->count() == 0)
            <div class="nodata">{{trans('core::master.nodata')}}</div>
        @else
            {form route="content.model.sort" method="post"}
            <table class="table table-nowrap table-sortable table-hover">
                <thead>
                <tr>
                    <td class="drag"></td>
                    <td width="1%" class="text-center"></td>
                    <td width="35%">{{trans('content::model.name.label')}}</td>
                    <td>{{trans('content::model.id.label')}}</td>
                    <td>{{trans('content::model.description.label')}}</td>
                    <td width="10%"></td>
                </tr>
                </thead>
                <tbody>
                @foreach($models as $model)
                    <tr class="item {{$model->disabled ? 'disabled' : ''}}">
                        <td class="drag">
                            <input type="hidden" name="ids[]" value="{{$model->id}}">
                        </td>
                        <td class="text-center">
                            @if($model->disabled)
                            <i class="fa fa-2x fa-times-circle text-muted d-none"></i>
                            <i class="{{$model->icon}} fa-2x text-muted"></i>
                            @else
                            <i class="{{$model->icon}} fa-2x text-success"></i>
                            <i class="fa fa-2x fa-check-circle text-success d-none"></i>
                            @endif
                             
                        </td>
                        <td>
                            <div class="title mb-1">
                                {{$model->name}}
                            </div>
                            <div class="manage">
                                <a class="manage-item" href="{{route('content.model.edit', $model->id)}}">
                                    <i class="fa fa-cog"></i> {{trans('content::model.edit')}}
                                </a>
                                <a class="manage-item" href="{{route('content.field.index', $model->id)}}">
                                    <i class="fa fa-bars"></i> {{trans('content::field.title')}}
                                </a>
                                <a class="manage-item" href="{{route('content.model.export', $model->id)}}">
                                    <i class="fa fa-file-export"></i> {{trans('content::model.export')}}
                                </a>
                                @if ($model->disabled)                                 
                                <a class="manage-item js-confirm" href="{{route('content.model.status', $model->id)}}">
                                    <i class="fa fa-check-circle"></i> {{trans('core::master.enable')}}                                    
                                </a>                                                                
                                <a class="manage-item js-delete" href="javascript:;" data-url="{{route('content.model.destroy', $model->id)}}">
                                    <i class="fa fa-times"></i> {{trans('core::master.delete')}}
                                </a>
                                @else
                                <a class="manage-item js-confirm" href="{{route('content.model.status', $model->id)}}">                                    
                                    <i class="fa fa-times-circle"></i> {{trans('core::master.disable')}}
                                </a>                                     
                                @endif
                            </div>
                        </td>
                        <td>{{$model->id}}</td>
                        <td>{{$model->description}}</td>
                        <td>
                            <strong>{{$model->user->username}}</strong>
                            <div>{{$model->updated_at}}</div>
                        </td>
                    </tr>
                @endforeach
                @foreach($import as $file=>$model)
                    <tr class="item disabled ui-sortable-disabled">
                        <td></td>
                        <td class="text-center">
                            <i class="{{$model->icon}} fa-2x text-muted"></i>
                        </td>
                        <td>
                            <div class="title mb-1">
                                {{$model->name}}
                            </div>
                            <div class="manage">
                                <a class="manage-item js-confirm" href="{{route('content.model.import', ['file'=>$file])}}">
                                    <i class="fa fa-file-import"></i> {{trans('content::model.import')}}
                                </a>
                            </div>
                        </td>
                        <td>{{$model->id}}</td>
                        <td>{{$model->description}}</td>
                        <td>

                        </td>
                    </tr>                
                @endforeach
                </tbody>
            </table>
            {/form}
        @endif                       
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="footer-text mr-auto">
            {{trans('content::model.description')}}
        </div>
    </div>
</div>
@endsection
