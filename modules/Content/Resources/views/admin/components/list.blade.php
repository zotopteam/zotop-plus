<table class="table table-nowrap table-sortable table-hover {{$class}}">
    <tbody>
        @foreach($list as $content)
        <tr class="checkable-item selectable-item" data-id="{{$content->id}}" data-sort="{{$content->sort}}"
            data-stick="{{$content->stick}}" data-title="{{$content->title}}">
            @if ($sortable)
            <td class="drag"></td>
            @endif
            @if ($checkable)
            <td class="select ignore-click-selectable">
                <input type="{{$mutiple ? 'checkbox' : 'radio'}}" name="{{$mutiple ? 'ids[]' : 'ids'}}"
                    value="{{$content->id}}" class="checkable-control text-muted">
            </td>
            @endif
            <td class="text-center" width="1%">
                @if ($content->nestable)
                <i class="{{$content->model->icon}} text-warning fs-3"></i>
                @elseif ($content->image)
                <a href="javascript:;"
                    class="d-flex justify-content-center align-items-center fw-3 fh-3 overflow-hidden js-image"
                    data-url="{{$content->image}}" data-title="{{$content->title}}">
                    <img src="{{$content->image}}" class="img-fluid rounded">
                </a>
                @else
                <i class="{{$content->model->icon}} text-info fs-3"></i>
                @endif
            </td>
            <td>
                <div class="title text-truncate text-truncate-1 mb-1 ignore-click-selectable">
                    @if ($content->nestable)
                    <a href="{{$content->nestable}}">
                        {{$content->title}}
                    </a>
                    @else
                    {{$content->title}}
                    @endif
                </div>
                @if ($statusbar)
                <div class="scale-n3 scale-left">
                    <div class="badge badge-warning badge-pill" title="{{$content->model->name}}" data-toggle="tooltip">
                        <i class="{{$content->model->icon}}"></i>
                        {{$content->model->name}}
                    </div>
                    <div class="badge badge-{{$content->status_color}} badge-pill" title="{{$content->status_name}}"
                        data-toggle="tooltip">
                        <i class="{{$content->status_icon}}"></i>
                        {{$content->status_name}}
                    </div>
                    @if ($content->stick)
                    <div class="badge badge-success badge-pill" title="{{trans('content::content.sticked')}}"
                        data-toggle="tooltip">
                        <i class="fa fa-arrow-circle-up"></i>
                        {{trans('content::content.sticked')}}
                    </div>
                    @endif
                </div>
                @endif

            </td>
            <td width="1%">
                <div class="manage ignore-click-selectable">
                    @foreach (array_slice($content->action, 0, 2) as $a)
                    <a href="{{$a.href ?? 'javascript:;'}}" class="manage-item {{$a.class ?? ''}}" {!!
                        Html::attributes($a.attrs ?? []) !!}>
                        <i class="{{$a.icon ?? ''}} fa-fw"></i>
                        {{$a.text}}
                    </a>
                    @endforeach
                    @if ($extra_action = array_slice($content->action, 2))
                    <div class="manage-item dropdown">
                        <a href="javascript:;" data-toggle="dropdown" class="dropdown-trigger py-1"
                            aria-expanded="false">
                            <i class="fa fa-ellipsis-h fa-fw mt-1"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-primary">
                            @foreach ($extra_action as $a)
                            <a href="{{$a.href ?? 'javascript:;'}}" class="dropdown-item {{$a.class ?? ''}}" {!!
                                Html::attributes($a.attrs ?? []) !!}>
                                <i class="dropdown-item-icon {{$a.icon ?? ''}} fa-fw"></i>
                                <b class="dropdown-item-text"> {{$a.text}}</b>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </td>
            <td width="1%" class="text-center">
                <div title="{{trans('content::content.hits.label')}}" data-toggle="tooltip">{{$content->hits}}</div>
            </td>
            <td width="1%" class="">{{$content->user->username}}</td>
            <td width="1%" class="text-xs">
                @if (in_array($content->status,['publish']))
                <div>{{trans('content::content.publish_at.label')}}</div>
                <div>{{$content->publish_at}}</div>
                @elseif (in_array($content->status,['future']))
                <div>{{trans('content::content.status.future')}}</div>
                <div>{{$content->publish_at}}</div>
                @else
                <div>{{trans('content::content.updated_at.label')}}</div>
                <div>{{$content->updated_at}}</div>
                @endif
            </td>
        </tr>
        @endforeach

    </tbody>
</table>
@push('js')

<script>
    $(function(){
        $(document).on('click.selectable', '.selectable-item', function(e) {
            if ($(e.target).parents('.ignore-click-selectable').length == 0) {
                var check = $(this).find('.checkable-control');
                check.prop('checked', !check.prop('checked'));
                //check.prop('checked') ? $(this).addClass('selected') : $(this).removeClass('selected');
                $('.checkable').data('checkable').update();
            }
        });
    });
</script>

@if($action === true || in_array('move', $action))
<script>
    $(function(){        
        // 单个文件夹和文件移动
        $(document).on('click', 'a.js-move',function(event){            
            var operator = $(this);
            var dialog = $.dialog({
                title : operator.data('title'),
                url : operator.data('url'),
                width : '85%',
                height : '70%',
                ok : function() {
                    $.post(operator.data('url'), {id: operator.data('id'), move_to: dialog.move_to}, function (msg) {
                        $.msg(msg);
                        if (msg.type == 'success') {
                            dialog.close().remove();
                            location.reload();
                        }
                    })
                    return false;
                },
                cancel : $.noop
            }, true).loading(true);
        });
    });
</script>
@endif

@if($checkable && $mutiple)
<script>
    $(function(){
        // 拖动选择
        $('.selectable').selectable({
            filter: '.selectable-item',
            distance: 30,
            selecting:function(evnet, ui){
                $(ui.selecting).find('.checkable-control').prop('checked', true);
                $('.checkable').data('checkable').update();
            },
            unselecting:function(evnet, ui){
                $(ui.unselecting).find('.checkable-control').prop('checked', false);
                $('.checkable').data('checkable').update();
            }
        });
    });
</script>
@endif

@if ($sortable)
<script type="text/javascript">
    $(function(){
        // 拖动停止更新当前的排序及当前数据之前的数据
        var dragstop = function(evt, ui, tr) {
            
            var oldindex = tr.data('originalIndex');
            var newindex = tr.prop('rowIndex');
            
            if(oldindex == newindex) { return; }

            var prev = ui.item.siblings('tr').eq(newindex-1); // 排到这一行之后
            var next = ui.item.siblings('tr').eq(newindex); // 排到这一行之前

            var newsort = newindex == 0 ? next.data('sort') + 1 : prev.data('sort');
            var newstick= (newindex < oldindex) ? next.data('stick') : prev.data('stick');

            console.log('prev '+ prev.prop('rowIndex')+ '=' + prev.data('sort') + '=' + prev.data('stick') + '=' + prev.data('title'));
            console.log('current '+ newindex + '==' + newsort + '=' + newstick + '=' + tr.data('title'));
            console.log('next '+ next.prop('rowIndex')+ '=' +next.data('sort')+ '=' + next.data('stick') + '=' + next.data('title'));

            $.post('{{$sortable}}', {id:tr.data('id'), sort:newsort, stick:newstick}, function(data) {
                $.msg(data);
            },'json');      
        };  

        $("table.sortable").sortable({
            items: "tbody > tr",
            handle: "td.drag",
            axis: "y",
            placeholder: "ui-sortable-placeholder",
            helper: function(e,tr){
                tr.children().each(function(){
                    $(this).width($(this).width());
                });
                return tr;
            },
            start:function (event,ui) {
                ui.placeholder.height(ui.helper[0].scrollHeight);
                ui.item.data('originalIndex', ui.item.prop('rowIndex'));
            },      
            stop:function(event,ui){
                dragstop.apply(this, Array.prototype.slice.call(arguments).concat(ui.item));
            }
        });
})
</script>
@endif
@endpush
