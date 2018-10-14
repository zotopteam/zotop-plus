<div class="fields-control">
    <table class="table table-sm table-sortable table-nowrap table-hover table-border table-fields">
        <thead>
            <tr>
                <td class="drag"></td>
                <td width="2%" class="text-center">{{trans('block::block.fields.show')}}</td>
                <td width="12%">{{trans('block::block.fields.label')}}</td>
                <td width="12%">{{trans('block::block.fields.name')}}</td>
                <td width="2%" class="text-center">{{trans('block::block.fields.required')}}</td>
                <td width="15%">{{trans('block::block.fields.type')}}</td>
                <td width="25%">{{trans('block::block.fields.setting')}}</td>
                <td>{{trans('block::block.fields.placeholder')}}</td>
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
                    <a href="javascript:;" class="field-delete"><i class="fa fa-times"></i></a>                    
                    @endif
                    <input type="hidden" name="fields[{{$k}}][show]" value="{{$v['show']}}">
                </td>
                <td>
                    <input type="text" name="fields[{{$k}}][label]" class="form-control text required" value="{{$v['label']}}" placeholder="{{trans('block::block.fields.label.placeholder')}}">
                </td>
                <td>
                    @if ($v['show'] == 2)
                    <input type="text" class="form-control text" value="{{$v['name']}}" disabled>
                    <input type="hidden" name="fields[{{$k}}][name]" class="form-control required" value="{{$v['name']}}" placeholder="{{trans('block::block.fields.name.placeholder')}}" fieldname="true" uniquename="true">
                    @else
                    <input type="text" name="fields[{{$k}}][name]" class="form-control required" value="{{$v['name']}}" placeholder="{{trans('block::block.fields.name.placeholder')}}" fieldname="true" uniquename="true">
                    @endif
                </td>
                <td class="text-center">
                    @if ($v['show'])
                    <input type="checkbox" class="disabled" checked disabled>
                    <input type="hidden" name="fields[{{$k}}][required]" value="required" checked>
                    @else
                    <input type="checkbox" name="fields[{{$k}}][required]" value="required" @if(isset($v['required']) && $v['required'])checked="checked"@endif>
                    @endif                
                </td>
                <td>
                    @if ($v['show'] == 2)
                    <input type="hidden" name="fields[{{$k}}][type]" class="form-control required" value="{{$v['type']}}">
                    {field type="select" options="Module::data('block::fields.types')" value="$v['type']" disabled="disabled"}
                    @else
                    {field type="select" name="fields['.$k.'][type]" options="Module::data('block::fields.types')" value="$v['type']" class="field-type"}
                    @endif
                </td>
                <td class="field-settings">
                    @if (in_array($v['type'], ['title','text','textarea']))
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text">{{trans('block::block.fields.length')}}</span></div>
                        <input type="number" name="fields[{{$k}}][minlength]" class="form-control required" value="{{$v['minlength'] ?? 0}}">
                        <div class="input-group-prepend"><span class="input-group-text">-</span></div>
                        <input type="number" name="fields[{{$k}}][maxlength]" class="form-control required" value="{{$v['maxlength'] ?? 255}}">
                        <div class="input-group-append"><span class="input-group-text">{{trans('block::block.fields.length.unit')}}</span></div>
                    </div>
                    @endif

                    @if (in_array($v['type'], ['upload_image']))
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text">{{trans('block::block.fields.size')}}</span></div>
                        <select name="fields[{{$k}}][resize][type]" class="form-control" id="field-settings-type-{{$k}}">
                            <option value="origin">原图</option>
                            <option value="system">跟随系统</option>
                            <option value="thunmb">缩放</option>
                            <option value="crop">裁剪</option>
                        </select>
                    </div>                                        
                    <div class="field-settings-more">
                   
                        <div class="input-group my-2" data-depend="#field-settings-type-{{$k}}" data-when="value=thunmb,crop" data-then="show">
                            <div class="input-group-prepend"><span class="input-group-text">{{trans('block::block.fields.width_height')}}</span></div>
                            <input type="number" name="fields[{{$k}}][resize][width]" class="form-control required" value="{{$v['resize']['width'] ?? ''}}">
                            <div class="input-group-prepend"><span class="input-group-text">-</span></div>
                            <input type="number" name="fields[{{$k}}][resize][height]" class="form-control required" value="{{$v['resize']['height'] ?? ''}}">
                            <div class="input-group-append"><span class="input-group-text">px</span></div>
                        </div>

                        <div class="input-group my-2">
                            <div class="input-group-prepend"><span class="input-group-text">{{trans('block::block.fields.watermark')}}</span></div>
                            <select name="fields[{{$k}}][watermark]" class="form-control">
                                <option value="false">{{trans('core::master.off')}}</option>
                                <option value="true">{{trans('core::master.on')}}</option>
                            </select>
                        </div>                                                            
                    </div>             
                    @endif
                    
                    @if (in_array($v['type'], ['textarea']))
                    <div class="field-settings-more">
                        <div class="input-group my-2">
                            <div class="input-group-prepend"><span class="input-group-text">{{trans('block::block.fields.rows')}}</span></div>
                            <input type="number" name="fields[{{$k}}][rows]" class="form-control required" value="{{$v['rows'] ?? 3}}">
                        </div>                      
                    </div>                      
                    @endif
                </td>
                <td>
                    <input type="text" name="fields[{{$k}}][placeholder]" class="form-control" value="{{$v['placeholder'] ?? ''}}">
                </td>
            </tr>
            @endforeach          
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td colspan="7">
                    <a class="btn btn-primary btn-sm field-add" href="javascript:;">
                        <i class="fa fa-plus fa-fw"></i><b>{{trans('block::block.fields.add')}}</b>
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
        $.post("{{route('block.fields','add')}}", post, function(result){
            $('.fields').html(result);
            $(window).trigger('resize');
        });        
    });

    // 改变字段类型
    $('.field-type').on('change',function(){
        var post = $('form.form').serialize();
        $.post("{{route('block.fields')}}", post, function(result){
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
