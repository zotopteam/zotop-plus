<div class="form-group">
    <label class="form-label required">{{trans('developer::table.columns')}}</label>
    <div class="form-field">
        <div class="columns-control">
            <table class="table table-sm table-sortable table-nowrap table-hover table-border table-columns">
                <thead>
                    <tr>
                        <td class="drag"></td>
                        <td width="2%" class="text-center">{{trans('developer::table.column.index')}}</td>
                        <td width="15%">{{trans('developer::table.column.name')}}</td>
                        <td width="15%">{{trans('developer::table.column.type')}}</td>
                        <td width="10%">{{trans('developer::table.column.length')}}</td>
                        <td width="4%" class="text-center">{{trans('developer::table.column.nullable')}}</td>
                        <td width="4%" class="text-center">{{trans('developer::table.column.unsigned')}}</td>
                        <td width="4%" class="text-center">{{trans('developer::table.column.increments')}}</td>
                        <!-- <td width="15%">{{trans('developer::table.column.index')}}</td> -->
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
                            @if ($increments == $v['name'])
                                <input type="checkbox" disabled>
                            @else
                                <input type="checkbox" name="columns[{{$k}}][select]" value="select">
                            @endif
                        </td>
                        <td>
                            <input type="text" name="columns[{{$k}}][name]" class="form-control column-check required" value="{{$v['name']}}" columnname="true" uniquename="true">
                        </td>
                        <td>
                            {field type="select" name="columns['.$k.'][type]" options="Module::data('developer::table.column.types')" value="$v['type']" class="column-check"}
                        </td>
                        <td>
                            <input type="text" name="columns[{{$k}}][length]" class="form-control" value="{{$v['length'] ?? ''}}" class="column-check">
                        </td>
                        <td class="text-center">
                            @if (! $v['increments'])
                            <input type="checkbox" name="columns[{{$k}}][nullable]" value="nullable" @if($v['nullable'])checked="checked"@endif class="column-check">
                            @else
                            <input type="checkbox" disabled>
                            @endif         
                        </td>
                        <td class="text-center">
                            @if (ends_with($v['type'], 'int'))
                            <input type="checkbox" name="columns[{{$k}}][unsigned]" value="unsigned" @if($v['unsigned'])checked="checked"@endif>
                            @else
                            <input type="checkbox" disabled>                    
                            @endif
                        </td>
                        <td class="text-center">
                            @if (ends_with($v['type'], 'int') && !$v['nullable'] && (empty($increments) || $increments == $v['name']))
                            <input type="checkbox" name="columns[{{$k}}][increments]" value="increments" @if($v['increments'])checked="checked"@endif class="column-check">
                            @else
                            <input type="checkbox" disabled>                    
                            @endif
                        </td>
<!--                         <td>
                            @if (! ends_with($v['type'], 'text'))
                            {field type="select" name="columns['.$k.'][index]" options="Module::data('developer::table.column.indexes')" value="$v['index']" class="column-check"}
                            @endif
                        </td>  -->                                               
                        <td>
                            <input type="text" name="columns[{{$k}}][default]" class="form-control" value="{{$v['default'] ?? ''}}">
                        </td>
                        <td>
                            <input type="text" name="columns[{{$k}}][comment]" class="form-control" value="{{$v['comment'] ?? ''}}">
                        </td>
                        <td class="text-center">
                            <a href="javascript:;" class="btn btn-danger btn-sm column-delete" data-column="{{$k}}">
                                <i class="fa fa-times"></i>
                            </a>    
                        </td>                
                    </tr>
                    @endforeach          
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td colspan="10">
                            <a class="btn btn-success btn-sm column-action"  href="javascript:;" data-url="{{route('developer.table.columns','add')}}">
                                <i class="fa fa-plus fa-fw"></i> {{trans('developer::table.column.add')}} 
                            </a>
                            <a class="btn btn-primary btn-sm column-action" href="javascript:;" data-url="{{route('developer.table.columns','add_timestamps')}}">
                                <i class="fa fa-plus fa-fw"></i> {{trans('developer::table.column.add_timestamps')}} 
                            </a>
                            <a class="btn btn-primary btn-sm column-action"  href="javascript:;" data-url="{{route('developer.table.columns','add_softdeletes')}}">
                                <i class="fa fa-plus fa-fw"></i> {{trans('developer::table.column.add_softdeletes')}} 
                            </a>
                            @if (! $increments)
                            <a class="btn btn-primary btn-sm column-action"  href="javascript:;" data-url="{{route('developer.table.columns','primary')}}">
                                <i class="fa fa-key fa-fw"></i> {{trans('developer::table.index.primary')}} 
                            </a>
                            @endif
                            <a class="btn btn-primary btn-sm column-action" href="javascript:;" data-url="{{route('developer.table.columns','index')}}">
                                <i class="fa fa-key fa-fw"></i> {{trans('developer::table.index.index')}}
                            </a>
                            <a class="btn btn-primary btn-sm column-action"  href="javascript:;" data-url="{{route('developer.table.columns','unique')}}">
                                <i class="fa fa-key fa-fw"></i> {{trans('developer::table.index.unique')}}
                            </a>                                                                       
                        </td>
                    </tr>             
                </tfoot>
            </table>
        </div>
    </div>
</div>

@if($indexes)
<div class="form-group">
    <label class="form-label required">{{trans('developer::table.indexes')}}</label>
    <div class="form-field">
        <div class="indexes-control">
            <table class="table table-sm table-nowrap table-hover table-border table-indexes">
                <thead>
                    <tr>
                        <td width="15%">{{trans('developer::table.column.name')}}</td>
                        <td width="15%">{{trans('developer::table.column.type')}}</td>
                        <td></td>
                        <td width="2%"></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($indexes as $k=>$v)
                    <tr>
                        <td>
                            {{$v['name']}}
                            <input type="hidden" name="indexes[{{$k}}][name]" value="{{$v['name']}}">
                        </td>
                        <td>
                            {{$v['type']}}
                            <input type="hidden" name="indexes[{{$k}}][type]" value="{{$v['type']}}">
                        </td>
                        <td>
                            {{implode(',', $v['columns'])}}
                            @foreach($v['columns'] as $c)
                            <input type="hidden" name="indexes[{{$k}}][columns][]" value="{{$c}}">
                            @endforeach        
                        </td>
                        <td class="text-center">
                            <a href="javascript:;" class="btn btn-danger btn-sm index-delete" data-index="{{$k}}">
                                <i class="fa fa-times"></i>
                            </a>    
                        </td>                
                    </tr>
                    @endforeach          
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4">
                                       
                        </td>
                    </tr>             
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endif

<script>
$(function(){
    // 字段操作
    $('.column-action').on('click',function(){
        var post = $('form.form').serialize();
        var href = $(this).data('url');
        $.post(href, post, function(result){
            $('.columns').html(result);
            $(window).trigger('resize');
        }).fail(function(jqXHR){
            $.error(jqXHR.responseJSON.message);
        });    
    });

    // 改变字段类型
    $('.column-check').on('change',function(){
        var post = $('form.form').serialize();
        $.post("{{route('developer.table.columns')}}", post, function(result){
            $('.columns').html(result);
            $(window).trigger('resize');
        }).fail(function(jqXHR){
            $.error(jqXHR.responseJSON.message);
        });        
    });

    // 删除字段
    $('.column-delete').on('click',function(e){
        $(e.target).parents('tr').remove();
        var post = $('form.form').serialize();
        $.post("{{route('developer.table.columns')}}", post, function(result){
            $('.columns').html(result);
            $(window).trigger('resize');
        }).fail(function(jqXHR){
            $.error(jqXHR.responseJSON.message);
        });         
    });

    // 删除字段
    $('.index-delete').on('click',function(e){
        $(e.target).parents('tr').remove();        
    });  
});


$(function(){
    // 字段名称检测
    $.validator.addMethod("columnname", function(value, element) {
        return this.optional(element) || /^[a-z][a-z0-9_]{0,18}[a-z0-9]$/.test(value);
    }, "{{trans('developer::table.column.validator.columnname')}}");

    // 字段名称唯一性检测
    $.validator.addMethod("uniquename", function(value, element) {
        var uniquename = true;
        $('input[uniquename]').not(element).each(function(){
            if ( value == $(this).val() ){
                uniquename = false;   
            }
        });
        return uniquename;
    }, "{{trans('developer::table.column.validator.uniquename')}}"); 
});

//表格行排序 sortable
$(function(){

    $("table.table-sortable").each(function(index,table){
        $(table).sortable({
            items: "tbody > tr",
            handle: "td.drag",
            axis: "y",
            placeholder:"ui-sortable-placeholder",
            helper: function(e,tr){
                tr.children().each(function(){
                    $(this).width($(this).width());
                });
                return tr;
            },
            update:function(){

            }
        });        
    });

});
</script>
