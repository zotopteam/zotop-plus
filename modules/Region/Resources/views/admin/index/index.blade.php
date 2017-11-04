@extends('core::layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-title">
            {{$title}}            
        </div>
        <div class="main-action mr-auto">
            <nav class="breadcrumb">               
                <a class="breadcrumb-item" href="{{route('region.index')}}">{{trans('region::module.root')}}</a>
                @foreach($parents as $nav)
                <a class="breadcrumb-item" href="{{route('region.index', $nav['id'])}}">{{$nav['title']}}</a> 
                @endforeach
                @if($parent)<a class="breadcrumb-item" href="{{route('region.index', $parent['id'])}}">{{$parent['title']}}</a> @endif
            </nav>
        </div>
        <div class="main-action">
            <a href="javascript:;" data-url="{{route('region.create', $parent_id)}}" data-width="800" data-height="300" class="btn btn-primary js-open"> <i class="fa fa-plus"></i> {{trans('region::module.add')}}</a>
        </div>        
    </div>
    <div class="main-body scrollable">
        
        {form route="region.sort" action="post"}
        <input type="hidden" name="parent_id" value="{{$parent_id}}"/>
        <table class="table table-nowrap table-sortable">
            <thead>
                <tr>
                    <td class="drag"></td>
                    <td>{{trans('core::modules.name.label')}}</td>
                </tr>
            </thead>        
            <tbody>  
            @foreach($regions as $region)
                <tr class="item {{$region->disabled?'disabled':'active'}}">
                    <td class="drag"> <input type="hidden" name="sort_id[]" value="{{$region->id}}"/> </td>
                	<td><div class="title"><b class="text-lg">{{$region->title}}</b></div>
                        <div class="manage">
                            <a href="{{route('region.index', $region->id)}}" class="manage-item"><i class="fa fa-child"></i> {{trans('region::module.child')}}</a>
                            <a href="javascript:;" data-url="{{route('region.edit', $region->id)}}" data-width="800" data-height="300" class="manage-item js-open"><i class="fa fa-edit"></i> {{trans('region::module.edit')}}</a>

                            @if($region->disabled)
                                <a href="javascript:;" data-url="{{route('region.enable', $region->id)}}" class="manage-item js-confirm"><i class="fa fa-check-circle"></i> {{trans('core::master.active')}}</a>
                            @else
                                <a href="javascript:;" data-url="{{route('region.disable', $region->id)}}" class="manage-item js-confirm"><i class="fa fa-times-circle"></i> {{trans('core::master.disable')}}</a>
                            @endif

                            <a href="javascript:;" data-url="{{route('region.destroy', $region->id)}}" data-confirm="{{trans('region::module.delete.confirm', [$region->title])}}"  class="manage-item js-confirm"><i class="fa fa-trash"></i> {{trans('region::module.delete')}}</a>

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
            <i class="fa fa-circle-o fa-fw text-primary"></i> {{trans('developer::module.description')}}
        </div>
    </div>
</div>
@endsection

@push('css')

@endpush

@push('js')

@endpush
