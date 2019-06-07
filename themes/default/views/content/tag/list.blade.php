{{-- title:列表区块默认模板 --}}
@if($self = \Modules\Content\Models\Content::find($attrs.id))
<div class="card">
    <div class="card-header">
        {{$self->title}}
        <a class="more float-right" href="{{$self->url}}">
            <i class="fa fa-ellipsis-h"></i>
        </a>
    </div>
    <ul class="list-group list-group-flush">
    @foreach ($list as $item)
        <li class="list-group-item">
            <a href="{{$item->url}}" {!! $item->title_style ? 'style="'.$item->title_style.'"': '' !!}>{{$item->title}}</a>
            <span class="float-right">{{$item->created_at->format('Y-m-d')}}</span>
        </li>
    @endforeach
    </ul>
</div>
@endif

