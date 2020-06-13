<div {{$attributes->merge(['class' => 'card'])}}>
    <div class="card-header">
        {{$block->name}}
    </div>
    <ul class="list-group">
        @foreach ($block->data as $v)
        @if (isset($v['url']))
        <li class="list-group-item"><a href="{{$v['url'] ?? 'javascript:;'}}" target="_blank">{{$v['title']}}</a></li>
        @else
        <li class="list-group-item">{{$v['title']}}</li>
        @endif
        @endforeach
    </ul>
</div>
