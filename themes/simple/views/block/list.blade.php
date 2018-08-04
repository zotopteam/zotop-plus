<ul class="list-group">
@foreach ($data as $v)
    <li class="list-group-item">
        <div class="image">
            <img src="{{image($v['image'],300,200)}}">
        </div>
        <a href="{{$v['url'] or 'javascript:;'}}" target="_blank">{{$v['title']}}</a>
    </li>
@endforeach
</ul>

