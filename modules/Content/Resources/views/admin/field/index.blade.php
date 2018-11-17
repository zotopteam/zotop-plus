@extends('core::layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-back">
            <a href="{{route('content.model.index')}}"><i class="fa fa-angle-left"></i><b>{{trans('core::master.back')}}</b></a>
        </div>    
        <div class="main-title">
            {{$title}} : {{$model->name}} ({{$model->id}})</span>
        </div>
        <div class="main-title mx-auto">
            
        </div>        
        <div class="main-action">
            <a href="{{route('content.field.create', [$model->id])}}" class="btn btn-primary">
                <i class="fa fa-plus"></i> {{trans('core::master.create')}}
            </a>
        </div>        
    </div>
    <div class="main-body scrollable">
            
            <div class="container-fluid">
                <div class="row">
                    <div class="col-7">
                            <div class="card card-left my-3">
                                <div class="card-header">
                                    {{trans('content::field.layout.main')}}
                                </div>
                                <div class="card-body p-0">

                                    <div class="field-container" data-col="0">
                                    @foreach($main as $field)
                                        @include('content::field.item')
                                    @endforeach
                                    </div>
                                </div>
                                <div class="card-footer">

                                </div>
                            </div>                      
                    </div>
                    <div class="col-5">
                            <div class="card card-right my-3">
                                <div class="card-header">
                                    {{trans('content::field.layout.side')}}
                                </div>
                                <div class="card-body p-0">
                                    <div class="field-container" data-col="1">
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
    .field-card{cursor:move;padding:0!important;margin: 0.5rem!important;}
    .field-card-placeholder{border:solid 1px green;min-height:60px;}
    .manage{font-size:.75rem;margin-top:0.5rem;}
</style>
@endpush
@push('js')
<script>
    $(function(){        
        $('.field-container').sortable({
            items: ".field-card",
            placeholder: "card m-2 p-2 field-card-placeholder",
            connectWith: ".field-container",
            update: function( event, ui ) {
                $(window).trigger('resize');
                var col = $(this).data('col');
                var ids = $(this).sortable('toArray', {attribute: 'id'});
                console.log(col);
                console.log(ids);
                if (ids.length) {
                    $.post('{{route('content.field.sort',[$model->id])}}', {col:col, ids:ids}, function(msg) {
                        $.msg(msg);
                    },'json');
                }
            }
        }).disableSelection();
    });
</script>
@endpush
