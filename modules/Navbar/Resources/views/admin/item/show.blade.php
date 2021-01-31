@extends('layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-back">
            <a href="{{Request::referer()}}"><i class="fa fa-angle-left"></i><b>{{trans('master.back')}}</b></a>
        </div>
        <div class="main-title mr-auto">
            {{$title}}
        </div>
        <div class="main-action">
            <a class="btn btn-success" href="{{route('navbar.item.edit', $item->id)}}">
                <i class="fa fa-edit"></i> {{trans('master.edit')}}
            </a>
            <a class="btn btn-warning js-delete" href="javascript:;" data-url="{{route('navbar.item.destroy', $item->id)}}">
                <i class="fa fa-times"></i> {{trans('master.delete')}}
            </a>
        </div>
    </div>

    <div class="main-body scrollable">

        <table class="table table-hover">
            <tr>
                <td width="15%">
                    {{trans('master.id')}}
                </td>
                <td>
                    {{$item->id ?? '[ID]'}}
                </td>
            </tr>
                        <tr>
                <td width="15%">
                    {{trans('navbar::item.navbar_id.label')}}
                </td>
                <td>
                    {{$item->navbar_id}}
                </td>
            </tr>

            <tr>
                <td width="15%">
                    {{trans('navbar::item.parent_id.label')}}
                </td>
                <td>
                    {{$item->parent_id}}
                </td>
            </tr>

            <tr>
                <td width="15%">
                    {{trans('navbar::item.title.label')}}
                </td>
                <td>
                    {{$item->title}}
                </td>
            </tr>

            <tr>
                <td width="15%">
                    {{trans('navbar::item.link.label')}}
                </td>
                <td>
                    {{$item->link}}
                </td>
            </tr>

            <tr>
                <td width="15%">
                    {{trans('navbar::item.custom.label')}}
                </td>
                <td>
                    {{$item->custom}}
                </td>
            </tr>

            <tr>
                <td width="15%">
                    {{trans('navbar::item.sort.label')}}
                </td>
                <td>
                    {{$item->sort}}
                </td>
            </tr>

            <tr>
                <td width="15%">
                    {{trans('navbar::item.status.label')}}
                </td>
                <td>
                    {{$item->status}}
                </td>
            </tr>

        </table>

    </div><!-- main-body -->
</div>
@endsection
