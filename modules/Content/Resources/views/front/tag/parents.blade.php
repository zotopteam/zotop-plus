{{-- title:父路径导航默认模板 --}}
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item home"><a href="{{url('/')}}">{{config('site.name')}}</a></li>
    @foreach ($parents as $parent)
    <li class="breadcrumb-item {{$id==$parent->id ? 'active' : ''}}">
        <a href="{{$parent->url}}">{{$parent->title}}</a>
    </li>
    @endforeach
  </ol>
</nav>

