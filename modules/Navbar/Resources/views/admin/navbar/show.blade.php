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
            <a class="btn btn-success" href="{{route('navbar.navbar.edit', $navbar->id)}}">
                <i class="fa fa-edit"></i> {{trans('master.edit')}}
            </a>
            <a class="btn btn-warning js-delete" href="javascript:;" data-url="{{route('navbar.navbar.destroy', $navbar->id)}}">
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
                    {{$navbar->id ?? '[ID]'}}
                </td>                
            </tr>
            <tr>
                <td width="15%">
                    {{trans('navbar::navbar.title.label')}}
                </td>
                <td>
                    {{$navbar->title ?? '[Title]'}}
                </td>                
            </tr>
        </table>

    </div><!-- main-body -->
</div>
@endsection
