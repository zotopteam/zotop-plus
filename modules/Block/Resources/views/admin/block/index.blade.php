@extends('core::layouts.master')

@section('content')
@include('block::side')

<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$category->name}}
        </div>
        <div class="main-action">
            <a href="{{route('block.create')}}" class="btn btn-primary">
                <i class="fa fa-plus"></i> {{trans('core::master.create')}}
            </a>
        </div>        
    </div>
    <div class="main-body scrollable">
        @if($blocks->count() == 0)
            <div class="nodata">{{trans('core::master.nodata')}}</div>
        @else
            <table class="table table-nowrap table-hover">
                <thead>
                <tr>
                    <th width="1%">{{trans('block::block.title.label')}}</th>
                    <td></td>
                </tr>
                </thead>
                <tbody>
                @foreach($blocks as $block)
                    <tr>
                        <td>
                            <div class="title text-lg">
                                {{$block->title}}
                            </div>
                            <div class="manage">
                                <a class="manage-item" href="{{route('block.block.edit', $block->id)}}">
                                    <i class="fa fa-edit"></i> {{trans('core::master.edit')}}
                                </a>
                                <a class="manage-item js-delete" href="javascript:;" data-url="{{route('block.block.destroy', $block->id)}}">
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
            {{trans('block::block.description')}}
        </div>

        {{ $blocks->links('core::pagination.default') }}
    </div>
</div>
@endsection
