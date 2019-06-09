@extends('core::layouts.master')

@section('content')
<div class="full-width">
    <div class="jumbotron bg-primary text-white text-center m-0">
        <div class="container-fluid">
            <h1>{{trans('core::system.manage.title')}}</h1>
            <p>{{trans('core::system.manage.description')}}</p>
        </div>
    </div>

    <div class="p-3">
        <div class="card mb-3">
            <div class="card-body p-0">
                <table class="table table-nowarp table-hover">
                    <tbody>
                    @foreach($manages as $key=>$manage)
                    <tr>
                        <td width="1%" class="text-center">
                            <i class="{{$manage.icon ?? 'fa fa-tt'}} fa-2x fa-fw"></i>
                        </td>
                        <td>
                            <div class="title">{{$manage.title ?? $key}}</div>
                            <div class="description">{{$manage.description ?? null}}</div>
                        </td>
                        <td width="30%">
                            @if (isset($manage.tips))
                                <div class="tips text-warning">{{$manage.tips}}</div>
                            @endif
                            @if (isset($manage.directory))
                                <div class="directory" data-url="{{route('core.system.size', ['directory'=>$manage.directory])}}">
                                    <i class="fa fa-spin fa-spinner"></i>
                                </div>
                            @endif
                        </td>
                        <td width="10%" class="text-right">
                            @if (isset($manage.action) && is_array($manage.action))
                                <a href="{{$manage.action.href ?? 'javascript:;'}}" class="{{$manage.action.class ?? 'btn btn-primary js-post'}}">
                                    <i class="{{$manage.action.icon ?? 'fa fa-tt'}} fa-fw"></i> {{$manage.action.text ?? null}}
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>                
            </div>
        </div>

    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
    $(function(){

        // 因为不支持直接获取文件夹大小，使用ajax获取
        function directory_size(dir) {
            $.ajax({
                url: dir.data('url'),
                dataType : 'json',
                success: function(msg) {
                    dir.html('<span class="text-success">' + msg.content + '</span>');
                },
                error: function(xhr,status,error){
                    dir.html('<span class="text-error">' + (xhr.responseJSON.message || xhr.responseText) + '</span>');
                }
            });
        }

        $('.directory').each(function(){
            var dir = $(this);
            var time = Math.ceil(Math.random()*3)*1000;
            setTimeout(function(){
                directory_size(dir);
            }, time);
        });


    });
</script>
@endpush
