@extends('core::layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>
        <div class="main-action">
            <a href="{{route('$LOWER_NAME$.$LOWER_CONTROLLER$.create')}}" class="btn btn-primary">
                <i class="fa fa-plus"></i> {{trans('core::master.create')}}
            </a>
        </div>        
    </div>
    <div class="main-body scrollable">
        @if($$PLURAL_MODEL$->count() == 0)
            <div class="nodata">{{trans('core::master.nodata')}}</div>
        @else
            <table class="table table-nowrap table-hover">
                <thead>
                <tr>
                    <th width="1%">{{trans('$LOWER_NAME$::$LOWER_CONTROLLER$.title.label')}}</th>
                    <td></td>
                </tr>
                </thead>
                <tbody>
                @foreach($$PLURAL_MODEL$ as $$LOWER_MODEL$)
                    <tr>
                        <td>
                            <div class="title text-lg">
                                {{$$LOWER_MODEL$->title}}
                            </div>
                            <div class="manage">
                                <a class="manage-item" href="{{route('$LOWER_NAME$.$LOWER_CONTROLLER$.edit', $$LOWER_MODEL$->id)}}">
                                    <i class="fa fa-edit"></i> {{trans('core::master.edit')}}
                                </a>
                                <a class="manage-item js-delete" href="javascript:;" data-url="{{route('$LOWER_NAME$.$LOWER_CONTROLLER$.destroy', $$LOWER_MODEL$->id)}}">
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
            {{trans('$LOWER_NAME$::$LOWER_CONTROLLER$.description')}}
        </div>

        {{ $$PLURAL_MODEL$->links('core::pagination.default') }}
    </div>
</div>
@endsection
