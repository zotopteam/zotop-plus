<div class="side">
    <div class="side-header">
        {{trans('media::media.title')}}   
    </div>
    <div class="side-body scrollable">
        <ul class="nav nav-pills nav-side">
            @foreach(Module::data('media::media.navbar',['folder_id'=>$folder_id, 'type'=>$type]) as $n)
            <li class="nav-item">
                <a class="nav-link {{$n['class'] or ''}} {{$n['active'] or ''}}" href="{{$n['href']}}">
                    <i class="fa fa-fw {{$n['icon'] or ''}}"></i> {{$n['text']}}
                </a>
            </li>
            @endforeach                    
        </ul>        
    </div>
</div>
