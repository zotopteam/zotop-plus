{{-- title:图片列表区块模板 --}}

<ul class="list-group">
@foreach ($data as $v)
    <li class="list-group-item">
        <div class="image">
            <img src="{{image($v['image'],300,200)}}">
        </div>
        <a href="{{$v['url'] ?? 'javascript:;'}}" target="_blank">{{$v['title']}}</a>
    </li>
@endforeach
</ul>