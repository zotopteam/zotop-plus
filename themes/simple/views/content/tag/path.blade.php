{{-- title:父路径导航默认模板 --}}
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item home"><a href="{{url('/')}}">{{__('Home')}}</a></li>
    @foreach ($contents as $content)
    <li class="breadcrumb-item {{$attrs.id==$content->id ? 'active' : ''}}">
        <a href="{{$content->url}}">{{$content->title}}</a>
    </li>
    @endforeach
  </ol>
</nav>

