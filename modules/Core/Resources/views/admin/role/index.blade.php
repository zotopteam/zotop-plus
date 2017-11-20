@extends('core::layouts.master')

@section('content')
@include('core::administrator.side')
<div class="main">
    <div class="main-header">        
        <div class="main-title mr-auto">
            {{$title}}
        </div>
        <div class="main-action">
          <a href="{{route('core.role.create')}}" class="btn btn-primary"> <i class="fa fa-plus"></i> {{trans('core::master.create')}}</a>
        </div>           
    </div>
    <div class="main-body scrollable">
        @if($roles->count() == 0)
            <div class="nodata">{{trans('core::master.nodata')}}</div>
        @else
            <table class="table table-nowrap table-hover" >
                <thead>
                <tr>
                    <th class="text-center" width="1%">{{trans('core::master.status')}}</th>
                    <th width="30%">{{trans('core::role.name.label')}}</th>
                    <th>{{trans('core::role.description.label')}}</th>
                    <th width="15%" class="d-none">{{trans('core::master.created_at')}}</th>
                    <th width="15%" class="d-none">{{trans('core::master.updated_at')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td class="text-center">
                            <i class="fa fa-2x {{$role->disabled ? 'fa-times-circle text-error' : 'fa-check-circle text-success'}}"></i>
                        </td>
                        <td>
                            <div class="title text-lg">
                                {{$role->name}}
                            </div>
                            <div class="manage">
                                <a class="manage-item" href="{{route('core.role.edit', $role->id)}}"><i class="fa fa-edit"></i> {{trans('core::master.edit')}}</a>
                                <a class="manage-item js-confirm" href="javascript:;" data-url="{{route('core.role.status', $role->id)}}">
                                    @if($role->disabled)
                                    <i class="fa fa-check-circle"></i> {{trans('core::master.active')}}
                                    @else
                                    <i class="fa fa-times-circle"></i> {{trans('core::master.disable')}}
                                    @endif                                    
                                </a>
                                <a class="manage-item js-delete" href="javascript:;" data-url="{{route('core.role.destroy', $role->id)}}"><i class="fa fa-times"></i> {{trans('core::master.delete')}}</a>
                            </div>

                        </td>
                        <td>{{$role->description}}</td>
                        <td class="d-none">{{$role->created_at}}</td>
                        <td class="d-none">{{$role->updated_at}}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        @endif                       
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="footer-text mr-auto">
            {{trans('core::role.description')}}
        </div>
    </div>
</div>
@endsection

@push('css')

@endpush

@push('js')

@endpush
