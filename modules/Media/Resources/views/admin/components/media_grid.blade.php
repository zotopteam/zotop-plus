<div class="grid grid-hover p-2 {{$class}}">
    @foreach ($list as $item)
    <div class="checkable-item">
        @if ($checkable)
        <input type="{{$mutiple ? 'checkbox' : 'radio'}}" name="{{$mutiple ? 'id[]' : 'id'}}" value="{{$item->id}}"
            class="form-control-check checkable-checkbox" data-url="{{$item->url}}" data-name="{{$item->name}}"
            data-type="{{$item->type}}" />
        @endif
        <div class="grid-item {{$item->is_folder ? 'folder-item' : 'file-item'}} p-1 h-100" data-type="{{$item->type}}"
            data-link="{{$item->link}}">
            <div class="grid-item-icon fh-8">
                @if ($item->type == 'folder')
                <i class="fa fa-folder fs-6 text-warning"></i>
                @elseif ($item->type == 'image')
                <img src="{{preview($item->diskpath, 300)}}">
                @else
                <i class="{{$item->icon}} fs-6 text-info"></i>
                @endif
            </div>
            <div class="grid-item-text d-flex flex-row">
                <div class="grid-item-name text-sm text-break text-truncate text-truncate-2 mr-auto">
                    {{$item->name}}
                </div>
                <div class="grid-item-action ml-2 dropdown">
                    <a href="javascript:;" data-toggle="dropdown" class="dropdown-trigger py-1" aria-expanded="false">
                        <i class="fa fa-ellipsis-h fa-fw mt-1"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-primary">
                        @foreach ($item->action as $action)
                        <a href="{{$action.href ?? 'javascript:;'}}" class="dropdown-item {{$action.class ?? ''}}" {!!
                            Html::attributes($action.attrs ?? []) !!}>
                            <i class="dropdown-item-icon {{$action.icon ?? ''}} fa-fw"></i>
                            <b class="dropdown-item-text"> {{$action.text}}</b>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@push('js')
<script>
    $(function(){
        // 文件夹快捷操作
        $(document).on('click.folder', '.folder-item', function(e) {
            if ($(e.target).parents('.dropdown').length == 0) {
                location.href = $(this).data('link');
                return true;              
            }
        });

        // 文件快捷操作
        $(document).on('click.file', '.file-item', function(e) {
            if ($(e.target).parents('.dropdown').length == 0) {
                var type = $(this).data('type');
                if (type == 'image') {
                    $.image($(this).data('link'));
                    return true;
                }                
            }
        });
    });
</script>
@if($moveable)
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
                    $.post(operator.data('url'), {id: operator.data('id'), folder_id: dialog.folder_id}, function (msg) {
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
            filter: '.checkable-item',
            distance: 30,
            selecting:function(evnet, ui){
                $(ui.selecting).find('.checkable-checkbox').prop('checked', true);
                $('.checkable').data('checkable').update();
            },
            unselecting:function(evnet, ui){
                $(ui.unselecting).find('.checkable-checkbox').prop('checked', false);
                $('.checkable').data('checkable').update();
            }
        });
    });
</script>
@endif

@endpush
