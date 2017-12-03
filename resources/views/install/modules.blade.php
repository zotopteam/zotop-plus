@extends('install.master')

@section('content')
    <section class="main d-flex scrollable">    
        <div class="jumbotron bg-transparent full-width align-self-center text-center">          
            <h1>{{trans("installer.$current")}}</h1>
            <p class="progress-text">{{trans("installer.$current.description")}}</p>
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width:0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
            </div>
        </div>
    </section>
    
    <section class="d-none"> 
        <div class="modules">
            @foreach($modules as $name=>$module)                
                <div class="module text-center" data-name="{{$name}}" data-title="{{$module->title}}">
                    <div class="module-icon">
                        <img src="{{preview($module->getExtraPath('/module.png'), 48, 48)}}">
                    </div>
                    <div class="module-title">{{$module->title}}</div>
                </div>
            @endforeach
        </div>
    </section>
@endsection

@section('wizard-none')

    <a href="{{route("install.$prev")}}" class="btn btn-outline text-white btn-prev d-inline-block mr-auto">
        <i class="fa fa-angle-left fa-fw"></i> {{trans('installer.prev')}}
    </a>
    
    <a href="{{route("install.$next")}}" class="btn btn-lg btn-success btn-next d-inline-block ml-auto">
        {{trans('installer.next')}} <i class="fa fa-angle-right fa-fw"></i> 
    </a>

@endsection

@push('css')

@endpush

@push('js')

<script type="text/javascript">

    var modules      = $('.module');
    var count        = modules.length;    
    var progressBar  = $('.progress-bar');
    var progressText = $('.progress-text');
    var progress     = 0;    

    function install(index) {

        var module     = modules.eq(index);
        var title      = module.data('title');
        var name       = module.data('name');
        var installing = '' + title + ' {{trans("installer.$current.installing")}}';
        var installed  = '' + title + ' {{trans("installer.$current.installed")}}';

        progressText.html(installing);

        $.post('{{route("install.$current")}}',{name:name},function(msg){

            // 安装成功
            if (msg.state) {

                module.addClass('success');
                
                progressText.html(installed);

                progress = Math.ceil((index+1) * 100 / count) + '%';
                progressBar.css('width', progress).html(progress);

                if (index+1 < count) {
                    install(index+1); // 安装下一个
                } else {
                    progressText.html('{{trans("installer.$current.completed")}}');
                    location.href = "{{route("install.$next")}}";
                }
            } else {
                module.addClass('error');
                progressText.html(msg.content);
            }

        },'json');
    }

    $(function(){        
        install(0);
    });
</script>

@endpush
