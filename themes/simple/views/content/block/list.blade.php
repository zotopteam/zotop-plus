{{-- title:列表区块默认模板 --}}

<div class="card">
    <div class="card-header">
        {{$content->title}}
        <a class="more pull-right" href="{{$content->url}}">More</a>
    </div>
    
    <ul class="list-group list-group-flush">
    @foreach ($children as $child)
        <li class="list-group-item">
            <a href="{{$child->url ?? 'javascript:;'}}" target="_blank">{{$child->title}}</a>
            <span class="pull-right">{{$child->created_at->format('Y-m-d')}}</span>
        </li>
    @endforeach
    </ul>

    @if(method_exists($children, 'links'))
    <div class="card-footer">
        {{$children->appends($_GET)->links('vendor.pagination.bootstrap-4')}}
    </div>
    @endif
</div>

