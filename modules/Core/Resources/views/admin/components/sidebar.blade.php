<div class="{{$class}}" {{$attributes}}>
    @if ($header)
    <div class="side-header">
        {{$header}}
    </div>
    @endif
    <div class="side-body scrollable">
        <ul class="nav nav-pills nav-side">
            @foreach($navigations as $n)
            <li class="nav-item">
                <a class="nav-link {{$n['class']}}" href="{{$n['href']}}" {{$n['attrs']}}>
                    <i class="nav-icon {{$n['icon']}} fa-fw"></i> <span class="nav-text">{{$n['text']}}</span>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    @if ($footer)
    <div class="side-footer">
        {{$footer}}
    </div>
    @endif
</div>
