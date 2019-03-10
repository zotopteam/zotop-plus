{{-- title:导航列表模板 --}}

<nav>
    <a href="{{url('/')}}" class="btn btn-outline text-white">
        <i class="fa fa-home"></i>
    </a>
    @foreach ($children as $child)
    <a href="{{$child->url}}" class="btn btn-outline text-white">
        {{$child->title}}
    </a>
    @endforeach
</nav>
