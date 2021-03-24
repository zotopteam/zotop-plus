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
            <a class="btn btn-success" href="{{route('navbar.field.edit', $field->id)}}">
                <i class="fa fa-edit"></i> {{trans('master.edit')}}
            </a>
            <a class="btn btn-warning js-delete" href="javascript:;" data-url="{{route('navbar.field.destroy', $field->id)}}">
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
                    {{$field->id ?? '[ID]'}}
                </td>
            </tr>
                        <tr>
                <td width="15%">
                    {{trans('navbar::field.navbar_id.label')}}
                </td>
                <td>
                    {{$field->navbar_id}}
                </td>
            </tr>

            <tr>
                <td width="15%">
                    {{trans('navbar::field.parent_id.label')}}
                </td>
                <td>
                    {{$field->parent_id}}
                </td>
            </tr>

            <tr>
                <td width="15%">
                    {{trans('navbar::field.label.label')}}
                </td>
                <td>
                    {{$field->label}}
                </td>
            </tr>

            <tr>
                <td width="15%">
                    {{trans('navbar::field.type.label')}}
                </td>
                <td>
                    {{$field->type}}
                </td>
            </tr>

            <tr>
                <td width="15%">
                    {{trans('navbar::field.name.label')}}
                </td>
                <td>
                    {{$field->name}}
                </td>
            </tr>

            <tr>
                <td width="15%">
                    {{trans('navbar::field.default.label')}}
                </td>
                <td>
                    {{$field->default}}
                </td>
            </tr>

            <tr>
                <td width="15%">
                    {{trans('navbar::field.settings.label')}}
                </td>
                <td>
                    {{$field->settings}}
                </td>
            </tr>

            <tr>
                <td width="15%">
                    {{trans('navbar::field.help.label')}}
                </td>
                <td>
                    {{$field->help}}
                </td>
            </tr>

            <tr>
                <td width="15%">
                    {{trans('navbar::field.sort.label')}}
                </td>
                <td>
                    {{$field->sort}}
                </td>
            </tr>

            <tr>
                <td width="15%">
                    {{trans('navbar::field.disabled.label')}}
                </td>
                <td>
                    {{$field->disabled}}
                </td>
            </tr>

        </table>

    </div><!-- main-body -->
</div>
@endsection
