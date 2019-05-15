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
                <i class="fa fa-fw fa-upload"></i> {{trans('core::file.upload')}}
            </a>
            <a href="javascript:;" class="btn btn-outline-primary btn-icon-only js-prompt" data-url="{{route('media.create',[$parent_id,'folder'])}}"  data-prompt="{{trans('core::folder.name')}}" data-name="name" title="{{trans('core::folder.create')}}">
                <i class="fa fa-fw fa-folder-plus"></i> {{trans('core::folder.create')}}
            </a>
            <a href="javascript:location.reload();" class="btn btn-light" title="{{trans('core::master.refresh')}}">
                <i class="fa fa-sync"></i>
            </a>        
        </div>        
    </div>
    <div class="main-header breadcrumb m-0 p-2 text-xs">
        @if ($parent_id)
        <a href="{{$parent_url}}" class="breadcrumb-item breadcrumb-extra">
            <i class="fa fa-fw fa-arrow-up"></i>{{trans('media::media.up')}}
        </a>
        @else
        <a href="javascript:;" class="breadcrumb-item breadcrumb-extra disabled"><i class="fa fa-arrow-up"></i>{{trans('media::media.up')}}</a>
        @endif
        <a class="breadcrumb-item" href="{{$root_url}}">{{trans('media::media.root')}}</a>
        @foreach($parents as $p)
        <a class="breadcrumb-item" href="{{$p->url}}">{{$p->name}}</a> 
        @endforeach      
    </div>    
    <div class="main-body scrollable p-2" id="file-upload-dragdrop">
        
        <div class="card-grid">         
        @foreach($media as $m)     
            <label class="card-check d-flex flex-column" data-type="{{$m->isFolder() ? 'folder' : 'file'}}" data-url="{{$m->link}}">
                @if (request()->input('select', 0) == 1)
                <input type="radio" name="file_ids[]" value="{{$m->id}}" class="form-control form-control-check">
                @else
                <input type="checkbox" name="file_ids[]" value="{{$m->id}}" class="form-control form-control-check">
                @endif             
                <div class="card bg-light js-contextmenu">
                    <div class="card-thumb pos-r">
                        @if ($m->isFolder())
                        <div class="d-flex justify-content-center bg-white pos-a pos-full">
                            <i class="fa fa-folder fa-6x fa-fw text-warning align-self-center"></i>
                        </div>
                        @elseif ($m->isImage())
                        <div class="d-flex justify-content-center bg-image-preview pos-a pos-full">
                            <img src="{{$m->link}}" class="align-self-center">
                        </div>
                        @else
                        <div class="d-flex justify-content-center bg-white pos-a pos-full">
                        <i class="{{$m->icon}} fa-5x fa-fw text-muted align-self-center"></i>
                        </div>
                        @endif
                    </div>

                    <div class="card-body p-2">
                        <div class="card-text text-sm text-truncate">
                            {{$m->name}}
                        </div>
                        <div class="card-text text-xs text-truncate">
                            @if($m->isFolder())
                            <small class="text-success">{{trans('core::folder.type')}}</small>
                            @else
                            <small class="text-success">{{trans('core::file.type.'.$m->type)}}</small>
                            @endif
                            <small class="text-info">{{$m->size_human}}</small>
                            @if ($m->isImage())
                            <small>{{$m->width}}px × {{$m->height}}px</small>
                            @endif
                        </div>
                        <div class="contextmenu d-none">
                                @if ($m->isImage())
                                <a href="javascript:;" class="contextmenu-item js-image" data-url="{{$m->link}}" data-title="{{$m->name}}" data-info="{{$m->size_human}} / {{$m->width}}px × {{$m->height}}px">
                                    <i class="contextmenu-item-icon fa fa-eye fa-fw"></i>
                                    <b class="contextmenu-item-text">{{trans('core::master.view')}}</b>
                                </a>
                                @endif

                                @if($m->isFolder())
                                    <a class="contextmenu-item js-prompt" href="javascript:;" data-url="{{route('media.rename',[$m->id])}}"  data-prompt="{{trans('core::folder.name')}}" data-name="name" data-value="{{$m->name}}">
                                        <i class="contextmenu-item-icon fa fa-fw fa-eraser"></i>
                                        <b class="contextmenu-item-text">{{trans('core::master.rename')}}</b>
                                    </a>                      
                                @else
                                    <a class="contextmenu-item js-prompt" href="javascript:;" data-url="{{route('media.rename',[$m->id])}}"  data-prompt="{{trans('core::file.name')}}" data-name="name" data-value="{{$m->name}}">
                                        <i class="contextmenu-item-icon fa fa-fw fa-eraser"></i>
                                        <b class="contextmenu-item-text">{{trans('core::master.rename')}}</b>
                                    </a>                                                                  
                                @endif

                                <a class="contextmenu-item js-delete" href="javascript:;" data-url="{{route('media.destroy', $m->id)}}">
                                    <i class="contextmenu-item-icon fa fa-times fa-fw"></i>
                                    <b class="contextmenu-item-text">{{trans('core::master.delete')}}</b>
                                </a>                                          
                        </div>
                        <textarea name="data" class="d-none">{!! json_encode($m) !!}</textarea>
                    </div>                           
                </div>
            </label>
        @endforeach
        </div>
        

    </div><!-- main-body -->
    @if ($media->lastPage() > 1)  
    <div class="main-footer text-sm p-1">
        {{ $media->appends($params)->links('core::pagination.default') }}
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
                    'folder_id'  : '{{$parent_id ?? 0}}',
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
                    //max_file_size:'20mb',
                    mime_types : [
                        { title : "select files", extensions : "*"},
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
        } else {
            $.error('{{ trans('core::master.select.min', [1]) }}');
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
