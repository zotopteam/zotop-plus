@extends('core::layouts.master')

@section('content')
@include('block::side')

<div class="main">
    <div class="main-header">
        @if($keywords = request('keywords'))
            <div class="main-back">
                <a href="{{route('block.index',$category->id)}}"><i class="fa fa-angle-left"></i><b>{{trans('core::master.back')}}</b></a>
            </div>
            <div class="main-title mr-auto">
                {{$category->name}}
            </div>                    
            <div class="main-title mr-auto">
                {{trans('core::master.searching', [$keywords])}}
            </div>        
        @else
        <div class="main-title mr-auto">
            {{$category->name}}
        </div>
        <div class="main-action">
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-plus"></i> {{trans('block::block.create')}}
                </button>
                <div class="dropdown-menu dropdown-menu-primary">
                    @foreach(Module::data('block::types') as $key=>$val)
                        <a class="dropdown-item" href="{{route('block.create',[$category->id, $key])}}" title="{{$val['help']}}" data-placement="left">
                            <i class="dropdown-item-icon {{$val['icon']}} fa-fw"></i>
                            <b class="dropdown-item-text">{{$val['name']}}</b>
                        </a>
                    @endforeach
                </div>
            </div>       
        </div>
        @endif

        <div class="main-action">
            {form route="['block.index',$category->id]" class="form-inline form-search" method="get"}
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
        @if($blocks->count() == 0)
            <div class="nodata">{{trans('core::master.nodata')}}</div>
        @else
            {form route="block.sort" action="post"}
            <table class="table table-nowrap table-sortable table-hover">
                <thead>
                <tr>
                    <td class="drag"></td>
                    <th>{{trans('block::block.name')}}</th>
                    <td width="30%" >{{trans('block::block.code.include')}}</td>
                    <td width="20%" class="text-center">{{trans('block::block.type')}}</td>
                    <td>{{trans('core::master.lastmodify')}}</td>
                </tr>
                </thead>
                <tbody>
                @foreach($blocks as $block)
                    <tr>
                        <td class="drag"> <input type="hidden" name="sort[]" value="{{$block->id}}"/> </td>
                        <td>
                            <div class="title">
                                {{$block->name}}
                            </div>
                            <div class="manage">
                                <a class="manage-item" href="{{route('block.data', $block->id)}}">
                                    <i class="fa fa-edit"></i> {{trans('block::block.data.edit')}}
                                </a>                            
                                <a class="manage-item" href="{{route('block.edit', $block->id)}}">
                                    <i class="fa fa-cog"></i> {{trans('block::block.setting')}}
                                </a>
                                <a class="manage-item js-delete" href="javascript:;" data-url="{{route('block.destroy', $block->id)}}">
                                    <i class="fa fa-times"></i> {{trans('core::master.delete')}}
                                </a>
                            </div>
                        </td>
                        <th>
                            <div class="input-group">
                                <input type="text" id="code_include_{{$block->id}}" class="form-control" value="{{$block->code_include}}">
                                <div class="input-group-append">
                                    <button class="btn btn-light btn-copy" type="button" data-clipboard-target="#code_include_{{$block->id}}" data-success="{{trans('core::master.copied')}}" data-toggle="tooltip" title="{{trans('core::master.copy')}}">
                                        <i class="far fa-copy"></i>
                                    </button>
                                </div>
                            </div>                            
                        </th>
                        <td class="text-center" title="{{$block->type}}"> {{$block->type_name}}</td>
                        <td>
                            <b>{{$block->user->username}}</b>
                            <div class="text-sm">{{$block->updated_at}}</div>
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
            {{trans('block::block.about')}}
        </div>
    </div>
</div>
@endsection
