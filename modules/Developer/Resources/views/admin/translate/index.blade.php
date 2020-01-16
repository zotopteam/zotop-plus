@extends('layouts.master')

@section('content')
@include('developer::module.side')
<div class="main">
    <div class="main-header">        
        <div class="main-title mr-auto">
            {{$title}}
        </div>
        <div class="main-action">
            <a href="javascript:;" class="btn btn-primary js-prompt" data-url="{{route('developer.translate.newfile',[$module])}}"  data-prompt="{{trans('developer::file.name')}}" data-name="name">
                <i class="fa fa-fw fa-plus"></i> {{trans('core::file.create')}}
            </a>            
        </div>           
    </div>
    <div class="main-body scrollable">
        @if($files)
        <table class="table table-hover">
            <thead>
                <tr>
                    <td colspan="2">{{trans('developer::file.name')}}</td>
                    <td>{{trans('developer::translate.itemcount')}}</td>
                    <td>{{trans('developer::file.mtime')}}</td>
                    <td></td>
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
                    <td>{{$file->itemcount}}</td>
                    <td>{{date('Y-m-d H:i:s',$file->getMTime())}}</td>
                    <td class="manage">
                        <a class="manage-item" href="{{route('developer.translate.translate', [$module, 'filename'=>$file->getFilename()])}}">
                            <i class="fa fa-language fa-fw"></i> {{trans('developer::translate.translate')}}
                        </a>

                        <a class="manage-item js-confirm" href="{{route('developer.translate.deletefile', [$module, 'filename'=>$file->getFilename()])}}">
                            <i class="fa fa-times fa-fw"></i> {{trans('master.delete')}}
                        </a>                        
                    </td>
                </tr>
                @endforeach
          
            </tbody>
        </table>
        @else
            <div class="nodata">{{trans('master.nodata')}}</div>
        @endif           
    </div>
    <div class="main-footer">
        <div class="footer-text mr-auto">
            <i class="fa fa-folder fa-fw mr-2 text-warning"></i> {{Format::path($path)}}
        </div>
    </div>
</div>
@endsection

@push('css')

@endpush

@push('js')

@endpush
