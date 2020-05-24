<div class="side">
    <div class="side-header">
        {{trans('core::storage.title')}}   
    </div>
    <div class="side-body scrollable">
        <ul class="nav nav-pills nav-side">
            @foreach($disks as $n)
            <li class="nav-item">
                <a class="nav-link {{$n.class ?? ''}}" href="{{$n.href ?? ''}}">
                    <i class="nav-icon {{$n.icon ?? ''}}"></i> <span class="nav-text">{{$n.title}}</span>
                </a>
            </li>
            @endforeach                    
        </ul>        
    </div>
</div>
