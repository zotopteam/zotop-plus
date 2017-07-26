<div class="input-group input-upload-image" id="upload-image-{{$name}}">
    {{Form::text($name,$value,$attrs)}}
    <div class="input-group-addon progress-percent" style="display:none;">
        0%
    </div>
    <span class="input-group-btn">
        <button type="button" tabindex="-1" class="btn btn-secondary btn-upload">
            <i class="fa fa-image"></i>
            {{$button}}
        </button>
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
        $("#upload-image-{{$name}}").upload_image({!!json_encode($options)!!});
    });
    </script>
@endpush

