<div class="input-group upload-field" id="upload-field-{{$id}}">
    {{Form::text($name,$value,$attrs)}}
    <span class="input-group-append">
        <button type="button" tabindex="-1" class="btn btn-light btn-icon-text btn-upload btn-progress">
            <i class="btn-icon fa fa-fw {{$icon or 'fa-upload'}}"></i>
            <b class="btn-text">{{$button}}</b>
            <div class="progress d-none">
              <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">0%</div>
            </div>            
        </button>
        @if($tools)
        <button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <div class="dropdown-menu dropdown-menu-primary dropdown-menu-right">
            @foreach($tools as $tool)
            <a class="dropdown-item js-upload-field-select" href="javascript:;" data-url="{{$tool['href']}}" data-title="{{$select}}">
                <i class="dropdown-item-icon {{$tool['icon'] or ''}}"></i>
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

    @once('field_pupload_single')
    <script type="text/javascript" src="{{Module::asset('core:plupload/jquery.upload_field.js')}}"></script>
    @endonce

    <script type="text/javascript">
    $(function(){
        $("#upload-field-{{$id}}").upload_field({!! json_encode($options) !!});
    });
    </script>
@endpush

