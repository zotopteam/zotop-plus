<div class="side">
    <div class="side-body scrollable">
        <ul class="nav nav-pills nav-side">
            @foreach(Module::data('core::field.upload.tools', $params) as $n)
            <li class="nav-item">
                <a class="nav-link {{$n['class'] or ''}} {{$n['active'] or ''}}" href="{{$n['href']}}">
                    <i class="nav-icon {{$n['icon'] or ''}}"></i> <span class="nav-text">{{$n['text']}}</span>
                </a>
            </li>
            @endforeach                    
        </ul>        
    </div>
</div>
