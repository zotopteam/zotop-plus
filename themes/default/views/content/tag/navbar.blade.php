{{-- title:导航列表模板 --}}

<nav>
    <a href="{{url('/')}}" class="btn btn-outline text-white">
        <i class="fa fa-home"></i>
    </a>
    @foreach ($navbar as $nav)
    <a href="{{$nav->url}}" class="btn btn-outline text-white">
        {{$nav->title}}
    </a>
    @endforeach
</nav>
