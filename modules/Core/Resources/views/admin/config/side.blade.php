<div class="side">
    <div class="side-header">
        {{trans('core::config.title')}}   
    </div>
    <div class="side-body scrollable">
        <ul class="nav nav-pills nav-side">
            @foreach(Module::data('core::config.navbar') as $n)
            <li class="nav-item">
                <a class="nav-link {{$n['active'] ? 'active' : ''}}" href="{{$n['href']}}">
                    <i class="{{$n['class']}}"></i> {{$n['text']}}
                </a>
            </li>
            @endforeach                    
        </ul>        
    </div>
</div>
