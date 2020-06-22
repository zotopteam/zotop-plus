<div class="grid grid-hover p-2 {{$class}}">
    @foreach($list as $content)
    <div class="grid-item checkable-item selectable-item {{$content->nestable ? 'cur-p' : ''}} p-1"
        data-id="{{$content->id}}" data-sort="{{$content->sort}}" data-stick="{{$content->stick}}"
        data-title="{{$content->title}}" data-nestable="{{$content->nestable}}">
        @if ($checkable)
        <div class="grid-item-badge js-nestable-ignore">
            <input type="{{$mutiple ? 'checkbox' : 'radio'}}" name="{{$mutiple ? 'ids[]' : 'ids'}}"
                value="{{$content->id}}" class="checkable-control d-none" id="checkable-control-{{$content->id}}">
            <label for="checkable-control-{{$content->id}}" class="checkable-control-circle hover-show"></label>
        </div>
        @endif
        <div class="grid-item-icon fh-8">
            @if ($content->nestable)
            <i class="{{$content->model->icon}} text-warning fs-6"></i>
            @elseif ($content->image)
            <img src="{{$content->image}}" class="img-fluid rounded">
            @else
            <i class="{{$content->model->icon}} text-info fs-6"></i>
            @endif
        </div>
        <div class="grid-item-text d-flex flex-row">
            <div
                class="text-sm text-break text-truncate text-truncate-2 {{$content->action ? 'mr-auto' : 'mx-auto'}} {{$content->nestable ? 'text-primary' : ''}}">
                {{$content->title}}
            </div>
            @if ($content->action)
            <div class="ml-2 dropdown js-nestable-ignore">
                <a href="javascript:;" data-toggle="dropdown" class="p-1" aria-expanded="false">
                    <i class="fa fa-ellipsis-h fa-fw mt-1"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-primary">
                    @foreach ($content->action as $a)
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
    </div>
    @endforeach
</div>

@push('js')
@if ($nestable)
<script>
    $(function(){
        $(document).on('click.nestable', '[data-nestable]', function(e) {
            if ($(e.target).parents('.js-nestable-ignore').length == 0 && $(this).data('nestable')) {
                location.href = $(this).data('nestable');
            }
        });
    });
</script>
@endif

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
@endpush
