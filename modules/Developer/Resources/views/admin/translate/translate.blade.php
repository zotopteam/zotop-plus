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
            <a href="javascript:;" class="btn btn-success js-prompt" data-url="{{route('developer.translate.newkey',[$module, 'filename'=>$filename])}}"  data-prompt="{{trans('developer::translate.key')}}" data-name="key">
                <i class="fa fa-fw fa-plus"></i> {{trans('developer::translate.key.create')}}
            </a>        
            @if($keys->count())
            <button class="btn btn-primary" type="submit" form="translate-form">
                <i class="fa fa-fw fa-save"></i> {{trans('developer::translate.translate.save')}}
            </button>
            @endif
        </div>           
    </div>
    <div class="main-body scrollable">
        @if($keys->count())
        {form route="['developer.translate.save', $module, 'filename'=>$filename]" id="translate-form" method="post" autocomplete="off"}
        <table class="table table-nowrap table-hover table-sortable">
            <thead>
                <tr>
                    <td class="drag"></td>
                    <td colspan="2">{{trans('developer::translate.key')}}</td>
                    <td width="1%" class="manage">{{trans('core::master.delete')}}</td>
                    @foreach ($languages as $lang=>$name)
                        <td>{{$name}}</td>
                    @endforeach
                </tr>                
            </thead>
            <tbody>
                
                @foreach($keys as $key)
                <tr>
                    <td class="drag"></td>
                    <td width="1%" class="pr-2"><div class="fa fa-key text-primary"></div> </td>
                    <td width="10%" class="pl-2 key">
                        <div class="font-weight-bold">{{$key}}</div>
                        <div class="text-xs">
                            {{$prefix.$key}}
                        </div>
                    </td>
                    <td class="manage">
                        <a href="javascript:;" class="manage-item js-confirm" data-url="{{route('developer.translate.deletekey',[$module, 'filename'=>$filename, 'key'=>$key])}}">
                            <i class="fa fa-times"></i> {{trans('core::master.delete')}}
                        </a>
                    </td>
                    @foreach ($languages as $lang=>$name)
                        <td class="translate-area">
                            @if ($locale == $lang)
                            <div class="input-group">
                                <input class="form-control form-locale" rows="1" name="langs[{{$lang}}][{{$key}}]" value="{{$langs[$lang][$key] ?? ''}}">
                                <div class="input-group-append">
                                    <button class="btn btn-light btn-zoom-in" type="button" data-lang="{{$name}}">
                                        <i class="fa fa-search-plus"></i>
                                    </button>
                                </div>
                            </div>
                            @else
                            <div class="input-group">
                                <input class="form-control form-others" rows="1" name="langs[{{$lang}}][{{$key}}]" value="{{$langs[$lang][$key] ?? ''}}">
                                <div class="input-group-append">
                                    <button class="btn btn-light btn-zoom-in" type="button" data-lang="{{$name}}">
                                        <i class="fa fa-search-plus"></i>
                                    </button>
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

@push('css')
<style type="text/css">
.input-group{min-width:200px;}
</style>
@endpush
@push('js')
<script type="text/javascript">
    $(function(){
        $(document).on('click', '.btn-translate', function() {
            var self   = $(this);
            var from   = self.data('from');
            var to     = self.data('to');
            var source = self.parents('tr').find('.form-locale').val();
            var target = self.parents('.input-group').find('input:first');
            
            if (self.hasClass('disabled') || target.is(':disabled')) {
                return false;
            }

            self.addClass('disabled').find('i.fa').addClass('fa-spin');

            $.post("{{route('translator.translate')}}", {source:source,from:from,to:to}, function(result){
                self.removeClass('disabled').find('i.fa').removeClass('fa-spin');
                target.val(result);
            }).fail(function(xhr, status, error) {
                self.removeClass('disabled').find('i.fa').removeClass('fa-spin');
            });
        });

        $(document).on('click', '.btn-zoom-in', function() {
            var self   = $(this);
            var input  = self.parents('.input-group').find('input:first');
            var source = self.parents('tr').find('.form-locale').val();
            var key    =  self.parents('tr').find('td.key').html();
            var lang   = self.data('lang');

            $.prompt(key+source, function(value){
                input.val(value);
            }, input.val(), 'textarea').width('50%').title(lang);
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
