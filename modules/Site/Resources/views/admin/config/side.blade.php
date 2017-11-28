<div class="side">
    <div class="side-header">
        {{trans('site::config.title')}}   
    </div>
    <div class="side-body scrollable">
        <ul class="nav nav-pills nav-side">
            @foreach(Module::data('site::config.navbar') as $n) 
            <li class="nav-item">
                <a class="nav-link {{$n['class'] or ''}}" href="{{$n['href']}}">
                    <i class="fa fa-fw {{$n['icon'] or ''}}"></i> {{$n['text']}}
                </a>
            </li>
            @endforeach                                      
        </ul>        
    </div>
</div>
