<a href="javascript:;" class="btn btn-primary" id="{{$id}}" {{$attributes}}>
    <i class="{{$icon}} fa-fw"></i> {{$text}}
</a>

@push('js')
{!! Module::load('core:plupload/plupload.full.min.js') !!}
{!! Module::load('core:plupload/i18n/'.App::getLocale().'.js') !!}
{!! Module::load('core:plupload/jquery.plupload.js') !!}
<script type="text/javascript">
    $(function() {
            var upload_btn = $('#{{$id}}');
            var upload_success = 0;
            var upload_options = $.extend({}, @json($options), {
                    started : function(up){
                        upload_btn.data('progress', $.progress());
                        upload_success = 0;
                    },
                    progress : function(up,file){
                        upload_btn.data('progress').percent(up.total.percent);
                    },
                    uploaded : function(up, file, response){
                        // 单个文件上传完成 返回信息在 response 中
                        if (response.result.state) {
                            $.success(response.result.content);
                            upload_success ++ ;
                        } else {
                            $.error(response.result.content);
                        }
                    },                
                    complete : function(up, files){
                        // 全部上传完成
                        upload_btn.data('progress').close().remove();
                        
                        if (upload_success > 0) {
                            location.reload();
                        }
                    },
                    error : function(error, detail){
                        $.error(detail);
                    }
            });

            upload_btn.plupload(upload_options);
        });
</script>
@endpush
