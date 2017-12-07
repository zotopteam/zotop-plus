@extends('core::layouts.master')

@section('content')
@include('media::media.side')
<div class="main">
    <div class="main-header">
        <div class="main-action mr-auto">
            <a href="{{route('media.file.upload')}}" class="btn btn-primary">
                <i class="fa fa-fw fa-upload"></i> {{trans('media::file.upload')}}
            </a>

            <a href="javascript:;" class="btn btn-outline-primary js-prompt" data-url="{{route('media.folder.create',[$folder_id])}}"  data-prompt="{{trans('media::folder.name')}}" data-name="name">
                <i class="fa fa-fw fa-folder"></i> {{trans('media::folder.create')}}
            </a>
        </div>
        <div class="main-action">
            <form class="form-inline form-search">
                <div class="input-group">   
                    {field type="select" name="type" options="Module::data('media::type.options')"}
                    <input name="keywords" value="{{$keywords}}" class="form-control" type="search" placeholder="{{trans('core::master.search.placeholder')}}" required="required" aria-label="Search" style="min-width:12rem;">
                    <div class="input-group-btn">
                        <button class="btn btn-primary" type="submit"> <i class="fa fa-fw fa-search"></i> </button>
                    </div>
                </div>
            </form>
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
        @if ($keywords)
            <span class="breadcrumb-item active">{{trans('core::master.searching', [$keywords])}}</span> 
        @endif        
    </div>
    <div class="main-body scrollable">

        {form route="media.index" method="post"}
        <table class="table table-nowrap table-hover table-select">
            <thead>
            <tr>
                <th class="select">
                    <input type="checkbox" name="" class="select-all">
                </th>
                <th colspan="3">{{trans('media::media.name')}}</th>
                <th width="12%">{{trans('media::media.type')}}</th>
                <th width="12%">{{trans('media::media.size')}}</th>
                <th width="12%">{{trans('media::media.created_at')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($folders as $folder)
                <tr class="folder-item" data-url="{{route('media.index', $folder->id)}}">
                    <td class="select">
                        <input type="checkbox" name="folder_id[]" value="{{$folder->id}}" class="selectAll">
                    </td>
                    <td width="1%" class="text-center pr-2">
                        <i class="fa fa-fw fa-2x fa-folder text-warning"></i>
                    </td>
                    <td class="pl-2">
                        <div class="title">
                             <a href="{{route('media.index', $folder->id)}}">{{$folder->name}}</a>
                        </div>
                    </td>
                    <td width="10%" class="manage manage-hover text-right">
                            <a class="manage-item js-prompt" href="javascript:;" data-url="{{route('media.folder.edit',[$folder->id])}}"  data-prompt="{{trans('media::folder.name')}}" data-name="name" data-value="{{$folder->name}}">
                                <i class="fa fa-fw fa-eraser"></i> {{trans('core::folder.rename')}}
                            </a>
                            <a class="manage-item js-delete" href="javascript:;" data-url="{{route('media.folder.delete', $folder->id)}}">
                                <i class="fa fa-fw fa-times"></i> {{trans('core::master.delete')}}
                            </a>                        
                    </td>
                    <td>{{trans('media::folder.type')}}</td>
                    <td></td>
                    <td>{{$folder->created_at}}</td>
                </tr>
            @endforeach

            @foreach($files as $file)
                <tr>
                    <td class="select">
                        <input type="checkbox" name="file_id[]" value="{{$file->id}}" class="selectAll">
                    </td>                
                    <td width="1%" class="text-center pr-2">
                        @if ($file->type == 'image')
                            <div class="image"><img src="{{$file->getPreview(32,32)}}"></div>
                        @else
                            <i class="fa fa-fw fa-2x fa-file text-warning"></i>
                            <i class="fa {{File::icon($file->extension)}} fa-2x fa-fw text-warning"></i>
                        @endif
                        
                    </td>                
                    <td width="50%" class="pl-2">
                        <div class="title text-lg text-overflow">
                            {{str_limit($file->name,36)}}
                        </div>
                        <div class="description">
                            {{$file->width}}px × {{$file->height}}px
                        </div>
                    </td>
                    <td width="10%" class="manage manage-hover text-right">
                        <a class="manage-item js-prompt" href="javascript:;" data-url="{{route('media.file.edit',[$file->id])}}"  data-prompt="{{trans('media::file.name')}}" data-name="name" data-value="{{$file->name}}">
                            <i class="fa fa-fw fa-eraser"></i> {{trans('core::file.rename')}}
                        </a>
                        <a class="manage-item js-delete" href="javascript:;" data-url="{{route('media.file.delete', $file->id)}}">
                            <i class="fa fa-times"></i> {{trans('core::master.delete')}}
                        </a>                        
                    </td>
                    <td>{{trans('core::file.type.'.$file->type)}}</td>
                    <td>{{Format::size($file->size)}}</td>
                    <td>{{$file->created_at}}</td>
                </tr>
            @endforeach

            </tbody>
        </table>
        {/form}

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
