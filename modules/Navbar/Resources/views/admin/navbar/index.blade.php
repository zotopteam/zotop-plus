@extends('layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>
        <div class="main-action">
            <a href="{{route('navbar.navbar.create')}}" class="btn btn-primary">
                <i class="fa fa-plus"></i> {{trans('master.create')}}
            </a>
        </div>        
    </div>
    <div class="main-body scrollable">
        @if($navbars->count() == 0)
            <div class="nodata">{{trans('master.nodata')}}</div>
        @else
            <table class="table table-nowrap table-hover" >
                <thead>
                    <tr>
                        <th class="text-center" width="1%">{{trans('master.id')}}</th>
                        <th>{{trans('navbar::navbar.title.label')}}</th>
                        <th width="15%">{{trans('master.created_at')}}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($navbars as $navbar)
                    <tr>
                        <td class="text-center">
                            {{$navbar->id ?? '[ID]'}}
                        </td>
                        <td>
                            <div class="title">
                                {{$navbar->title ?? '[Title]'}}
                            </div>
                            <div class="manage">
                                <a class="manage-item" href="{{route('navbar.navbar.show', $navbar->id)}}">
                                    <i class="fa fa-eye"></i> {{trans('master.show')}}
                                </a>                            
                                <a class="manage-item" href="{{route('navbar.navbar.edit', $navbar->id)}}">
                                    <i class="fa fa-edit"></i> {{trans('master.edit')}}
                                </a>
                                <a class="manage-item js-delete" href="javascript:;" data-url="{{route('navbar.navbar.destroy', $navbar->id)}}">
                                    <i class="fa fa-times"></i> {{trans('master.delete')}}
                                </a>
                            </div>
                        </td>
                        <td>
                            <div class="text-sm"></div>
                            <div class="text-sm">{{$navbar->created_at ?? '[Created_at]'}}</div>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        @endif                       
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="footer-text mr-auto">
            {{trans('navbar::navbar.description')}}
        </div>

        {{ $navbars->links() }}
    </div>
</div>
@endsection
