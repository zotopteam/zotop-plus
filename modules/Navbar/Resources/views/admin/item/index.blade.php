@extends('layouts.master')

@section('content')
    @include('navbar::sidebar', ['navbar_id'=>$navbar_id])

    <div class="main">
        <div class="main-header">
            <div class="main-title">
                {{$navbar ? $navbar->title : trans('navbar::navbar.default')}}
            </div>
            <nav class="breadcrumb mr-auto">
                <a class="breadcrumb-item" href="{{route('navbar.item.index', $navbar_id)}}">
                    <i class="fa fa-home"></i>
                </a>
                @if($parents)
                    @foreach($parents as $p)
                        <a class="breadcrumb-item"
                           href="{{route('navbar.item.index', ['navbar_id'=>$navbar_id, 'parent_id'=>$p->id])}}">
                            {{$p->title}}
                        </a>
                    @endforeach
                @endif
            </nav>
            <div class="main-action">
                <a href="{{route('navbar.field.index',['navbar_id'=>$navbar_id, 'parent_id'=>$parent_id])}}"
                   class="btn btn-info">
                    <i class="fa fa-list"></i> {{trans('navbar::field.title')}}
                </a>
                <a href="{{route('navbar.item.create',['navbar_id'=>$navbar_id, 'parent_id'=>$parent_id])}}"
                   class="btn btn-primary">
                    <i class="fa fa-plus"></i> {{trans('master.create')}}
                </a>
            </div>
        </div>
        <div class="main-body scrollable">
            @if($items->count() == 0)
                <div class="nodata">{{trans('master.nodata')}}</div>
            @else
                <z-form :route="['navbar.item.sort', 'navbar_id'=>$navbar_id, 'parent_id'=>$parent_id]" action="post">
                    <table class="table table-nowrap table-sortable table-hover">
                        <thead>
                        <tr>
                            <th class="drag"></th>
                            <th class="text-center" width="1%">{{trans('master.id')}}</th>
                            <th>{{trans('navbar::item.title.label')}}</th>
                            <th>{{trans('navbar::item.link.label')}}</th>
                            <th width="15%" class="text-center">{{trans('navbar::item.enabled.label')}}</th>
                            <th width="15%">{{trans('master.operate')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td class="drag"><input type="hidden" name="ids[]" value="{{$item->id}}"/></td>
                                <td class="text-center">
                                    {{$item->id}}
                                </td>
                                <td>
                                    {{$item->title}}
                                </td>
                                <td>
                                    {{$item->link}}
                                </td>
                                <td class="text-center">
                                    <x-status-icon :status="!$item->disabled"></x-status-icon>
                                </td>
                                <td class="manage">
                                    <a class="manage-item"
                                       href="{{route('navbar.item.index', ['navbar_id'=>$navbar_id, 'parent_id'=>$item->id])}}">
                                        <i class="fa fa-list"></i> {{trans('navbar::item.children')}}
                                        ({{$item->child_count}})
                                    </a>
                                    <a class="manage-item" href="{{route('navbar.item.edit', $item->id)}}">
                                        <i class="fa fa-edit"></i> {{trans('master.edit')}}
                                    </a>
                                    @if($item->disabled)
                                        <a class="manage-item js-post"
                                           href="{{route('navbar.item.enable', $item->id)}}">
                                            <i class="fa fa-check-circle"></i> {{trans('master.enable')}}
                                        </a>
                                    @else
                                        <a class="manage-item js-post"
                                           href="{{route('navbar.item.disable', $item->id)}}">
                                            <i class="fa fa-times-circle"></i> {{trans('master.disable')}}
                                        </a>
                                    @endif
                                    <a class="manage-item js-delete" href="javascript:;"
                                       data-url="{{route('navbar.item.destroy', $item->id)}}">
                                        <i class="fa fa-times"></i> {{trans('master.delete')}}
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </z-form>
            @endif
        </div><!-- main-body -->
    </div>
@endsection
