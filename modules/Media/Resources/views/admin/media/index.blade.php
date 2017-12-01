@extends('core::layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>
        <div class="main-action">
            
            <a href="javascript:;" class="btn btn-primary js-prompt" data-url="{{route('media.folder.create',[$folder_id,'prompt'])}}"  data-prompt="{{trans('media::folder.name')}}">
                <i class="fa fa-folder"></i> {{trans('media::folder.create')}}
            </a>
            <a href="{{route('media.file.upload')}}" class="btn btn-primary">
                <i class="fa fa-upload"></i> {{trans('media::file.upload')}}
            </a> 
                   
        </div>        
    </div>
    <div class="main-header breadcrumb m-0">
        @if ($folder_id)
        <a href="{{route('media.index',[$folder->parent_id])}}" class="breadcrumb-item breadcrumb-extra">
            <i class="fa fa-arrow-up"></i>{{trans('media::folder.up')}}
        </a>
        @else
        <a href="javascript:;" class="breadcrumb-item breadcrumb-extra disabled"><i class="fa fa-arrow-up"></i>{{trans('media::folder.up')}}</a>
        @endif
        <a class="breadcrumb-item" href="{{route('media.index')}}">{{trans('media::media.title')}}</a>
        @foreach($parents as $p)
        <a class="breadcrumb-item" href="{{route('media.index', $p->id)}}">{{$p->name}}</a> 
        @endforeach
    </div>
    <div class="main-body scrollable">

        <table class="table table-nowrap table-hover">
            <thead>
            <tr>
                <th colspan="3">{{trans('media::media.name')}}</th>
                <th width="12%">{{trans('media::media.type')}}</th>
                <th width="12%">{{trans('media::media.size')}}</th>
                <th width="12%">{{trans('media::media.created_at')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($folders as $folder)
                <tr class="folder-item" data-url="{{route('media.index', $folder->id)}}">
                    <td width="1%" class="pr-2">
                        <i class="fa fa-fw fa-2x fa-folder text-warning"></i>
                    </td>
                    <td class="pl-2">
                        <div class="title">
                             <a href="{{route('media.index', $folder->id)}}">{{$folder->name}}</a>
                        </div>
                    </td>
                    <td width="10%" class="manage text-right">
                            <a class="manage-item d-hover" href="{{route('media.folder.edit', $folder->id)}}">
                                <i class="fa fa-edit"></i> {{trans('core::master.edit')}}
                            </a>
                            <a class="manage-item d-hover js-delete" href="javascript:;" data-url="{{route('media.folder.delete', $folder->id)}}">
                                <i class="fa fa-times"></i> {{trans('core::master.delete')}}
                            </a>                        
                    </td>
                    <td>{{trans('media::folder.type')}}</td>
                    <td></td>
                    <td>{{$folder->created_at}}</td>
                </tr>
            @endforeach

            @foreach($files as $file)
                <tr>
                    <td>
                        <div class="title text-lg">
                            {{$file->name}}
                        </div>
                        <div class="manage">
                            <a class="manage-item" href="{{route('media.edit', $file->id)}}">
                                <i class="fa fa-edit"></i> {{trans('core::master.edit')}}
                            </a>
                            <a class="manage-item js-delete" href="javascript:;" data-url="{{route('media.destroy', $file->id)}}">
                                <i class="fa fa-times"></i> {{trans('core::master.delete')}}
                            </a>
                        </div>
                    </td>
                    <td></td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="footer-text mr-auto">
            {{trans('media::media.description')}}
        </div>

        {{ $files->links('core::pagination.default') }}
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
    $(function(){
        // 文件夹双击
        $('.folder-item').on('dblclick',function(){
            location.href = $(this).data('url');
            return false;
        });        
    });

</script>
@endpush
