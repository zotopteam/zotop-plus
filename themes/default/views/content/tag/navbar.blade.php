{{-- title:导航列表模板 --}}
<ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link" href="{{url('/')}}">{{__('Home')}}</a>
    </li>
    @foreach ($navbar as $nav)
    <li class="nav-item">
        <a class="nav-link" href="{{$nav->url}}">{{$nav->title}}</a>
    </li>
    @endforeach
</ul>
