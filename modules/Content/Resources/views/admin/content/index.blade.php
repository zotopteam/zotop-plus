@extends('core::layouts.master')

@section('content')
@include('content::content.side')

<div class="main">
    <div class="main-header">
        @if($keywords = request('keywords'))
            <div class="main-back">
                <a href="{{route('content.content.index',$parent->id)}}"><i class="fa fa-angle-left"></i><b>{{trans('core::master.back')}}</b></a>
            </div>
            <div class="main-title mr-auto">
                {{$parent->title}}
            </div>                    
            <div class="main-title mr-auto">
                {{trans('core::master.searching', [$keywords])}}
            </div>        
        @else
        <div class="main-title mr-auto">
            {{$parent->title}}
        </div>
        <div class="main-action">
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-plus"></i> {{trans('content::content.create')}}
                </button>
                <div class="dropdown-menu dropdown-menu-primary">
                    @foreach(Module::data('content::menu.create', get_defined_vars()) as $model)
                        <a class="dropdown-item" href="{{route('content.content.create',[$parent->id, $model->id])}}" title="{{$model->description}}" data-placement="left">
                            <i class="dropdown-item-icon {{$model->icon}} fa-fw"></i>
                            <b class="dropdown-item-text">{{$model->name}}</b>
                        </a>
                    @endforeach
                </div>
            </div>       
        </div>
        @endif
        <div class="main-action">
            {form route="['content.content.index',$parent->id]" class="form-inline form-search" method="get"}
                <div class="input-group">
                    <input name="keywords" value="{{$keywords}}" class="form-control" type="search" placeholder="{{trans('core::master.keywords.placeholder')}}" required="required" aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"> <i class="fa fa-fw fa-search"></i> </button>
                    </div>
                </div>
            {/form}
        </div>              
    </div>
    <div class="main-body scrollable">
        @if($contents->count() == 0)
            <div class="nodata">{{trans('core::master.nodata')}}</div>
        @else
            <table class="table table-nowrap table-hover">
                <thead>
                <tr>
                    <th width="1%">{{trans('content::content.title.label')}}</th>
                    <td></td>
                </tr>
                </thead>
                <tbody>
                @foreach($contents as $content)
                    <tr>
                        <td>
                            <div class="title text-lg">
                                {{$content->title}}
                            </div>
                            <div class="manage">
                                <a class="manage-item" href="{{route('content.content.edit', $content->id)}}">
                                    <i class="fa fa-edit"></i> {{trans('core::master.edit')}}
                                </a>
                                <a class="manage-item js-delete" href="javascript:;" data-url="{{route('content.content.destroy', $content->id)}}">
                                    <i class="fa fa-times"></i> {{trans('core::master.delete')}}
                                </a>
                            </div>
                        </td>
                        <td></td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        @endif                       
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="footer-text mr-auto">
            {{trans('content::content.description')}}
        </div>

        {{ $contents->links('core::pagination.default') }}
    </div>
</div>
@endsection
