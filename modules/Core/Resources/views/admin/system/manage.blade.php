@extends('core::layouts.master')

@section('content')
<div class="full-width">
    <div class="jumbotron bg-primary text-white text-center m-0 pos-r">
        <div class="container-fluid">
            <h1>{{trans('core::system.manage.title')}}</h1>
            <p>{{trans('core::system.manage.description')}}</p>
        </div>
        <svg class="animation-wave" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28" preserveAspectRatio="none">
            <defs>
                <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"></path>
            </defs>
            <g class="parallax">
                <use xlink:href="#gentle-wave" x="50" y="0" fill="rgba(255,255,255,.3)"></use>
                <use xlink:href="#gentle-wave" x="50" y="3" fill="rgba(255,255,255,.3)"></use>
                <use xlink:href="#gentle-wave" x="50" y="6" fill="rgba(255,255,255,.3)"></use>
            </g>
        </svg>           
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
                success: function(msg) {
                    dir.html('<span class="text-success">' + msg + '</span>');
                },
                error: function(xhr,status,error){
                    dir.html('');
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
