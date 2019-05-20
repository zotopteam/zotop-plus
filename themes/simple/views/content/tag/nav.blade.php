{{-- title:导航模板 --}}
@if ($list->count() > 0)
@php($self = \Modules\Content\Models\Content::find($attrs.id))
<ul class="nav nav-pills justify-content-center">
    <li class="nav-item">
        <a href="{{$self->url}}" class="nav-link {{$attrs.id == $attrs.current_id ? 'active' : ''}}">
            {{__('All')}}
        </a>
    </li>
    @foreach ($list as $item)
    <li class="nav-item">
        <a href="{{$item->url}}" class="nav-link {{$item->id == $attrs.current_id ? 'active' : ''}}">{{$item->title}}</a>
    </li>
    @endforeach
</ul>
@endif
