@extends('core::layouts.master')

@section('content')

@include('core::config.side')

<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>
    </div>
    <div class="main-body scrollable">
        <div class="container-fluid">

            {form model="$config" route="core.config.upload" method="post" id="config" autocomplete="off"}
            <div class="form-title row">{{trans('core::config.upload.base')}}</div>

            <div class="form-group row">
                <label for="types" class="col-2 col-form-label required">{{trans('core::config.upload.types.label')}}</label>
                <div class="col-8">
                
                    <table class="table table-nowrap form-control">
                        <thead>
                            <tr>
                                <th width="10%">{{trans('core::config.upload.types.type')}}</th>
                                <th>{{trans('core::config.upload.types.extensions')}}</th>
                                <th width="25%">{{trans('core::config.upload.types.maxsize')}}</th>
                                <th width="12%" class="text-center">{{trans('core::config.upload.types.enabled')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (Module::data('core::file.types') as $type=>$name)
                            <tr>
                                <td>{{$name}}</td>
                                <td>{field type="text" name="upload[types]['.$type.'][extensions]"}</td>
                                <td>
                                    <div class="input-group">
                                        {field type="number" name="upload[types]['.$type.'][maxsize]" min="0" required="required}
                                        <div class="input-group-append"><span class="input-group-text">MB</span></div>
                                    </div>
                                </td>
                                <td class="text-center">{field type="toggle" name="upload[types]['.$type.'][enabled]"}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                                       
                    @if ($errors->has('types'))
                    <span class="form-help text-error">{{ $errors->first('types') }}</span>
                    @else
                    <span class="form-help">{{trans('core::config.upload.types.help')}}</span>
                    @endif

                </div>
            </div>

            <div class="form-group row">
                <label for="dir" class="col-2 col-form-label required">{{trans('core::config.upload.dir.label')}}</label>
                <div class="col-8">
                    {field type="radiogroup" name="upload[dir]" options="Module::data('core::upload.dir')"}
                    
                    @if ($errors->has('dir'))
                    <span class="form-help text-error">{{ $errors->first('dir') }}</span>
                    @else
                    <span class="form-help">{{trans('core::config.upload.dir.help')}}</span>
                    @endif
                </div>
            </div>


            <div class="form-title row">{{trans('core::image.resize')}}</div>
            
            <div class="form-group row">
                <label for="resize_enabled" class="col-2 col-form-label">{{trans('core::image.resize.enabled')}}</label>
                <div class="col-8">
                    {field type="toggle" name="image[resize][enabled]"}
                    
                    @if ($errors->has('enabled'))
                    <span class="form-help text-error">{{ $errors->first('enabled') }}</span>
                    @endif
                </div>
            </div>            

            <div class="form-group row">
                <label for="resize_max" class="col-2 col-form-label">{{trans('core::image.resize.max')}}</label>
                <div class="col-5">
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text">{{trans('core::image.resize.width')}}</span></div>
                        {field type="number" name="image[resize][width]" min="0"}
                        <div class="input-group-prepend"><span class="input-group-text">{{trans('core::image.resize.height')}}</span></div>
                        {field type="number" name="image[resize][height]" min="0"}
                        <div class="input-group-append"><span class="input-group-text">px</span></div>
                    </div>
                    
                    @if ($errors->has('max'))
                    <span class="form-help text-error">{{ $errors->first('max') }}</span>
                    @else
                    <span class="form-help">{{trans('core::image.resize.max.help')}}</span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="resize_quality" class="col-2 col-form-label">
                    {{trans('core::image.resize.quality')}}
                </label>
                <div class="col-5">
                    {field type="number" name="image[resize][quality]" min="0" max="100"}
                    
                    @if ($errors->has('quality'))
                    <span class="form-help text-error">{{ $errors->first('quality') }}</span>
                    @else
                    <span class="form-help">{{trans('core::image.resize.quality.help')}}</span>
                    @endif
                </div>
            </div>
            <div class="form-title row">{{trans('core::image.watermark')}}</div>
            
            <div class="form-group row">
                <label for="image_watermark_enabled" class="col-2 col-form-label">
                    {{trans('core::image.watermark.enabled')}}
                </label>
                <div class="col-8">
                    {field type="toggle" name="image[watermark][enabled]"}
                    
                    @if ($errors->has('enabled'))
                    <span class="form-help text-error">{{ $errors->first('enabled') }}</span>
                    @endif
                </div>
            </div>            

            <div class="form-group row">
                <label for="image_watermark_min" class="col-2 col-form-label">{{trans('core::image.watermark.min')}}</label>
                <div class="col-5">
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text">{{trans('core::image.watermark.width')}}</span></div>
                        {field type="number" name="image[watermark][width]" min="0"}
                        <div class="input-group-prepend"><span class="input-group-text">{{trans('core::image.watermark.height')}}</span></div>
                        {field type="number" name="image[watermark][height]" min="0"}
                        <div class="input-group-append"><span class="input-group-text">px</span></div>
                    </div>
                    
                    @if ($errors->has('min'))
                    <span class="form-help text-error">{{ $errors->first('min') }}</span>
                    @else
                    <span class="form-help">{{trans('core::image.watermark.min.help')}}</span>
                    @endif
                </div>
            </div>

            <div class="form-group row" id="watermark-type">
                <label for="image_watermark_type" class="col-2 col-form-label">
                    {{trans('core::image.watermark.type')}}
                </label>
                <div class="col-5">
                    {field type="radiogroup" name="image[watermark][type]" options="Module::data('core::watermark.type')"}
                </div>
            </div>
            <div class="watermark-options" rel="text" data-depend="#watermark-type :radio" data-when="value=text" data-then="show">
                <div class="form-group row">
                    <label for="image_watermark_font" class="col-2 col-form-label">
                        {{trans('core::image.watermark.font')}}
                    </label>
                    <div class="col-8">
                         {field type="radiocards" name="image[watermark][font][file]" options="Module::data('core::watermark.font.file')"}
                    </div>
                </div>
                <div class="form-group row">
                    <label for="image_watermark_font" class="col-2 col-form-label">
                        {{trans('core::image.watermark.font.style')}}
                    </label>
                    <div class="col-3">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">{{trans('core::image.watermark.font.size')}}</span></div>
                            {field type="select" name="image[watermark][font][size]" options="Module::data('core::watermark.font.size')"}
                            <div class="input-group-append"><span class="input-group-text">px</span></div>                
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">{{trans('core::image.watermark.font.color')}}</span></div>
                            {field type="text" name="image[watermark][font][color]"}
                        </div>                
                    </div>                
                </div>            
                <div class="form-group row">
                    <label for="image_watermark_text" class="col-2 col-form-label">
                        {{trans('core::image.watermark.text')}}
                    </label>
                    <div class="col-8">
                         {field type="text" name="image[watermark][text]"}
                    </div>
                </div>
            </div>
            <div class="watermark-options" rel="image" data-depend="#watermark-type :radio" data-when="value=image" data-then="show">
                <div class="form-group row">
                    <label for="image_watermark_image" class="col-2 col-form-label">
                        {{trans('core::image.watermark.image')}}
                    </label>
                    <div class="col-8">
                        {field type="upload_image" name="image[watermark][image]" allow="png" resize="false" watermark="false" tools="false"}
                        @if ($errors->has('image.watermark.image'))
                        <span class="form-help text-error">{{ $errors->first('image.watermark.image') }}</span>
                        @else
                        <span class="form-help">{{trans('core::image.watermark.image.help')}}</span>
                        @endif                         
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="image_watermark_position" class="col-2 col-form-label">
                    {{trans('core::image.watermark.position')}}
                </label>
                <div class="col-8">
                    <table class="table table-nowrap form-control d-inline-block w-auto">
                        <tbody>
                            <tr>
                            @foreach (Module::data('core::watermark.position') as $value=>$postion)
                                <td class="text-center">
                                    <label class="radio m-0">
                                    {field type="radio" name="image[watermark][position]" value="$value"}
                                    {{$postion}}
                                    </label>
                                </td>
                                @if($loop->iteration%3==0 && $loop->iteration < $loop->count)
                                </tr>
                                <tr>
                                @endif
                            @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="form-group row">
                <label for="image_watermark_offset" class="col-2 col-form-label">{{trans('core::image.watermark.offset')}}</label>
                <div class="col-5">
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text">{{trans('core::image.watermark.offset.x')}}</span></div>
                        {field type="number" name="image[watermark][offset][x]" min="0"}
                        <div class="input-group-prepend"><span class="input-group-text">{{trans('core::image.watermark.offset.y')}}</span></div>
                        {field type="number" name="image[watermark][offset][y]" min="0"}
                        <div class="input-group-append"><span class="input-group-text">px</span></div>
                    </div>
                    
                    @if ($errors->has('offset'))
                    <span class="form-help text-error">{{ $errors->first('offset') }}</span>
                    @else
                    <span class="form-help">{{trans('core::image.watermark.offset.help')}}</span>
                    @endif
                </div>
            </div>            
            <div class="form-group row">
                <label for="watermark_opacity" class="col-2 col-form-label">
                    {{trans('core::image.watermark.opacity')}}
                </label>
                <div class="col-5">
                    {field type="number" name="image[watermark][opacity]" min="1" max="100"}
                    
                    @if ($errors->has('watermark.opacity'))
                    <span class="form-help text-error">{{ $errors->first('watermark.opacity') }}</span>
                    @else
                    <span class="form-help">{{trans('core::image.watermark.opacity.help')}}</span>
                    @endif
                </div>
            </div>                  
            <div class="form-group row">
                <label for="watermark_quality" class="col-2 col-form-label">
                    {{trans('core::image.watermark.quality')}}
                </label>
                <div class="col-5">
                    {field type="number" name="image[watermark][quality]" min="0" max="100"}
                    
                    @if ($errors->has('watermark.quality'))
                    <span class="form-help text-error">{{ $errors->first('watermark.quality') }}</span>
                    @else
                    <span class="form-help">{{trans('core::image.watermark.quality.help')}}</span>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <label for="watermark_test" class="col-2 col-form-label">
                </label>
                <div class="col-5">
                    <div class="btn btn-secondary" id="watermark-test">
                        {{trans('core::image.watermark.test')}}
                    </div>
                </div>
            </div>                                                                                  
            {/form}
        </div>
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="mr-auto">
            {field type="submit" form="config" value="trans('core::master.save')" class="btn btn-primary"}           
        </div>
    </div>
    
</div>

@endsection

@push('js')
<script type="text/javascript">

    $(function(){
        $('#watermark-test').on('click',function(){
            var data = $('form.form').serialize();
            var title = $(this).text();
            var dialog = $.dialog({
                    title: title,
                    content: '',
                    width: '50%',
                    height: '60%',
                    ok: true,
                    padding:0
            }, true).loading(true);

            $.post("{{route('core.config.watermarktest')}}", data, function(msg){
                dialog.content('<a href="'+msg.content+'" target="_blank">' +
                 '  <div class="image-preview bg-image-preview full-height full-width d-flex justify-content-center p-3">' +
                 '      <img src="'+msg.content+'" class="align-self-center">' +
                 '  </div>'+
                 '</a>');
            },'json');
        });
    });

    $(function(){
        $('form.form').validate({       
            submitHandler:function(form){                
                var validator = this;

                $('.form-submit').prop('disabled',true);

                $.post($(form).attr('action'), $(form).serialize(), function(msg){
                    
                    $.msg(msg);

                    if ( msg.state && msg.url ) {
                        location.href = msg.url;
                        return true;
                    }

                    $('.form-submit').prop('disabled',false);
                    return false;

                },'json').fail(function(jqXHR){                    
                    $('.form-submit').prop('disabled',false);
                    return validator.showErrors(jqXHR.responseJSON.errors);
                });
            }            
        });
    })
</script>
@endpush
