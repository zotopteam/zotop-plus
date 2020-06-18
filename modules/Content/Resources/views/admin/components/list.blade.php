<table class="table table-nowrap table-sortable table-hover checkable">
    <tbody>
        @foreach($list as $content)
        <tr class="checkable-item" data-id="{{$content->id}}" data-sort="{{$content->sort}}"
            data-stick="{{$content->stick}}" data-title="{{$content->title}}">
            <td class="drag"></td>
            <td class="select">
                <input type="checkbox" name="ids[]" value="{{$content->id}}" class="checkable-checkbox text-muted">
            </td>
            <td class="text-center px-2" width="1%">
                @if ($content->image)
                <a href="javascript:;" class="d-flex fw-3 fh-3 overflow-hidden js-image" data-url="{{$content->image}}"
                    data-title="{{$content->title}}">
                    <span class="align-self-center"><img src="{{$content->image}}" class="img-fluid rounded"></span>
                </a>
                @endif
            </td>
            <td>
                <div class="title text-truncate text-truncate-1 mb-1">
                    @if ($content->model->nestable)
                    <a href="{{route('content.content.index', $content->id)}}">
                        {{$content->title}}
                    </a>
                    @else
                    {{$content->title}}
                    @endif
                </div>
                <div class="scale-n3 scale-left">
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

            </td>
            <td width="1%">
                <div class="manage">
                    @foreach (array_slice($content->action, 0, 2) as $action)
                    <a href="{{$action.href ?? 'javascript:;'}}" class="manage-item {{$action.class ?? ''}}" {!!
                        Html::attributes($action.attrs ?? []) !!}>
                        <i class="{{$action.icon ?? ''}} fa-fw"></i>
                        {{$action.text}}
                    </a>
                    @endforeach
                    <div class="manage-item dropdown">
                        <a href="javascript:;" data-toggle="dropdown" class="dropdown-trigger py-1"
                            aria-expanded="false">
                            <i class="fa fa-ellipsis-h fa-fw mt-1"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-primary">
                            @foreach (array_slice($content->action, 2) as $action)
                            <a href="{{$action.href ?? 'javascript:;'}}" class="dropdown-item {{$action.class ?? ''}}"
                                {!! Html::attributes($action.attrs ?? []) !!}>
                                <i class="dropdown-item-icon {{$action.icon ?? ''}} fa-fw"></i>
                                <b class="dropdown-item-text"> {{$action.text}}</b>
                            </a>
                            @endforeach
                        </div>
                    </div>
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

</script>
@endpush
