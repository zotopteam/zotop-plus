{{-- title:父路径导航默认模板 --}}
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item home"><a href="{{url('/')}}">{{__('Home')}}</a></li>
    @foreach ($path as $p)
    <li class="breadcrumb-item {{$attrs.id==$p->id ? 'active' : ''}}">
        <a href="{{$p->url}}">{{$p->title}}</a>
    </li>
    @endforeach
  </ol>
</nav>

