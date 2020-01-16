@extends('layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-back">
            <a href="{{route('developer.table.index', [$module])}}"><i class="fa fa-angle-left"></i><b>{{trans('master.back')}}</b></a>
        </div>
        <div class="main-title mr-auto">
            {{$title}} : {{$table}}
        </div>
        <div class="main-action">
            @if ($model)
            <a class="btn btn-danger js-confirm" href="{{route('developer.table.model', [$module, $table, 'override'])}}">
                <i class="fa fa-cube"></i> {{trans('developer::table.model.override')}}
            </a>
            @else
            <a class="btn btn-success js-confirm" href="{{route('developer.table.model', [$module, $table, 'create'])}}">
                <i class="fa fa-cube"></i> {{trans('developer::table.model.create')}}
            </a>            
            @endif

            @if ($migrations)
            <a class="btn btn-danger js-confirm" href="{{route('developer.table.migration', [$module, $table, 'override'])}}">
                <i class="fa fa-database"></i> {{trans('developer::table.migration.override')}}
            </a>
            <a class="btn btn-primary" href="{{route('developer.table.edit', [$module, $table])}}">
                <i class="fa fa-fw fa-pen-square"></i> {{trans('developer::table.edit')}}
            </a>            
            @else
            <a class="btn btn-success js-confirm" href="{{route('developer.table.migration', [$module, $table, 'create'])}}">
                <i class="fa fa-database"></i> {{trans('developer::table.migration.create')}}
            </a>            
            @endif
                    
            <a class="btn btn-primary js-delete" href="javascript:;" data-url="{{route('developer.table.drop', [$module, $table])}}">
                <i class="fa fa-times"></i> {{trans('developer::table.drop')}}
            </a>                           
        </div>            
    </div>
    
    <div class="main-body scrollable">
        
            <div class="card m-3">
                <div class="card-header">
                    <b>{{trans('developer::table.columns')}}</b>
                    <p class="card-text">
                        <span class="mr-3">{{trans('developer::table.columns.count',[count($columns)])}}</span>
                        <span class="ml-3">
                            ['{{$columns->implode('name', "','")}}']
                        </span>
                    </p>                
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-sortable table-nowrap table-striped table-hover table-columns">
                        <thead>
                            <tr>
                                <td width="2%"></td>
                                <td width="15%">{{trans('developer::table.column.name')}}</td>
                                <td width="10%">{{trans('developer::table.column.type')}}</td>
                                <td width="5%">{{trans('developer::table.column.length')}}</td>
                                <td width="8%" class="text-center">{{trans('developer::table.column.nullable')}}</td>
                                <td width="8%" class="text-center">{{trans('developer::table.column.unsigned')}}</td>
                                <td width="8%" class="text-center">{{trans('developer::table.column.increments')}}</td>
                                <td width="10%">{{trans('developer::table.column.default')}}</td>
                                <td>{{trans('developer::table.column.comment')}}</td>
                                <td width="2%"></td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($columns as $k=>$v)
                            <tr>
                                <td class="text-center">
                                    @if ($primaryColumns && in_array($v['name'], $primaryColumns))
                                        <i class="fa fa-key fa-1x text-warning"></i>
                                    @endif
                                    @if ($uniqueColumns && in_array($v['name'], $uniqueColumns))
                                        <i class="fa fa-key fa-1x text-important"></i>
                                    @endif                                    
                                    @if ($indexColumns && in_array($v['name'], $indexColumns))
                                        <i class="fa fa-key fa-1x text-success"></i>
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
                                    {{$v['default'] ?? ''}}
                                </td>
                                <td>
                                    {{$v['comment'] ?? ''}}
                                </td>
                                <td class="manage">
                                    
                                </td>                
                            </tr>
                            @endforeach          
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td colspan="11">                                     
                                </td>
                            </tr>             
                        </tfoot>
                    </table>                    
                </div>
            </div>

            <div class="card m-3">
                <div class="card-header">
                    <b>{{trans('developer::table.indexes')}}</b>
                    <p class="card-text">
                        {{trans('developer::table.indexes.count',[count($indexes)])}}
                    </p>                    
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-sortable table-nowrap table-striped table-hover table-indexes">
                        <thead>
                            <tr>
                                <td width="2%"></td>
                                <td width="15%">{{trans('developer::table.index.name')}}</td>
                                <td width="15%">{{trans('developer::table.index.type')}}</td>
                                <td>{{trans('developer::table.index.columns')}}</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($indexes as $k=>$v)
                            <tr>
                                <td class="text-center">
                                    @if ($v['type'] == 'primary')
                                        <i class="fa fa-key fa-1x text-warning"></i>
                                    @endif
                                    @if ($v['type'] == 'unique')
                                        <i class="fa fa-key fa-1x text-important"></i>
                                    @endif                                    
                                    @if ($v['type'] == 'index')
                                        <i class="fa fa-key fa-1x text-success"></i>
                                    @endif                                                         
                                </td>
                                <td>
                                    <strong>{{$v['name']}}</strong>
                                </td>
                                <td>{{$v['type']}}</td>
                                <td>{{implode(',', $v['columns'])}}</td>            
                            </tr>
                            @endforeach          
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4"></td>
                            </tr>             
                        </tfoot>
                    </table>
                </div>
            </div>                    
        
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

            var dialog = $.confirm(confirm,function(){
                dialog.loading(true);
                $.ajax({url:href,type:method,dataType:'json',data:{name:name},success:function(msg){
                    dialog.close().remove();
                    $.msg(msg);
                }});
                return false;
            }).title(text);

            event.stopPropagation();
        });
    });
</script>
@endpush
