<div class="input-group input-upload-image" id="upload-image-{{$id}}">
    {{Form::text($name,$value,$attrs)}}
    <div class="input-group-append progress-percent" style="display:none;">
        0%
    </div>
    <span class="input-group-append">
        <button type="button" tabindex="-1" class="btn btn-secondary btn-upload">
            <i class="fa fa-image"></i>
            {{$button}}
        </button>
        @if($tools)
        <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <div class="dropdown-menu dropdown-menu-primary dropdown-menu-right">
            @foreach($tools as $tool)
            <a class="dropdown-item" href="#">
                <i class="dropdown-item-icon fa fa-fw {{$tool['icon'] or ''}}"></i>
                <b class="dropdown-item-text">{{$tool['text']}}</b>
            </a>
            @endforeach
        </div>
        @endif        
    </span>
</div>
@push('js')
    @once('field_pupload')
    <script type="text/javascript" src="{{Module::asset('core:plupload/plupload.full.min.js')}}"></script>
    <script type="text/javascript" src="{{Module::asset('core:plupload/i18n/'.App::getLocale().'.js')}}"></script>
    <script type="text/javascript" src="{{Module::asset('core:plupload/jquery.plupload.js')}}"></script>
    @endonce

    @once('field_pupload_image')
    <script type="text/javascript" src="{{Module::asset('core:plupload/jquery.upload_image.js')}}"></script>
    @endonce

    <script type="text/javascript">
    $(function(){
        $("#upload-image-{{$id}}").upload_image({!! json_encode($options) !!});
    });
    </script>
@endpush

