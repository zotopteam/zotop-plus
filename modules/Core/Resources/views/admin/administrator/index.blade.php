@extends('core::layouts.master')

@section('content')

@include('core::administrator.side')

<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>
        <div class="main-action">
            <a href="{{route('core.administrator.create')}}" class="btn btn-primary">
                <i class="fa fa-plus"></i> {{trans('core::master.create')}}
            </a>
        </div>        
    </div>
    <div class="main-body scrollable">
        @if($users->count() == 0)
            <div class="nodata">{{trans('core::master.nodata')}}</div>
        @else
            <table class="table table-nowrap table-hover" >
                <thead>
                <tr>
                    <th class="text-center" width="1%">{{trans('core::master.status')}}</th>
                    <th>{{trans('core::administrator.username.label')}}</th>
                    <th>{{trans('core::administrator.nickname.label')}}</th>
                    <th>{{trans('core::administrator.mobile.label')}}</th>
                    <th width="15%">{{trans('core::administrator.email.label')}}</th>
                    <th width="15%">{{trans('core::administrator.login_at.label')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="text-center">
                            <i class="fa fa-2x {{$user->disabled ? 'fa-times-circle text-error' : 'fa-check-circle text-success'}}"></i>
                        </td>
                        <td>
                            <div class="title text-lg">
                                {{$user->username}}
                            </div>
                            @if ($user->isSuper())
                            <div class="text-sm text-important">{{trans('core::administrator.model.super')}}</div>
                            @else
                            <div class="text-sm text-muted">
                                @foreach ($user->roles as $role)
                                    <span>{{$role->name}}</span>
                                @endforeach
                            </div>
                            @endif
                            <div class="manage">
                                <a class="manage-item" href="{{route('core.administrator.edit', $user->id)}}"><i class="fa fa-edit"></i> {{trans('core::master.edit')}}</a>
                                @if (! $user->isSuper())
                                <a class="manage-item js-confirm" href="javascript:;" data-url="{{route('core.administrator.status', $user->id)}}">
                                    @if($user->disabled)
                                    <i class="fa fa-check-circle"></i> {{trans('core::master.active')}}
                                    @else
                                    <i class="fa fa-times-circle"></i> {{trans('core::master.disable')}}
                                    @endif                                    
                                </a>
                                <a class="manage-item js-delete" href="javascript:;" data-url="{{route('core.administrator.destroy', $user->id)}}"><i class="fa fa-times"></i> {{trans('core::master.delete')}}</a>
                                @endif
                            </div>

                        </td>
                        <td>{{$user->nickname}}</td>
                        <td>{{$user->mobile}}</td>
                        <td>{{$user->email}}</td>
                        <td>
                            <div class="text-sm">{{$user->login_ip}}</div>
                            <div class="text-sm">{{$user->login_at}}</div>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        @endif                       
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="footer-text mr-auto">
            {{trans('core::administrator.description')}}
        </div>

        {{ $users->links('core::pagination.default') }}
    </div>
</div>
@endsection
