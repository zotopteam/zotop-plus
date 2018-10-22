@extends('core::layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-back">
            <a href="{{route('developer.table.index', [$module])}}"><i class="fa fa-angle-left"></i><b>{{trans('core::master.back')}}</b></a>
        </div>
        <div class="main-title mr-auto">
            {{$title}} : {{$table}}
        </div>
        <div class="main-action">
            @if ($migrations)
            <a class="btn btn-danger js-confirm" href="{{route('developer.table.migration', [$module, $table, 'override'])}}">
                <i class="fa fa-database"></i> {{trans('developer::table.migration.override')}}
            </a>
            @else
            <a class="btn btn-success js-confirm" href="{{route('developer.table.migration', [$module, $table, 'create'])}}">
                <i class="fa fa-database"></i> {{trans('developer::table.migration.create')}}
            </a>            
            @endif

            @if ($updatelogs)
            <a class="btn btn-success js-post" href="{{route('developer.table.migration', [$module, $table, 'update'])}}">
                <i class="fa fa-database"></i> {{trans('developer::table.migration.update')}}
            </a>                   
            @endif

            <a class="btn btn-primary js-prompt" href="{{route('developer.table.operate', [$module, $table, 'rename'])}}" data-prompt="{{trans('developer::table.name')}}" data-name="name" data-value="{{$table}}">
                <i class="fa fa-fw fa-eraser"></i> {{trans('developer::table.rename')}}
            </a>                      
            <a class="btn btn-primary js-delete" href="javascript:;" data-url="{{route('developer.table.drop', [$module, $table])}}">
                <i class="fa fa-times"></i> {{trans('developer::table.drop')}}
            </a>                           
        </div>            
    </div>
    
    <div class="main-body scrollable">
        <table class="table table-sm table-sortable table-nowrap table-striped table-hover table-border table-columns">
            <thead>
                <tr>
                    <td class="drag"></td>
                    <td width="2%"></td>
                    <td width="15%">{{trans('developer::table.column.name')}}</td>
                    <td width="15%">{{trans('developer::table.column.type')}}</td>
                    <td width="10%">{{trans('developer::table.column.length')}}</td>
                    <td width="4%" class="text-center">{{trans('developer::table.column.nullable')}}</td>
                    <td width="4%" class="text-center">{{trans('developer::table.column.unsigned')}}</td>
                    <td width="4%" class="text-center">{{trans('developer::table.column.increments')}}</td>
                    <td width="15%">{{trans('developer::table.column.index')}}</td>
                    <td>{{trans('developer::table.column.default')}}</td>
                    <td>{{trans('developer::table.column.comment')}}</td>
                    <td width="2%"></td>
                </tr>
            </thead>
            <tbody>
                @foreach($columns as $k=>$v)
                <tr>
                    <td class="drag"></td>
                    <td class="text-center">
                        @if ($primary && in_array($v['name'], $primary))
                            <i class="fa fa-key fa-1x text-warning"></i>
                        @endif                        
                    </td>
                    <td>
                        <strong>{{$v['name']}}</strong>
                    </td>
                    <td>{{$v['type']}}</td>
                    <td>{{$v['length'] ?? ''}}</td>
                    <td class="text-center">
                        @if($v['nullable'])
                            <i class="fa fa-check-circle fa-1x text-success"></i>
                        @else
                            <i class="fa fa-times-circle fa-1x text-muted"></i>                            
                        @endif      
                    </td>
                    <td class="text-center">
                        @if($v['unsigned'])
                            <i class="fa fa-check-circle fa-1x text-success"></i>
                        @else
                            <i class="fa fa-times-circle fa-1x text-muted"></i>                            
                        @endif
                    </td>
                    <td class="text-center">
                        @if($v['increments'])
                            <i class="fa fa-check-circle fa-1x text-success"></i>
                        @else
                            <i class="fa fa-times-circle fa-1x text-muted"></i>
                        @endif
                    </td>
                    <td>
                        {{$v['index'] ?? ''}}
                    </td>                                                
                    <td>
                        {{$v['default'] ?? ''}}
                    </td>
                    <td>
                        {{$v['comment'] ?? ''}}
                    </td>
                    <td class="manage">
                        @if (count($columns) >1)                        
                        <a href="javascript:;" class="manage-item js-drop" data-url="{{route('developer.table.operate', [$module, $table, 'dropColumn'])}}" data-name="{{$v['name']}}">
                            <i class="fa fa-times"></i> {{trans('core::master.delete')}}
                        </a>
                        @endif  
                    </td>                
                </tr>
                @endforeach          
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td colspan="11">
                        <a class="btn btn-primary btn-sm field-add" data-type='' href="javascript:;">
                            <i class="fa fa-plus fa-fw"></i><b>{{trans('developer::table.column.add')}}</b>
                        </a>
                        <a class="btn btn-primary btn-sm field-add" data-type='timestamps' href="javascript:;">
                            <i class="fa fa-plus fa-fw"></i><b>{{trans('developer::table.column.add_timestamps')}}</b>
                        </a>
                        <a class="btn btn-primary btn-sm field-add" data-type='softdeletes' href="javascript:;">
                            <i class="fa fa-plus fa-fw"></i><b>{{trans('developer::table.column.add_softdeletes')}}</b>
                        </a>                                        
                    </td>
                </tr>             
            </tfoot>
        </table>
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="mr-auto">
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
    $(function(){
        $(document).on('click', 'a.js-drop', function(event){
            event.preventDefault();

            var href    = $(this).data('url');
            var text    = $(this).text();
            var confirm = $(this).data('confirm') || $.trans('您确定要 {0} 嘛?', text);
            var method  = $(this).data('method') || 'DELETE';
            var name  = $(this).data('name');

            var $dialog = $.confirm(confirm,function(){
                $dialog.loading(true);
                $.ajax({url:href,type:method,dataType:'json',data:{name:name},success:function(msg){
                    $dialog.close().remove();
                    $.msg(msg);
                }});
                return false;
            }).title(text);

            event.stopPropagation();
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
