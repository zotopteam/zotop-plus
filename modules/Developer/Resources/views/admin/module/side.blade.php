<div class="side">
    <div class="side-header">
        <a href="{{route('developer.module.index')}}" title="{{trans('core::master.back')}}" data-placement="right" class="mr-3"><i class="fa fa-angle-left"></i></a>
        {{$module->title}}   
    </div>
    <div class="side-body scrollable">
        <ul class="nav nav-pills nav-side">
            @foreach(Module::data('developer::module.navbar',['module'=>$module]) as $n) 
            <li class="nav-item">
                <a class="nav-link {{$n['class'] or ''}}" href="{{$n['href']}}">
                    <i class="nav-icon {{$n['icon'] or ''}}"></i> <span class="nav-text">{{$n['text']}}</span>
                </a>
            </li>
            @endforeach                    
        </ul>    
    </div>
    <div class="side-footer">
        
    </div>
</div>
