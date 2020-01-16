@extends('layouts.dialog')

@section('content')
@include('media::media.select.side')
<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>        
        <div class="main-action">
            <a href="javascript:;" class="btn btn-primary file-upload" id="file-upload" data-url="{{route('core.file.upload')}}">
                <i class="fa fa-fw fa-upload"></i> {{trans('core::file.upload')}}
            </a>        
            @if (request()->input('select', 0) != 1)
            <a href="javascript:;" class="btn btn-light js-select-all d-none">
                <i class="fa fa-check-square fa-fw"></i> {{trans('media::media.select.all')}}
            </a>
            @endif
            <a href="javascript:location.reload();" class="btn btn-light" title="{{trans('master.refresh')}}">
                <i class="fa fa-sync"></i>
            </a>        
        </div>        
    </div>
    <div class="main-header progress p-0 rounded-0 d-none">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>        
    </div>
    <div class="main-body scrollable p-2" id="file-upload-dragdrop">
        <div class="card-grid">
            @foreach($files as $file)
            <label class="card-check d-block m-0" data-type="file">
                @if (request()->input('select', 0) == 1)
                <input type="radio" name="file_ids[]" value="{{$file->id}}" class="form-control form-control-check">
                @else
                <input type="checkbox" name="file_ids[]" value="{{$file->id}}" class="form-control form-control-check">
                @endif               
                <div class="card card-md bg-light js-contextmenu">
                    <div class="card-thumb pos-r">
                        <div class="pos-a pos-full d-flex justify-content-center bg-image-preview">
                            @if ($file->isImage())
                            <img src="{{$file->url}}" class="img-fluid align-self-center">
                            @else
                            <i class="fa {{$file->icon}} fa-5x fa-fw text-muted align-self-center"></i>
                            @endif
                        </div>
                    </div>                             
                    <div class="card-body p-2">
                        <div class="card-text text-sm text-truncate">
                            {{$file->name}}
                        </div>
                        <div class="card-text text-xs text-truncate">
                            <small class="text-success">{{$file->size_human}}</small>
                            @if ($file->isImage())
                            <small>{{$file->width}}px × {{$file->height}}px</small>
                            @endif
                        </div>
                        <div class="contextmenu d-none">
                                @if ($file->isImage())
                                <a href="javascript:;" class="contextmenu-item js-image" data-url="{{$file->url}}" data-title="{{$file->name}}" data-info="{{$file->size_human}} / {{$file->width}}px × {{$file->height}}px">
                                    <i class="contextmenu-item-icon fa fa-eye fa-fw"></i>
                                    <b class="contextmenu-item-text">{{trans('master.view')}}</b>
                                </a>
                                @endif                 
                                <a class="contextmenu-item js-prompt" href="javascript:;" data-url="{{route('media.rename',[$file->id])}}"  data-prompt="{{trans('core::file.name')}}" data-name="name" data-value="{{$file->name}}">
                                    <i class="contextmenu-item-icon fa fa-fw fa-eraser"></i>
                                    <b class="contextmenu-item-text">{{trans('master.rename')}}</b>
                                </a>                      
                                <a class="contextmenu-item js-delete" href="javascript:;" data-url="{{route('media.destroy', $file->id)}}">
                                    <i class="contextmenu-item-icon fa fa-times fa-fw"></i>
                                    <b class="contextmenu-item-text">{{trans('master.delete')}}</b>
                                </a>
                        </div>
                        <textarea name="data" class="d-none">{!! json_encode($file) !!}</textarea>
                    </div>                           
                </div>
            </label>
            @endforeach
        </div>
    </div><!-- main-body -->
    @if ($files->lastPage() > 1)  
    <div class="main-footer text-sm p-1">
        {{ $files->appends($params)->links() }}
    </div>
    @endif
</div>
@endsection

@push('css')
<style type="text/css">
    .card-grid{
        display: grid;
        grid-template-columns: repeat(auto-fill,minmax(10rem,1fr));
        grid-row-gap: .5rem;
        grid-column-gap: .5rem;
        padding: .5rem;        
    }
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
        var success = 0;
        var options = {
                url : url,
                autostart : true, //自动开始
                multi_selection : true, //是否可以选择多个文件
                multipart_params: {
                    'folder_id'  : '{{$folder_id ?? 0}}',
                    'source_id'  : '{{$params['source_id'] ?? null}}',
                    'extensions' : '{{$params['extensions'] ?? null}}',
                    'module'     : '{{$params['module'] ?? app('current.module')}}',
                    'controller' : '{{$params['controller'] ?? app('current.controller')}}',
                    'action'     : '{{$params['action'] ?? app('current.action')}}',
                    'field'      : '{{$params['field'] ?? null}}',
                    'user_id'    : '{{Auth::user()->id}}',
                    'token'      : '{{Auth::user()->token}}'
                },
                filters: {
                    max_file_size:'{{$params['maxsize'] ?? 10}}mb',
                    mime_types : [
                        { title : "{{$params['typename'] ?? 'select files'}}", extensions : "{{$params['extensions'] ?? '*'}}"},
                    ],
                    prevent_duplicates:false //阻止多次上传同一个文件
                },
                started : function(up){
                    self.data('progress', $.progress());
                },
                progress : function(up,file){
                    self.data('progress').percent(up.total.percent);
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
                    self.data('progress').close().remove();
                    
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
    currentDialog.callbacks['ok'] = function () {
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
        } else {
            $.error('{{ trans('master.select.min', [1]) }}');
        }
        
        return false;
    }

</script>
@endpush
