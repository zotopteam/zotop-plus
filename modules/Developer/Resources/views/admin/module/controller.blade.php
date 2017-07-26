@extends('core::layouts.master')

@section('content')
@include('developer::module.side')
<div class="main">
    <div class="main-header">
        <div class="main-title">
            {{$title}}
        </div>
        <div class="main-action mx-auto">
            <div class="btn-group" role="group">
                @foreach($types as $k=>$v)
                    <a href="{{route('developer.module.controller',[$name,$k])}}" class="btn {{$type==$k ? 'btn-success' : 'btn-secondary'}}">
                        {{$v['name']}}
                    </a>
                @endforeach
            </div>
        </div>        
        <div class="main-action">
            <a href="javascript:;" class="btn btn-primary js-open" data-url="{{route('developer.module.make.controller',[$name,$type])}}" data-width="800" data-height="300">
                <i class="fa fa-plus"></i> {{trans('core::master.create')}}
            </a>
        </div>
    </div>
    <div class="main-body scrollable">
        
        @if($files)
        <table class="table table-hover">
            <thead>
                <tr>
                    <td>{{trans('developer::module.file.name')}}</td>
                    <td>{{trans('developer::module.file.path')}}</td>
                    <td>{{trans('developer::module.file.lastmodified')}}</td>
                </tr>                
            </thead>
            <tbody>
                
                @foreach($files as $file)
                <tr>
                    <td>{{File::name($file)}} </td>
                    <td>{{realpath($file)}}</td>
                    <td>{{date('Y-m-d H:i:s',File::lastModified($file))}}</td>
                </tr>
                @endforeach
          
            </tbody>
        </table>
        @else
            <div class="nodata">{{trans('core::master.nodata')}}</div>
        @endif     
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="footer-text mr-auto">
            {{trans('developer::module.path')}}: {{realpath($path)}}
        </div>
    </div>
</div>

@endsection

@push('js')

@endpush