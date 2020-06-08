{{-- title:分页列表默认模板 --}}
<ul class="list-group list-group-flush">
    @foreach ($list as $item)
    <li class="list-group-item d-flex align-items-center px-0">
        <a href="{{$item->url}}" target="_blank" class="mr-auto">{{$item->title}}</a>
        <span class="text-nowrap ml-5">{{$item->created_at->format('Y-m-d')}}</span>
    </li>
    @endforeach
</ul>

<div class="mt-3">
    {{$list->withQueryString()->links()}}
</div>
