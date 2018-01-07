@extends('core::layouts.dialog')

@section('content')
@include('media::media.select.side')
<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>        
        <div class="main-action">
            <a href="javascript:;" class="btn btn-light js-select-all">
                <i class="fa fa-check-square fa-fw"></i> {{trans('media::media.select.all')}}
            </a>
            <a href="javascript:location.reload();" class="btn btn-light" title="{{trans('core::master.refresh')}}">
                <i class="fa fa-sync"></i>
            </a>        
        </div>        
    </div>
    <div class="main-header progress p-0 rounded-0 d-none">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>        
    </div>
    <div class="main-body scrollable" id="file-upload-dragdrop">
        
        {form route="media.operate" class="form-datalist" method="post"}
        <table class="table table-nowrap table-hover table-select">
            <thead>
            <tr>
                <th class="select">
                    <input type="checkbox" class="select-all d-none">
                </th>
                <th colspan="3">{{trans('media::media.name')}}</th>
                <th width="10%">{{trans('media::media.type')}}</th>
                <th width="10%">{{trans('media::media.size')}}</th>
                <th width="10%">{{trans('media::media.created_at')}}</th>
            </tr>
            </thead>
            <tbody>

            @foreach($files as $file)
                <tr data-type="file">
                    <td class="select">
                        @if (request()->input('select', 0) == 1)
                        <input type="radio" name="file_ids[]" value="{{$file->id}}" class="select">
                        @else
                        <input type="checkbox" name="file_ids[]" value="{{$file->id}}" class="select">
                        @endif                        
                    </td>                
                    <td width="1%" class="text-center pr-2">
                        @if ($file->isImage())
                            <a href="javascript:;" class="js-image" data-url="{{$file->getUrl()}}" data-title="{{$file->name}}">
                                <div class="icon icon-32"><img src="{{$file->getPreview(32,32)}}"></div>
                            </a>
                        @else
                            <i class="fa {{$file->getIcon()}} fa-2x fa-fw text-warning"></i>
                        @endif                        
                    </td>                
                    <td width="50%" class="pl-2">
                        <div class="title text-md text-wrap">
                            {{$file->name}}
                        </div>
                        <div class="description">
                            @if ($file->isImage())
                            {{$file->width}}px × {{$file->height}}px
                            @endif
                        </div>
                        <textarea name="data" class="d-none">{!! json_encode($file) !!}</textarea>
                    </td>
                    <td width="10%" class="manage manage-hover text-right">
                        @if ($file->isImage())
                        <a href="javascript:;" class="manage-item js-image" data-url="{{$file->getUrl()}}" data-title="{{$file->name}}">
                            <i class="fa fa-eye fa-fw"></i> {{trans('media::file.view')}}
                        </a>
                        @endif                 
                        <a class="manage-item js-prompt" href="javascript:;" data-url="{{route('media.file.edit',[$file->id])}}"  data-prompt="{{trans('media::file.name')}}" data-name="name" data-value="{{$file->name}}">
                            <i class="fa fa-fw fa-eraser"></i> {{trans('media::file.rename')}}
                        </a>                      
                        <a class="manage-item js-delete" href="javascript:;" data-url="{{route('media.file.delete', $file->id)}}">
                            <i class="fa fa-times fa-fw"></i> {{trans('media::file.delete')}}
                        </a>                        
                    </td>
                    <td>{{trans('core::file.type.'.$file->type)}}</td>
                    <td>{{$file->getSize()}}</td>
                    <td>{{$file->getCreatedAt()}}</td>
                </tr>
            @endforeach

            </tbody>
        </table>
        {/form}

    </div><!-- main-body -->
    <div class="main-footer">
        {{ $files->links('core::pagination.default') }}
    </div>
</div>
@endsection

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

    // 回调
    function callback() {
        var selected  = new Array();

        $('[data-type="file"]').each(function() {
            if ($(this).find('input.select').is(':checked')) {
                var data = $(this).find('[name=data]').val();
                    data = $.parseJSON(data);
                selected.push(data);                
            }
        });

        $dialog.selected = selected;   
    }

    $(function(){
        var selectTable = $('table.table-select').data('selectTable');

        $('.js-select-all').on('click', function() {
            selectTable.selectAll(true);
            callback();
        });

        $('input.select').on('change',function(){
            callback();
        });

        // 文件单击
        $('[data-type="file"]').on('click', function(event) {
            //当点击为按钮时，禁止选择
            if($(event.target).prop('tagName') == 'A') return;

            if($(this).find('input.select').is(':checked')) {
                $(this).find('input.select').prop('checked',false);
            } else {
                $(this).find('input.select').prop('checked',true);
            }
            callback();
            event.stopPropagation();
            return false;
        });          
    });

</script>
@endpush
