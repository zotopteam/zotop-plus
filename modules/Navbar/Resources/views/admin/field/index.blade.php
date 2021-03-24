@extends('layouts.master')

@section('content')
    @include('navbar::sidebar', ['navbar_id'=>$navbar_id])

    <div class="main">
        <div class="main-header">
            <div class="main-back">
                <a href="{{route('navbar.item.index', ['navbar_id'=>$navbar_id, 'parent_id'=>$parent_id])}}">
                    <i class="fa fa-angle-left"></i><b>{{trans('master.back')}}</b>
                </a>
            </div>
            <div class="main-title">
                {{$title}}
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
                <a href="{{route('navbar.field.create', ['navbar_id'=>$navbar_id, 'parent_id'=>$parent_id])}}"
                   class="btn btn-primary">
                    <i class="fa fa-plus"></i> {{trans('master.create')}}
                </a>
            </div>
        </div>
        <div class="main-body scrollable">
            @if($fields->count() == 0)
                <div class="nodata">{{trans('master.nodata')}}</div>
            @else
                <z-form :route="['navbar.field.sort', 'navbar_id'=>$navbar_id, 'parent_id'=>$parent_id]" action="post">

                    <table class="table table-nowrap table-sortable table-hover">
                        <thead>
                        <tr>
                            <th class="drag"></th>
                            <th class="text-center" width="1%">{{trans('master.id')}}</th>

                            <th>{{trans('navbar::field.label.label')}}</th>

                            <th>{{trans('navbar::field.type.label')}}</th>

                            <th>{{trans('navbar::field.name.label')}}</th>

                            <th class="text-center">{{trans('navbar::field.enabled.label')}}</th>

                            <th width="15%">{{trans('master.operate')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($fields as $field)
                            <tr>
                                <td class="drag">
                                    <input type="hidden" name="ids[]" value="{{$field->id}}"/>
                                </td>

                                <td class="text-center">
                                    {{$field->id}}
                                </td>

                                <td>
                                    {{$field->label}}
                                </td>

                                <td>
                                    {{$field->type}}
                                </td>

                                <td>
                                    {{$field->name}}
                                </td>

                                <td class="text-center">
                                    <x-status-icon :status="!$field->disabled"></x-status-icon>
                                </td>

                                <td class="manage">
                                    <a class="manage-item" href="{{route('navbar.field.show', $field->id)}}">
                                        <i class="fa fa-eye"></i> {{trans('master.show')}}
                                    </a>
                                    <a class="manage-item" href="{{route('navbar.field.edit', $field->id)}}">
                                        <i class="fa fa-edit"></i> {{trans('master.edit')}}
                                    </a>
                                    @if($field->disabled)
                                        <a class="manage-item js-post"
                                           href="{{route('navbar.field.enable', $field->id)}}">
                                            <i class="fa fa-check-circle"></i> {{trans('master.enable')}}
                                        </a>
                                    @else
                                        <a class="manage-item js-post"
                                           href="{{route('navbar.field.disable', $field->id)}}">
                                            <i class="fa fa-times-circle"></i> {{trans('master.disable')}}
                                        </a>
                                    @endif
                                    <a class="manage-item js-delete" href="javascript:;"
                                       data-url="{{route('navbar.field.destroy', $field->id)}}">
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
