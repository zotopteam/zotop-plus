{{-- title:导航模板 --}}
<ul class="nav nav-pills justify-content-center">
    <li class="nav-item">
        <a href="{{$content->url}}" class="nav-link {{$content->id == $attrs.current_id ? 'active' : ''}}">
            {{__('All')}}
        </a>
    </li>
    @foreach ($children as $child)
    <li class="nav-item">
        <a href="{{$child->url}}" class="nav-link {{$child->id == $attrs.current_id ? 'active' : ''}}">{{$child->title}}</a>
    </li>
    @endforeach
</ul>
