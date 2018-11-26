@extends('core::layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-back">
            <a href="{{route('block.data',$block->id)}}"><i class="fa fa-angle-left"></i><b>{{trans('core::master.back')}}</b></a>
        </div>    
        <div class="main-title mr-auto">
            {{$block->name}} - {{$title}}
        </div>
        <div class="main-action">

        </div>        
    </div>
    <div class="main-body scrollable">
        @if($datalists->count() == 0)
            <div class="nodata">{{trans('core::master.nodata')}}</div>
        @else
            <table class="table table-nowrap table-hover">
                <thead>
                <tr>
                    <td>{{trans('block::datalist.title')}}</td>
                    <td width="10%">{{trans('core::master.lastmodify')}}</td>
                </tr>
                </thead>
                <tbody>
                @foreach($datalists as $datalist)
                    <tr>
                        <td>
                            @if ($datalist->image_preview)
                                <a href="javascript:;" class="js-image" data-url="{{preview($datalist->image_preview)}}" data-title="{{$datalist->title}}">
                                    <div class="image-preview bg-image-preview text-center float-left mr-3">
                                        <img src="{{preview($datalist->image_preview, 64, 64)}}">
                                    </div>
                                </a>
                            @endif                        
                            <div class="title">
                                {{$datalist->title}}
                            </div>
                            <div class="manage">
                                <a class="manage-item js-confirm" href="javascript:;"  data-url="{{route('block.datalist.republish', $datalist->id)}}">
                                    <i class="fas fa-undo"></i> {{trans('block::datalist.republish')}}
                                </a>                                
                                <a class="manage-item js-open" href="javascript:;"  data-url="{{route('block.datalist.edit', $datalist->id)}}" data-width="800" data-height="400">
                                    <i class="fa fa-edit"></i> {{trans('core::master.edit')}}
                                </a>
                                <a class="manage-item js-delete" href="javascript:;" data-url="{{route('block.datalist.destroy', $datalist->id)}}">
                                    <i class="fa fa-times"></i> {{trans('core::master.delete')}}
                                </a>
                            </div>
                        </td>
                        <td>
                            <b>{{$datalist->user->username}}</b>
                            <div class="text-sm">{{$datalist->updated_at}}</div>                            
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        @endif                       
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="footer-text mr-auto">
        </div>

        {{ $datalists->links('core::pagination.default') }}
    </div>
</div>
@endsection
