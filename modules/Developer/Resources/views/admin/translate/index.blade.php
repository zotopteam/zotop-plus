@extends('core::layouts.master')

@section('content')
@include('developer::module.side')
<div class="main">
    <div class="main-header">        
        <div class="main-title mr-auto">
            {{$title}}
        </div>
        <div class="main-action">
        </div>           
    </div>
    <div class="main-body scrollable">
        @if($files)
        <table class="table table-hover">
            <thead>
                <tr>
                    <td colspan="2">{{trans('developer::file.name')}}</td>
                    <td>{{trans('developer::file.mtime')}}</td>
                    <td>{{trans('developer::translate.translate')}}</td>
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
                    <td>{{date('Y-m-d H:i:s',$file->getMTime())}}</td>
                    <td class="manage">
                        <a href="{{route('developer.translate.translate', [$module, 'filename'=>$file->getFilename()])}}">{{trans('developer::translate.translate')}}</a>
                    </td>
                </tr>
                @endforeach
          
            </tbody>
        </table>
        @else
            <div class="nodata">{{trans('core::master.nodata')}}</div>
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
