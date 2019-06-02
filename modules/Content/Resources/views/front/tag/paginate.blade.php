{{-- title:分页列表默认模板 --}} 
<ul class="list-group list-group-flush">
@foreach ($list as $item)
    <li class="list-group-item px-0">
        <a href="{{$item->url}}" target="_blank">{{$item->title}}</a>
        <span class="float-right">{{$item->created_at->format('Y-m-d')}}</span>
    </li>
@endforeach
</ul>

<div class="mt-3">
{{$list->appends($_GET)->links()}}
</div>

