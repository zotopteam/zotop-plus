<div class="upload-field {{$class}}" id="upload-field-{{$id}}">
    @if($preview)
        <div class="form-control form-control-preview" id="upload-{{$id}}-dragdrop">
            @if($type == 'image')
                <img src="{{$attributes->get('value')}}"/>
            @endif
        </div>
    @endif
    <div class="input-group position-relative">
        <div class="progress position-absolute pos-full full-height form-control-progress d-none" style="z-index:5;">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                 aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0">0%
            </div>
        </div>
        <input {{$attributes}} />
        <div class="input-group-append">
            @if($enable)
                <button type="button" tabindex="-1" class="btn btn-primary btn-icon-text btn-upload"
                        id="upload-{{$id}}">
                    <i class="btn-icon fa-fw {{$buttonIcon}}"></i>
                    <b class="btn-text">{{$buttonText}}</b>
                </button>
            @endif
            @if($tools)
                <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" tabindex="-1"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu dropdown-menu-primary dropdown-menu-right">
                    @foreach($tools as $tool)
                        <a class="dropdown-item js-upload-field-select" href="javascript:;" data-url="{{$tool['href']}}"
                           data-title="{{$selectText}}">
                            <i class="dropdown-item-icon {{$tool['icon'] ?? ''}}"></i>
                            <b class="dropdown-item-text">{{$tool['text']}}</b>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@push('js')
    {!! Module::load('core:plupload/plupload.full.min.js') !!}
    {!! Module::load('core:plupload/i18n/'.App::getLocale().'.js') !!}
    {!! Module::load('core:plupload/jquery.plupload.js') !!}
    {!! Module::load('core:js/field_upload.js') !!}

    <script type="text/javascript">
        $(function () {
            $("#upload-field-{{$id}}").upload_field(@json($options));
        });
    </script>
@endpush
