@extends('layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-back">
            <a href="{{route('content.model.index')}}"><i class="fa fa-angle-left"></i><b>{{trans('master.back')}}</b></a>
        </div>    
        <div class="main-title">
            {{$title}} : {{$model->name}} ({{$model->id}})</span>
        </div>
        <div class="main-title mx-auto">
            
        </div>        
        <div class="main-action">
            <a href="{{route('content.field.create', [$model->id])}}" class="btn btn-primary">
                <i class="fa fa-plus"></i> {{trans('master.create')}}
            </a>
        </div>        
    </div>
    <div class="main-body scrollable">
            
            <div class="container-fluid">
                <div class="row">
                    <div class="col-8 p-2">
                            <div class="card card-main">
                                <div class="card-header">
                                    {{trans('content::field.layout.main')}}
                                </div>
                                <div class="card-body p-0">
                                    <div class="field-container d-flex flex-wrap align-content-start" data-position="main">
                                    @foreach($main as $field)
                                        @include('content::field.item')
                                    @endforeach
                                    </div>
                                </div>
                                <div class="card-footer">

                                </div>
                            </div>                      
                    </div>
                    <div class="col-4 p-2">
                            <div class="card card-side">
                                <div class="card-header">
                                    {{trans('content::field.layout.side')}}
                                </div>
                                <div class="card-body p-0">
                                    <div class="field-container" data-position="side">
                                        @foreach($side as $field)
                                            @include('content::field.item')        
                                        @endforeach
                                    </div>
                                </div>
                                <div class="card-footer">
                                </div>
                            </div>                         
                    </div>
                </div>
            </div>

    </div><!-- main-body -->
    <div class="main-footer">
        <div class="footer-text mr-auto">
            {{trans('content::field.description')}}
        </div>
    </div>
</div>
@endsection
@push('css')
<style type="text/css">
    .field-container{min-height:360px;margin:0!important;}
    .field-card{cursor:move;padding:0.5rem!important;margin: 0rem!important;height:auto!important;}
    .field-card-placeholder{background:#00bc3e;opacity:0.5;}
    .field-card .card{background:#f6f9fc;}
    .manage{font-size:.75rem;margin-top:0.5rem;}
    
    .card-main .w-25 td.typename,
    .card-main .w-33 td.typename,
    .card-main .w-25 td.required,
    .card-main .w-33 td.required{display:none!important;}
    .card-side td.width{display:none;}
    .card-side .field-card{width:100%!important;}
</style>
@endpush
@push('js')
<script>
    $(function(){        
        $('.field-container').sortable({
            items: ".field-card",
            placeholder: "field-card-placeholder",
            connectWith: ".field-container",
             start: function(e, ui){
                 ui.placeholder.addClass(ui.item.attr("class"));
                 ui.placeholder.html(ui.item.html());
             },            
            update: function( event, ui ) {
                $(window).trigger('resize');
                var position = $(this).data('position');
                var ids = $(this).sortable('toArray', {attribute: 'id'});
                if (ids.length) {
                    $.post('{{route('content.field.sort',[$model->id])}}', {position:position, ids:ids}, function(msg) {
                        $.msg(msg);
                    },'json');
                }
            }
        }).disableSelection();
    });
</script>
@endpush
