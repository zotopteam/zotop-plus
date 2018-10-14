<ul class="list-group">
@foreach ($data as $v)
    @if (isset($v['url']))
    <li class="list-group-item"><a href="{{$v['url'] ?? 'javascript:;'}}" target="_blank">{{$v['title']}}</a></li>
    @else
    <li class="list-group-item">{{$v['title']}}</li>
    @endif
@endforeach
</ul>

