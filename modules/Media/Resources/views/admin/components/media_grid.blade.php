<div class="grid grid-hover p-2 {{$class}} ias-container">
    @foreach ($list as $item)
        <div class="grid-item checkable-item selectable-item ias-item  {{$item->is_folder ? 'folder-item' : 'file-item'}} p-1 h-100 cur-p"
             data-type="{{$item->type}}" data-link="{{$item->link}}">
            @if ($checkable === true || (is_array($checkable) && in_array($item->type, $checkable)))
                <div class="grid-item-badge js-click-ignore">
                    <input type="{{$mutiple ? 'checkbox' : 'radio'}}" name="{{$mutiple ? 'id[]' : 'id'}}"
                           value="{{$item->id}}"
                           id="checkable-control-{{$item->id}}" class="checkable-control d-none"
                           data-url="{{$item->url}}"
                           data-name="{{$item->name}}" data-type="{{$item->type}}"/>
                    <label for="checkable-control-{{$item->id}}" class="checkable-control-circle hover-show"></label>
                </div>
            @endif
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
                <div class="grid-item-action  ml-2 dropdown js-click-ignore">
                    <a href="javascript:;" data-toggle="dropdown" class="p-1" aria-expanded="false">
                        <i class="fa fa-ellipsis-h fa-fw mt-1"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-primary">
                        @foreach ($item->action as $a)
                            <a href="{{$a.href ?? 'javascript:;'}}" class="dropdown-item {{$a.class ?? ''}}" {!!
                        Html::attributes($a.attrs ?? []) !!}>
                                <i class="dropdown-item-icon {{$a.icon ?? ''}} fa-fw"></i>
                                <b class="dropdown-item-text"> {{$a.text}}</b>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    @endforeach
</div>
<div class="ias-spinner text-center d-none">
    <i class="fa fa-spin fa-spinner"></i>
</div>
@push('js')
    @if ($nestable)
        <script>
            $(function () {
                // 文件夹快捷操作
                $(document).on('click.folder', '.folder-item', function (e) {
                    if ($(e.target).parents('.js-click-ignore').length == 0) {
                        location.href = $(this).data('link');
                        return true;
                    }
                });
            });
        </script>
    @endif

    @if ($action === true || in_array('view', $action))
        <script>
            $(function () {
                // 文件快捷操作
                $(document).on('click.file', '.file-item', function (e) {
                    if ($(e.target).parents('.js-click-ignore').length == 0) {
                        var type = $(this).data('type');
                        if (type == 'image') {
                            $.image($(this).data('link'));
                            return true;
                        }
                    }
                });
            });
        </script>
    @endif

    @if ($action === true || in_array('move', $action))
        <script>
            $(function () {
                // 单个文件夹和文件移动
                $(document).on('click', 'a.js-move', function (event) {
                    var operator = $(this);
                    var dialog = $.dialog({
                        title: operator.data('title'),
                        url: operator.data('url'),
                        width: '85%',
                        height: '70%',
                        ok: function () {
                            $.post(operator.data('url'), {
                                id: operator.data('id'),
                                folder_id: dialog.folder_id
                            }, function (msg) {
                                $.msg(msg);
                                if (msg.type == 'success') {
                                    dialog.close().remove();
                                    location.reload();
                                }
                            })
                            return false;
                        },
                        cancel: $.noop
                    }, true).loading(true);
                });
            });
        </script>
    @endif

    @if($checkable && $mutiple)
        <script>
            $(function () {
                // 拖动选择
                $('.selectable').selectable({
                    filter: '.selectable-item',
                    distance: 30,
                    selecting: function (evnet, ui) {
                        $(ui.selecting).find('.checkable-control').prop('checked', true);
                        $('.checkable').data('checkable').update();
                    },
                    unselecting: function (evnet, ui) {
                        $(ui.unselecting).find('.checkable-control').prop('checked', false);
                        $('.checkable').data('checkable').update();
                    }
                });
            });
        </script>
    @endif

@endpush
