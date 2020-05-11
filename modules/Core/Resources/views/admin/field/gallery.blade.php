<div class="form-control p-0 gallery-field" id="gallery-field-{{$id}}">
    <div class="btn-toolbar bg-light gallery-field-toolbar p-2">
        <div class="btn-group mr-1">
            <button type="button" class="btn btn-sm btn-light gallery-field-upload">
                <i class="btn-icon fa fa-upload"></i>
                <b class="btn-text">{{trans('core::field.upload.multiple')}}</b>
            </button>
        </div>
        <div class="btn-group mr-1">
            @foreach($tools as $tool)
            <button type="button" class="btn btn-sm btn-light gallery-field-select" href="javascript:;" data-url="{{$tool['href']}}" data-title="{{$select_text}}">
                <i class="btn-icon {{$tool['icon'] ?? ''}}"></i>
                <b class="btn-text">{{$tool['text']}}</b>              
            </button>
            @endforeach
        </div>      
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-light gallery-field-description" data-toggle="tooltip" title="{{trans('core::field.gallery.describe.multiple')}}">
                <i class="btn-icon fa fa-info-circle"></i>
                <b class="btn-text">{{trans('core::field.describe.multiple')}}</b>
            </button>
        </div>                
    </div>
    <div class="progress gallery-field-progress rounded-0 d-none">
      <div class="progress-bar progress-bar-striped progress-bar-animated bg-success gallery-field-progress-bar" style="width:0%">0%</div>
    </div>    
    <div class="nodata gallery-field-empty p-5 {{$value ? 'd-none' : ''}}">
        {{trans('core::field.gallery.empty')}}
    </div>
    <div class="gallery-field-list">
        
    </div>
</div>


@push('css')
    {!! Module::load('core:css/field_gallery.css') !!}
@endpush
@push('js')
    {!! Module::load('core:plupload/plupload.full.min.js') !!}
    {!! Module::load('core:plupload/i18n/'.App::getLocale().'.js') !!}
    {!! Module::load('core:plupload/jquery.plupload.js') !!}
    {!! Module::load('core:js/field_gallery.js') !!}

    @once('field_gallery')
    <script type="text/javascript">
        var field_gallery = {
            "description"    : "{{trans('core::field.gallery.description')}}",
            "delete"         : "{{trans('master.delete')}}",
            "upload_replace" : "{{trans('core::field.upload.replace')}}",
            "preview"        : "{{trans('master.view')}}"
        };
    </script>
    @endonce

    <script type="text/javascript">
    $(function(){
        $("#gallery-field-{{$id}}").gallery_field('{{$name}}',  {!! json_encode($value) !!}, {!! json_encode($options) !!});
    });
    </script>
@endpush

