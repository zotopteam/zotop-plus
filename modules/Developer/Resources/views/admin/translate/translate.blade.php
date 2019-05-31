@extends('core::layouts.master')

@section('content')
@include('developer::module.side')
<div class="main">
    <div class="main-header">
        <div class="main-back">
            <a href="{{route('developer.translate.index',[$module])}}"><i class="fa fa-angle-left"></i><b>{{trans('core::master.back')}}</b></a>
        </div>    
        <div class="main-title mr-auto">
            {{$title}} {{$filename}}
        </div>
        <div class="main-action">
             {field type="submit" form="translate-form" value="trans('core::master.save')" class="btn btn-primary"}
        </div>           
    </div>
    <div class="main-body scrollable">
        @if($keys)
        {form route="['developer.translate.save', $module, 'filename'=>$filename]" id="translate-form" method="post" autocomplete="off"}
        <input type="hidden" name="maxlength" value="{{$maxlength}}">
        <table class="table table-hover">
            <thead>
                <tr>
                    <td colspan="2">{{trans('developer::translate.key')}}</td>
                    @foreach ($languages as $lang=>$name)
                        <td>{{$name}}</td>
                    @endforeach
                </tr>                
            </thead>
            <tbody>
                
                @foreach($keys as $key)
                <tr>
                    <td width="1%" class="pr-2"><div class="fa fa-key text-primary"></div> </td>
                    <td class="pl-2">
                        {{$key}}
                    </td>
                    @foreach ($languages as $lang=>$name)
                        <td>
                            @if ($locale == $lang)
                                <textarea class="form-control form-locale" rows="1" name="langs[{{$lang}}][{{$key}}]">{{$langs[$lang][$key] ?? ''}}</textarea>
                            @else
                            <div class="input-group">
                                <textarea class="form-control" rows="1" name="langs[{{$lang}}][{{$key}}]">{{$langs[$lang][$key] ?? ''}}</textarea>
                                <div class="input-group-append">
                                    <button class="btn btn-light btn-translate" type="button" data-toggle="tooltip" data-from="{{$locale}}" data-to="{{$lang}}"  title="{{$languages[$locale]}} => {{$name}}">
                                        <i class="fa fa-sync"></i>
                                    </button>
                                </div>
                            </div>                                
                            @endif
                        </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <div class="nodata">{{trans('core::master.nodata')}}</div>
        @endif           
    </div>
    <div class="main-footer">
        <div class="footer-text mr-auto">
            <i class="fa fa-folder fa-fw mr-2 text-warning"></i> {{Format::path($filepath)}}
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
    $(function(){
        $(document).on('click', '.btn-translate', function() {
            var self   = $(this);
            var from   = self.data('from');
            var to     = self.data('to');
            var source = self.parents('tr').find('.form-locale').val();
            var target = self.parents('.input-group').find('textarea:first');
            
            self.addClass('disabled').find('i.fa').addClass('fa-spin');

            $.post("{{route('translator.translate')}}", {source:source,from:from,to:to}, function(result){
                self.removeClass('disabled').find('i.fa').removeClass('fa-spin');
                target.val(result);
            });
        });
    })

    $(function(){
        $('form.form').validate({
            submitHandler:function(form){                
                var validator = this;
                $('.form-submit').prop('disabled',true);
                $.loading();
                $.post($(form).attr('action'), $(form).serialize(), function(msg){
                    $.msg(msg);
                    if ( msg.state && msg.url ) {
                        location.href = msg.url;
                        return true;
                    }
                    $.loading(false);
                    $('.form-submit').prop('disabled',false);
                    return false;
                },'json').fail(function(jqXHR){
                    $('.form-submit').prop('disabled',false);
                    return validator.showErrors(jqXHR.responseJSON.errors);
                });
            }            
        });
    });
</script>
@endpush
