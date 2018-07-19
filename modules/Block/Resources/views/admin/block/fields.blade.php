<div class="fields-control">
    <table class="table table-sm table-sortable table-nowrap table-hover table-border">
        <thead>
            <tr>
                <td class="drag"></td>
                <td width="2%" class="text-center">显示</td>
                <td width="15%">名称</td>
                <td width="15%">标识</td>
                <td width="2%" class="text-center">必填</td>
                <td width="15%">控件类型</td>
                <td>提示</td>
            </tr>
        </thead>
        <tbody>
            @foreach($fields as $k=>$v)
            <tr>
                <td class="drag"></td>
                <td class="text-center">
                    @if ($v['show'])
                    <input type="checkbox" class="disabled" checked disabled>
                    @else
                    <a href="javascript:;" onclick="field.delete(this)"><i class="fa fa-times"></i></a>                    
                    @endif
                    <input type="hidden" name="fields[{{$k}}][show]" value="{{$v['show']}}">
                </td>
                <td>
                    <input type="text" name="fields[{{$k}}][label]" class="form-control text required" value="{{$v['label']}}" placeholder="名称">
                </td>
                <td>
                    @if ($v['show'] == 2)
                    <input type="text" class="form-control text" value="{{$v['name']}}" disabled>
                    <input type="hidden" name="fields[{{$k}}][name]" class="form-control required" value="{{$v['name']}}" placeholder="标识" fieldname="true" uniquename="true">
                    @else
                    <input type="text" name="fields[{{$k}}][name]" class="form-control required" value="{{$v['name']}}" placeholder="标识" fieldname="true" uniquename="true">
                    @endif
                </td>
                <td class="text-center">
                    @if ($v['show'])
                    <input type="checkbox" class="disabled" checked disabled>
                    <input type="hidden" name="fields[{{$k}}][required]" value="required" checked>
                    @else
                    <input type="checkbox" name="fields[{{$k}}][required]" value="required" @if($v['required'])checked="checked"@endif>
                    @endif                
                </td>
                <td>
                    @if ($v['show'] == 2)
                    <input type="hidden" name="fields[{{$k}}][type]" class="form-control required" value="{{$v['type']}}">
                    {field type="select" options="Module::data('block::field.types')" value="$v['type']" disabled="disabled"}
                    @else
                    {field type="select" name="fields['.$k.'][type]" options="Module::data('block::field.types')" value="$v['type']"}
                    @endif
                </td>
                <td>
                    <input type="text" name="fields[{{$k}}][placeholder]" class="form-control" value="{{$v['placeholder'] or ''}}">
                </td>
            </tr>
            @endforeach          
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td colspan="6">
                    <a class="btn btn-primary btn-sm" href="javascript:;" onclick="field.add(this)"><i class="fa fa-plus fa-fw"></i><b>{{trans('添加字段')}}</b></a>
                </td>
            </tr>             
        </tfoot>
    </table>
</div>


<script>
var field = {}
field.add = function() {
    var post = $('form.form').serialize();
    $.post("{{route('block.fields','add')}}", post, function(result){
        $('.fields').html(result);
        $(window).trigger('resize');
    });         
}

field.changetype = function(obj){
    var post = $('form.form').serialize();
    $.post("{U('block/admin/postextend')}",post,function(result){
        $('.extend').html(result);
    });         
}

field.delete = function(obj){
    $(obj).parents('tr').remove();
}

$(function(){
        $.validator.addMethod("fieldname", function(value, element) {
            return this.optional(element) || /^[a-z][a-z0-9_]{0,18}[a-z0-9]$/.test(value);
        }, "{{trans('长度2-20，允许小写英文字母、数字和下划线，并且仅能字母开头，不以下划线结尾')}}");

        $.validator.addMethod("uniquename", function(value, element) {
            var uniquename = true;
            $('input[uniquename]').not(element).each(function(){
                if ( value == $(this).val() ){
                    uniquename = false;   
                }
            });
            return uniquename;
        }, "{{trans('标识已经存在，请使用其它标识')}}"); 
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
