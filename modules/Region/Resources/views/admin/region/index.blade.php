@extends('core::layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-title">
            {{$title}}            
        </div>
        @if($parents->count())
        <div class="main-action mr-auto">
            <nav class="breadcrumb">    
                <a class="breadcrumb-item" href="{{route('region.index')}}">{{trans('region::region.root')}}</a>
                @foreach($parents as $p)
                <a class="breadcrumb-item" href="{{route('region.index', $p->id)}}">{{$p->title}}</a> 
                @endforeach
            </nav>
        </div>
        @endif
        <div class="main-action ml-auto">
            <a href="javascript:;" data-url="{{route('region.create', $parent_id)}}" data-width="800" data-height="300" class="btn btn-primary js-open"> <i class="fa fa-plus"></i> {{trans('region::region.create')}}</a>
        </div>        
    </div>
    <div class="main-body scrollable">
        
        {form route="region.sort" action="post"}
        <input type="hidden" name="parent_id" value="{{$parent_id}}"/>
        <table class="table table-nowrap table-sortable table-hover">
            <thead>
                <tr>
                    <td class="drag"></td>
                    <td>{{trans('region::region.state')}}</td>
                    <td>{{trans('region::region.name')}}</td>
                </tr>
            </thead>        
            <tbody>  
            @foreach($regions as $region)
                <tr class="item {{$region->disabled ? 'disabled' : 'active'}}">
                    <td class="drag"> <input type="hidden" name="sort_id[]" value="{{$region->id}}"/> </td>
                    <td class="state" width="2%">
                        @if($region->disabled)
                            <i class="fa fa-times-circle fa-2x text-muted" title="{{trans('core::master.disabled')}}"></i>
                        @else
                            <i class="fa fa-check-circle fa-2x text-success" title="{{trans('core::master.actived')}}"></i>
                        @endif                        
                    </td>
                	<td>
                        <div class="title text-lg">{{$region->title}}</div>
                        <div class="manage">
                            <a href="{{route('region.index', $region->id)}}" class="manage-item"><i class="fa fa-sitemap"></i> {{trans('region::region.child')}}</a>
                            <a href="javascript:;" data-url="{{route('region.edit', $region->id)}}" data-width="800" data-height="300" class="manage-item js-open"><i class="fa fa-edit"></i> {{trans('region::region.edit')}}</a>

                            @if($region->disabled)
                                <a href="javascript:;" data-url="{{route('region.enable', $region->id)}}" class="manage-item js-confirm"><i class="fa fa-check-circle"></i> {{trans('core::master.active')}}</a>
                            @else
                                <a href="javascript:;" data-url="{{route('region.disable', $region->id)}}" class="manage-item js-confirm"><i class="fa fa-times-circle"></i> {{trans('core::master.disable')}}</a>
                            @endif

                            <a href="javascript:;" data-url="{{route('region.destroy', $region->id)}}" data-confirm="{{trans('region::region.destroy.confirm', [$region->title])}}"  class="manage-item js-confirm"><i class="fa fa-trash"></i> {{trans('region::region.destroy')}}</a>

                        </div>
                	</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {/form}

    </div><!-- main-body -->
    <div class="main-footer">
        <div class="footer-text mr-auto">
            <i class="fa fa-circle-o fa-fw text-primary"></i> {{trans('region::region.description')}}
        </div>
    </div>
</div>
@endsection

@push('css')

@endpush

@push('js')

@endpush
