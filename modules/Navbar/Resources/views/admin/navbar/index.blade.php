@extends('layouts.master')

@section('content')
    <div class="main">
        <div class="main-header">
            <div class="main-back">
                <a href="{{route('navbar.item.index', 0)}}">
                    <i class="fa fa-angle-left"></i><b>{{trans('master.back')}}</b>
                </a>
            </div>
            <div class="main-title mr-auto">
                {{$title}}
            </div>
            <div class="main-action">
                <a href="{{route('navbar.navbar.create')}}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> {{trans('master.create')}}
                </a>
            </div>
            <x-search/>
        </div>
        <div class="main-body scrollable">
            @if($navbars->count() == 0)
                <div class="nodata">{{trans('master.nodata')}}</div>
            @else
                <z-form route="navbar.navbar.sort" action="post">
                    <table class="table table-nowrap table-sortable table-hover">
                        <thead>
                        <tr>
                            <th class="drag"></th>
                            <th class="text-center" width="1%">{{trans('master.id')}}</th>
                            <th>{{trans('navbar::navbar.title.label')}}</th>
                            <th>{{trans('navbar::navbar.slug.label')}}</th>
                            <th width="15%" class="text-center">{{trans('navbar::navbar.enabled.label')}}</th>
                            <th width="15%">{{trans('master.operate')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($navbars as $navbar)
                            <tr>
                                <td class="drag"><input type="hidden" name="ids[]" value="{{$navbar->id}}"/></td>
                                <td class="text-center">
                                    {{$navbar->id}}
                                </td>
                                <td>
                                    {{$navbar->title}}
                                </td>
                                <td>
                                    {{$navbar->slug}}
                                </td>
                                <td class="text-center">
                                    <x-status-icon :status="!$navbar->disabled"></x-status-icon>
                                </td>
                                <td class="manage">
                                    <a class="manage-item" href="{{route('navbar.item.index', $navbar->id)}}">
                                        <i class="fa fa-list"></i> {{trans('navbar::item.title')}}
                                        （{{$navbar->item_count}}）
                                    </a>
                                    <a class="manage-item" href="{{route('navbar.navbar.edit', $navbar->id)}}">
                                        <i class="fa fa-edit"></i> {{trans('master.edit')}}
                                    </a>
                                    <a class="manage-item js-delete" href="javascript:;"
                                       data-url="{{route('navbar.navbar.destroy', $navbar->id)}}">
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
