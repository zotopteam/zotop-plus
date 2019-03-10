{{-- title:列表区块默认模板 --}}

<div class="card">
    <div class="card-header">
        {{$content->title}}
        <a class="more float-right" href="{{$content->url}}">
            <i class="fa fa-ellipsis-h"></i>
        </a>
    </div>
    
    <ul class="list-group list-group-flush">
    @foreach ($children as $child)
        <li class="list-group-item">
            <a href="{{$child->url}}" {!! $child->title_style ? 'style="'.$child->title_style.'"': '' !!}>{{$child->title}}</a>
            <span class="float-right">{{$child->source}} {{$child->user->username}} {{$child->created_at->format('Y-m-d')}}</span>
        </li>
    @endforeach
    </ul>
</div>

