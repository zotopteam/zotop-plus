@extends('core::layouts.dialog')

@section('content')
@include('media::media.select.side')
<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>        
        <div class="main-action">
            @if (request()->input('select', 0) != 1)
            <a href="javascript:;" class="btn btn-light js-select-all">
                <i class="fa fa-check-square fa-fw"></i> {{trans('media::media.select.all')}}
            </a>
            @endif
            <a href="javascript:location.reload();" class="btn btn-light" title="{{trans('core::master.refresh')}}">
                <i class="fa fa-sync"></i>
            </a>        
        </div>        
    </div>
    <div class="main-header progress p-0 rounded-0 d-none">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>        
    </div>
    <div class="main-body scrollable p-2" id="file-upload-dragdrop">
        
        <div class="container-fluid">

            <div class="row">
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
                                        <img src="{{$file->url()}}" class="img-fluid align-self-center">
                                        @else
                                        <i class="fa {{$file->icon()}} fa-2x fa-fw text-warning"></i>
                                        @endif
                                    </div>
                                </div>                             
                            </div>
                            <div class="card-body p-2">
                                <div class="card-text text-md text-overflow">
                                    {{$file->name}}
                                </div>
                                <div class="card-text">
                                    <small class="text-success">{{$file->size()}}</small>
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
@endpush
