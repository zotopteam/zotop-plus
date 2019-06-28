<div class="input-group upload-field" id="upload-field-{{$id}}">
    {{Form::text($name,$value,$attrs)}}
    <span class="input-group-append">
        <button type="button" tabindex="-1" class="btn btn-light btn-icon-text btn-upload btn-progress">
            <i class="btn-icon fa fa-fw {{$button_icon ?? 'fa-upload'}}"></i>
            <b class="btn-text">{{$button_text}}</b>
            <div class="progress d-none">
              <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">0%</div>
            </div>            
        </button>
        @if($tools)
        <button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split" tabindex="-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <div class="dropdown-menu dropdown-menu-primary dropdown-menu-right">
            @foreach($tools as $tool)
            <a class="dropdown-item js-upload-field-select" href="javascript:;" data-url="{{$tool['href']}}" data-title="{{$select_text}}">
                <i class="dropdown-item-icon {{$tool['icon'] ?? ''}}"></i>
                <b class="dropdown-item-text">{{$tool['text']}}</b>
            </a>
            @endforeach
        </div>
        @endif        
    </span>
</div>
@push('js')
    @loadjs(Module::asset('core:plupload/plupload.full.min.js'))
    @loadjs(Module::asset('core:plupload/i18n/'.App::getLocale().'.js'))
    @loadjs(Module::asset('core:plupload/jquery.plupload.js'))
    @loadjs(Module::asset('core:js/field_upload.js'))

    <script type="text/javascript">
    $(function(){
        $("#upload-field-{{$id}}").upload_field({!! json_encode($options) !!});
    });
    </script>
@endpush

