@extends('core::layouts.master')

@section('content')

<div class="main">
    <div class="main-header">
        <div class="main-back">
            <a href="{{route('block.index')}}"><i class="fa fa-angle-left"></i><b>{{trans('core::master.back')}}</b></a>
        </div>
        <div class="main-title mx-auto">
            {{$title}}
        </div>
        <div class="main-action">
            <a href="javascript:;" class="btn btn-primary js-open" data-url="{{route('block.category.create')}}" data-width="800" data-height="300">
                <i class="fa fa-plus"></i> {{trans('core::master.create')}}
            </a>
        </div>        
    </div>
    <div class="main-body scrollable">
        @if($categories->count() == 0)
            <div class="nodata">{{trans('block::category.nodata')}}</div>
        @else
            {form route="block.category.sort" action="post"}
            <table class="table table-nowrap table-sortable table-hover">
                <thead>
                <tr>
                    <td class="drag"></td>
                    <th width="30%">{{trans('block::category.name')}}</th>
                    <td>{{trans('block::category.description')}}</td>
                </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td class="drag"> <input type="hidden" name="sort[]" value="{{$category->id}}"/> </td>
                        <td>
                            <div class="title text-lg">
                                {{$category->name}}
                            </div>
                            <div class="manage">
                                <a class="manage-item" href="{{route('block.index', $category->id)}}">
                                    <i class="fa fa-list-alt"></i> {{trans('block::category.manage')}}
                                </a>                            
                                <a class="manage-item js-open" href="javascript:;"  data-url="{{route('block.category.edit', $category->id)}}" data-width="800" data-height="300">
                                    <i class="fa fa-edit"></i> {{trans('core::master.edit')}}
                                </a>
                                <a class="manage-item js-delete" href="javascript:;" data-url="{{route('block.category.destroy', $category->id)}}">
                                    <i class="fa fa-times"></i> {{trans('core::master.delete')}}
                                </a>
                            </div>
                        </td>
                        <td>{{$category->description}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {/form}
        @endif                       
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="footer-text mr-auto">
            {{trans('block::category.about')}}
        </div>
    </div>
</div>
@endsection
