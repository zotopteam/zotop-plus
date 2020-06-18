<div class="side fw-16 text-nowrap">
    <div class="side-header">
        {{trans('content::content.title')}}
    </div>
    <div class="side-body scrollable">
        <ul class="nav nav-pills nav-side">
            @foreach(Module::data('content::navbar') as $n)
            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center {{$n['class'] ?? ''}}"
                    href="{{$n['href']}}">
                    <span>
                        <i class="nav-icon {{$n['icon'] ?? ''}}"></i>
                        <span class="nav-text">{{$n['text']}}</span>
                    </span>
                    @if ($n['badge'])
                    <span class="badge badge-info scale-n2 badge-pill">{{$n['badge']}}</span>
                    @endif
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="side-divider m-0"></div>
    <div class="side-body">
        <ul class="nav nav-pills nav-side">
            @foreach(Module::data('content::navbar.extra') as $n)
            <li class="nav-item">
                <a class="nav-link {{$n['class'] ?? ''}}" href="{{$n['href']}}">
                    <i class="nav-icon {{$n['icon'] ?? ''}}"></i> <span class="nav-text">{{$n['text']}}</span>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</div>
