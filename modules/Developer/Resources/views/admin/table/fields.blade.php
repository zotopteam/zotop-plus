<div class="fields-control">
    <table class="table table-sm table-sortable table-nowrap table-hover table-border table-fields">
        <thead>
            <tr>
                <td class="drag"></td>
                <td width="12%">{{trans('developer::table.fields.name')}}</td>
                <td width="15%">{{trans('developer::table.fields.type')}}</td>
                <td>{{trans('developer::table.fields.length')}}</td>
                <td width="2%" class="text-center">{{trans('developer::table.fields.nullable')}}</td>
                <td width="2%" class="text-center">{{trans('developer::table.fields.unsigned')}}</td>
                <td width="2%" class="text-center">{{trans('developer::table.fields.auto_increment')}}</td>
                <td>{{trans('developer::table.fields.index')}}</td>
                <td>{{trans('developer::table.fields.default')}}</td>
                <td>{{trans('developer::table.fields.comment')}}</td>
                <td width="2%"></td>
            </tr>
        </thead>
        <tbody>
            @foreach($fields as $k=>$v)
            <tr>
                <td class="drag"></td>
                <td>
                    <input type="text" name="fields[{{$k}}][name]" class="form-control required" value="{{$v['name']}}" fieldname="true" uniquename="true">
                </td>
                <td>
                    {field type="select" name="fields['.$k.'][type]" options="Module::data('developer::table.fields.types')" value="$v['type']" class="field-type"}
                </td>
                <td>
                    <input type="text" name="fields[{{$k}}][length]" class="form-control required" value="{{$v['length'] ?? ''}}">
                </td>
                <td class="text-center">
                    <input type="checkbox" name="fields[{{$k}}][nullable]" value="nullable" @if($v['nullable'])checked="checked"@endif>           
                </td>
                <td class="text-center">
                    <input type="checkbox" name="fields[{{$k}}][unsigned]" value="unsigned" @if($v['unsigned'])checked="checked"@endif>
                </td>
                <td class="text-center">
                    <input type="checkbox" name="fields[{{$k}}][autoIncrement]" value="autoIncrement" @if($v['autoIncrement'])checked="checked"@endif>
                </td>
                <td>
                    {field type="select" name="fields['.$k.'][index]" options="Module::data('developer::table.fields.indexes')" value="$v['index']" class="field-index"}
                </td>                                                
                <td>
                    <input type="text" name="fields[{{$k}}][default]" class="form-control" value="{{$v['default'] ?? ''}}">
                </td>
                <td>
                    <input type="text" name="fields[{{$k}}][comment]" class="form-control" value="{{$v['comment'] ?? ''}}">
                </td>
                <td class="text-center">
                    <a href="javascript:;" class="btn btn-danger btn-sm field-delete">
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
                    <a class="btn btn-primary btn-sm field-add" data-type='' href="javascript:;">
                        <i class="fa fa-plus fa-fw"></i><b>{{trans('developer::table.fields.add')}}</b>
                    </a>
                    <a class="btn btn-primary btn-sm field-add" data-type='timestamps' href="javascript:;">
                        <i class="fa fa-plus fa-fw"></i><b>{{trans('developer::table.fields.add_timestamps')}}</b>
                    </a>
                    <a class="btn btn-primary btn-sm field-add" data-type='softdeletes' href="javascript:;">
                        <i class="fa fa-plus fa-fw"></i><b>{{trans('developer::table.fields.add_softdeletes')}}</b>
                    </a>                                        
                </td>
            </tr>             
        </tfoot>
    </table>
</div>

<style type="text/css">
    .field-settings{position: relative;}
    .field-settings-more{
        display:none;
        position:absolute;z-index:10000;right:0;left:0;padding:0 0.5rem;
        background:#FFFFDB;border:solid 1px #efefef;border-top:0 none;
    }
    .table-fields tr:hover .field-settings-more{display:block;}
</style>
<script>
$(function(){
    $('[data-depend]').depend();
});

$(function(){
    // 添加字段
    $('.field-add').on('click',function(){
        var post = $('form.form').serialize();
        var type = $(this).data('type');
        if (type == 'timestamps') {
            url = "{{route('developer.table.fields','add_timestamps')}}";
        } else if (type == 'softdeletes') {
            url = "{{route('developer.table.fields','add_softdeletes')}}";
        } else {
            url = "{{route('developer.table.fields','add')}}"; 
        }
        $.post(url, post, function(result){
            $('.fields').html(result);
            $(window).trigger('resize');
        }).fail(function(jqXHR){
            $.error(jqXHR.responseJSON.message);
        });    
    });

    // 改变字段类型
    $('.field-type').on('change',function(){
        var post = $('form.form').serialize();
        $.post("{{route('developer.table.fields')}}", post, function(result){
            $('.fields').html(result);
            $(window).trigger('resize');
        });         
    });

    // 删除字段
    $('.field-delete').on('click',function(e){
        $(e.target).parents('tr').remove();
    });    
});


$(function(){
    // 字段名称检测
    $.validator.addMethod("fieldname", function(value, element) {
        return this.optional(element) || /^[a-z][a-z0-9_]{0,18}[a-z0-9]$/.test(value);
    }, "{{trans('block::block.fields.validator.fieldname')}}");

    // 字段名称唯一性检测
    $.validator.addMethod("uniquename", function(value, element) {
        var uniquename = true;
        $('input[uniquename]').not(element).each(function(){
            if ( value == $(this).val() ){
                uniquename = false;   
            }
        });
        return uniquename;
    }, "{{trans('block::block.fields.validator.uniquename')}}"); 
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
