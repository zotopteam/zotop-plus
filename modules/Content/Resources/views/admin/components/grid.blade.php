<div class="grid grid-hover p-2 {{$class}}">
    @foreach($list as $content)
    <div class="grid-item checkable-item selectable-item p-1" data-id="{{$content->id}}" data-sort="{{$content->sort}}"
        data-stick="{{$content->stick}}" data-title="{{$content->title}}">
        @if ($checkable)
        <div class="grid-item-badge">
            <input type="{{$mutiple ? 'checkbox' : 'radio'}}" name="{{$mutiple ? 'ids[]' : 'ids'}}"
                value="{{$content->id}}" class="checkable-control d-none">
            <div class="checkable-control-circle hover-show"></div>
        </div>
        @endif
        <div class="grid-item-icon fh-8">
            @if ($content->image)
            <img src="{{$content->image}}" class="img-fluid rounded">
            @else
            <i class="{{$content->model->icon}} fs-6 text-warning"></i>
            @endif
        </div>
        <div class="grid-item-text d-flex flex-row">
            <div class="text-sm text-break text-truncate text-truncate-1 mr-auto">
                @if ($nestable && $content->model->nestable)
                <a href="{{route('content.content.index', $content->id)}}" class="stretched-link">
                    {{$content->title}}
                </a>
                @else
                {{$content->title}}
                @endif
            </div>
            <div class="ml-2 dropdown stretched-link-ignore">
                <a href="javascript:;" data-toggle="dropdown" class="dropdown-trigger py-1" aria-expanded="false">
                    <i class="fa fa-ellipsis-h fa-fw mt-1"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-primary">
                    @foreach ($content->action as $action)
                    <a href="{{$action.href ?? 'javascript:;'}}" class="dropdown-item {{$action.class ?? ''}}" {!!
                        Html::attributes($action.attrs ?? []) !!}>
                        <i class="dropdown-item-icon {{$action.icon ?? ''}} fa-fw"></i>
                        <b class="dropdown-item-text"> {{$action.text}}</b>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="scale-n3 scale-left p-2 mt-n2">
            <div class="badge badge-warning badge-pill" title="{{$content->model->name}}" data-toggle="tooltip">
                <i class="{{$content->model->icon}}"></i>
                {{$content->model->name}}
            </div>
            <div class="badge badge-info badge-pill" title="{{$content->status_name}}" data-toggle="tooltip">
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
    </div>
    @endforeach

</div>

@push('css')
<style>

</style>
@endpush

@push('js')

<script>
    $(function(){
        $(document).on('click.selectable', '.selectable-item', function(e) {
            if ($(e.target).parents('.ignore-click-selectable').length == 0) {
                var check = $(this).find('.checkable-control');
                check.prop('checked', !check.prop('checked'));
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
