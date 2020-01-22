@extends('layouts.master')

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
                    <a href="{{route('developer.controller.index',[$module, $k])}}" class="btn {{$type==$k ? 'btn-success' : 'btn-secondary'}}">
                        {{$v['title']}}
                    </a>
                @endforeach
            </div>
        </div>        
        <div class="main-action">
            <a href="javascript:;" class="btn btn-primary js-open" data-url="{{route('developer.controller.create',[$module, $type])}}" data-width="800" data-height="300">
                <i class="fa fa-plus"></i> {{trans('master.create')}}
            </a>
        </div>
    </div>
    <div class="main-body scrollable">
        
        @if($files)
        <table class="table table-hover">
            <thead>
                <tr>
                    <td colspan="3">{{trans('developer::file.name')}}</td>
                    <td>{{trans('developer::file.mtime')}}</td>
                </tr>                
            </thead>
            <tbody>
                
                @foreach($files as $file)
                <tr>
                    <td width="1%" class="pr-2"><div class="fa fa-file fa-2x text-primary"></div> </td>
                    <td class="pl-2">
                        <div class="title">{{$file->getFilename()}}</div>
                        <div class="description">
                            {{$file->getRealPath()}}
                        </div>
                    </td>
                    <td class="manage text-right">
                        <a class="manage-item js-open" href="{{route('developer.controller.route',[$module, $type, basename($file,'.php')])}}" data-width="80%" data-height="60%">
                            <i class="fa fa-fw fa-anchor"></i> {{trans('developer::controller.route')}}
                        </a>                        
                    </td>
                    <td>{{date('Y-m-d H:i:s',$file->getMTime())}}</td>
                </tr>
                @endforeach
          
            </tbody>
        </table>
        @else
            <div class="nodata">{{trans('master.nodata')}}</div>
        @endif     
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="footer-text mr-auto">
           <i class="fa fa-terminal"></i> php artisan module::make-controller {{$module}} test --model=test --force
        </div>
    </div>    
    <div class="main-footer">
        <div class="footer-text mr-auto">
            {{trans('developer::developer.position')}}: {{$path}}
        </div>
    </div>
</div>

@endsection

@push('js')

@endpush
