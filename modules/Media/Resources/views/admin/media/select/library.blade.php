@extends('core::layouts.dialog')

@section('content')
@include('media::media.select.side')
<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>
        <div class="main-action">
            <a href="javascript:;" class="btn btn-primary file-upload" id="file-upload" data-url="{{route('core.file.upload')}}">
                <i class="fa fa-fw fa-upload"></i> {{trans('media::file.upload')}}
            </a>
            <a href="javascript:;" class="btn btn-outline-primary btn-icon-only js-prompt" data-url="{{route('media.folder.create',[$folder_id])}}"  data-prompt="{{trans('media::folder.name')}}" data-name="name" title="{{trans('media::folder.create')}}">
                <i class="fa fa-fw fa-folder"></i>
            </a>
            <a href="javascript:location.reload();" class="btn btn-light" title="{{trans('core::master.refresh')}}">
                <i class="fa fa-sync"></i>
            </a>        
        </div>        
    </div>
    <div class="main-header breadcrumb m-0 p-2 text-sm">
        @if ($folder_id)
        <a href="{{$parent_url}}" class="breadcrumb-item breadcrumb-extra">
            <i class="fa fa-fw fa-arrow-up"></i>{{trans('media::folder.up')}}
        </a>
        @else
        <a href="javascript:;" class="breadcrumb-item breadcrumb-extra disabled"><i class="fa fa-arrow-up"></i>{{trans('media::folder.up')}}</a>
        @endif
        <a class="breadcrumb-item" href="{{$root_url}}">{{trans('media::media.root')}}</a>
        @foreach($parents as $p)
        <a class="breadcrumb-item" href="{{$p->url}}">{{$p->name}}</a> 
        @endforeach      
    </div>    
    <div class="main-header progress p-0 rounded-0 d-none">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>        
    </div>
    <div class="main-body scrollable p-2" id="file-upload-dragdrop">
        
        <div class="container-fluid">
            <div class="row">
            @foreach($folders as $folder)
                <div class="col-sm-4 col-md-3 col-xl-2 p-1">
                    <label class="card-check d-block cur-p m-0" data-type="folder" data-url="{{$folder->url}}">  
                        <div class="card card-md bg-light js-contextmenu">
                            <div class="card-image">
                                <div class="card-thumb pos-r">
                                    <div class="pos-a pos-full d-flex justify-content-center bg-white">
                                        <i class="fa fa-folder fa-6x fa-fw text-warning align-self-center"></i>
                                    </div>
                                </div>                             
                            </div>
                            <div class="card-body p-2">
                                <div class="card-text text-md text-overflow">
                                    {{$folder->name}}
                                </div>
                                <div class="card-text text-xs">
                                    <small class="text-success">{{trans('media::folder.type')}}</small>
                                </div>
                                <div class="contextmenu d-none">          
                                        <a class="contextmenu-item js-prompt" href="javascript:;" data-url="{{route('media.folder.edit',[$folder->id])}}"  data-prompt="{{trans('media::folder.name')}}" data-name="name" data-value="{{$folder->name}}">
                                            <i class="contextmenu-item-icon fa fa-fw fa-eraser"></i>
                                            <b class="contextmenu-item-text">{{trans('media::folder.rename')}}</b>
                                        </a>                      
                                        <a class="contextmenu-item js-delete" href="javascript:;" data-url="{{route('media.folder.delete', $folder->id)}}">
                                            <i class="contextmenu-item-icon fa fa-times fa-fw"></i>
                                            <b class="contextmenu-item-text">{{trans('media::folder.delete')}}</b>
                                        </a>
                                </div>
                            </div>                           
                        </div>
                    </label>
                </div>
            @endforeach            
            @foreach($files as $file)
                <div class="col-sm-4 col-md-3 col-xl-2 p-1">
                    <label class="card-check d-block m-0" data-type="file">
                        @if (request()->input('select', 0) == 1)
                        <input type="radio" name="file_ids[]" value="{{$file->id}}" class="form-control form-control-check">
                        @else
                        <input type="checkbox" name="file_ids[]" value="{{$file->id}}" class="form-control form-control-check">
                        @endif               
                        <div class="card card-md bg-light js-contextmenu">
                            <div class="card-image">
                                <div class="card-thumb pos-r">
                                    <div class="pos-a pos-full d-flex justify-content-center bg-image-preview">
                                        @if ($file->isImage())
                                        <img src="{{$file->url()}}" class="align-self-center">
                                        @else
                                        <i class="fa {{$file->icon()}} fa-6x fa-fw text-warning align-self-center"></i>
                                        @endif
                                    </div>
                                </div>                             
                            </div>
                            <div class="card-body p-2">
                                <div class="card-text text-md text-overflow">
                                    {{$file->name}}
                                </div>
                                <div class="card-text text-xs">
                                    <small class="text-success">{{trans('core::file.type.'.$file->type)}}</small>
                                    <small class="text-info">{{$file->size()}}</small>
                                    @if ($file->isImage())
                                    <small>{{$file->width}}px × {{$file->height}}px</small>
                                    @endif
                                </div>
                                <div class="contextmenu d-none">
                                        @if ($file->isImage())
                                        <a href="javascript:;" class="contextmenu-item js-image" data-url="{{$file->url()}}" data-title="{{$file->name}}" data-info="{{$file->size()}} / {{$file->width}}px × {{$file->height}}px">
                                            <i class="contextmenu-item-icon fa fa-eye fa-fw"></i>
                                            <b class="contextmenu-item-text">{{trans('media::file.view')}}</b>
                                        </a>
                                        @endif                 
                                        <a class="contextmenu-item js-prompt" href="javascript:;" data-url="{{route('media.file.edit',[$file->id])}}"  data-prompt="{{trans('media::file.name')}}" data-name="name" data-value="{{$file->name}}">
                                            <i class="contextmenu-item-icon fa fa-fw fa-eraser"></i>
                                            <b class="contextmenu-item-text">{{trans('media::file.rename')}}</b>
                                        </a>                                                                  
                                        <a class="contextmenu-item js-delete" href="javascript:;" data-url="{{route('media.file.delete', $file->id)}}">
                                            <i class="contextmenu-item-icon fa fa-times fa-fw"></i>
                                            <b class="contextmenu-item-text">{{trans('media::file.delete')}}</b>
                                        </a>
                                </div>
                                <textarea name="data" class="d-none">{!! json_encode($file) !!}</textarea>
                            </div>                           
                        </div>
                    </label>
                </div>
            @endforeach
            </div>
        
        </div>

    </div><!-- main-body -->
    @if ($files->lastPage() > 1)  
    <div class="main-footer text-sm p-1">
        {{ $files->appends($params)->links('core::pagination.default') }}
    </div>
    @endif
</div>
@endsection

@push('css')
<style type="text/css">
    .card-thumb{padding-bottom:60%;overflow:hidden;}
    .card-thumb img{max-width:100%;max-height:100%;}
</style>
@endpush
@push('js')
<script type="text/javascript" src="{{Module::asset('core:plupload/plupload.full.min.js')}}"></script>
<script type="text/javascript" src="{{Module::asset('core:plupload/i18n/'.App::getLocale().'.js')}}"></script>
<script type="text/javascript" src="{{Module::asset('core:plupload/jquery.plupload.js')}}"></script>
<script type="text/javascript">
    // upload
    $('.file-upload').each(function(){
        var self = $(this);
        var url = self.data('url');
        var progress = $('.progress');
        var success = 0;
        var options = {
                url : url,
                autostart : true, //自动开始
                multi_selection : true, //是否可以选择多个文件
                multipart_params: {
                    'folder_id'  : '{{$folder_id or 0}}',
                    'module'     : '{{app('current.module')}}',
                    'controller' : '{{app('current.controller')}}',
                    'action'     : '{{app('current.action')}}',
                    'user_id'    : '{{Auth::user()->id}}',
                    'token'      : '{{Auth::user()->token}}'
                },
                filters: {
                    //max_file_size:'20mb',
                    mime_types : [
                        { title : "select files", extensions : "*"},
                    ],
                    prevent_duplicates:false //阻止多次上传同一个文件
                },
                progress : function(up,file){
                    progress.removeClass('d-none');
                    progress.find('.progress-bar').width(up.total.percent+'%').html(up.total.percent+'%');
                },
                uploaded : function(up, file, response){
                    // 单个文件上传完成 返回信息在 response 中
                    if (response.result.state) {
                        $.success(response.result.content);
                        success ++;
                    } else {
                        $.error(response.result.content);
                    }
                },                
                complete : function(up, files){
                    // 全部上传完成
                    progress.addClass('d-none')
                    progress.find('.progress-bar').width('0%').html('');
                    
                    if (success > 0) {
                        location.reload();
                    }
                },
                error : function(error, detail){
                    $.error(detail);
                }
        };

        self.plupload(options);
    });
</script>
<script type="text/javascript">  

    // 确定按钮回调
    $dialog.callbacks['ok'] = function () {
        var selected  = new Array();

        $('[data-type="file"]').each(function() {
            if ($(this).find('input.form-control-check').is(':checked')) {
                var data = $(this).find('[name=data]').val();
                    data = $.parseJSON(data);
                selected.push(data);                
            }
        });

        if (selected.length) {
            this.close(selected).remove(); 
        }
        
        return false;
    }

</script>
<script type="text/javascript">  

    $(function(){
        // 文件夹单击
        $('[data-type="folder"]').on('click',function(){
            location.href = $(this).data('url');
            return false;
        });

        // 文件单击
        $('[data-type="file"]').on('click', function(event) {
            // code
        });         
    });

</script>
@endpush
