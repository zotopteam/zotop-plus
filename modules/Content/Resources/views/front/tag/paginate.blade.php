{{-- title:分页列表默认模板 --}} 
<ul class="list-group list-group-flush">
@foreach ($children as $child)
    <li class="list-group-item px-0">
        <a href="{{$child->url}}" target="_blank">{{$child->title}}</a>
        <span class="float-right">{{$child->created_at->format('Y-m-d')}}</span>
    </li>
@endforeach
</ul>

<div class="mt-3">
{{$children->appends($_GET)->links('vendor.pagination.bootstrap-4')}}
</div>

