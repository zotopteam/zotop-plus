<div class="side">
    <div class="side-header">
        <a href="{{route('developer.module.index')}}" title="{{trans('master.back')}}" data-placement="right" class="mr-3"><i class="fa fa-angle-left"></i></a>
        {{$module->getTitle()}}   
    </div>
    <div class="side-body scrollable">
        <ul class="nav nav-pills nav-side">
            @foreach(Module::data('developer::module.navbar',['module'=>$module]) as $n) 
            <li class="nav-item">
                <a class="nav-link {{$n['class'] ?? ''}}" href="{{$n['href']}}">
                    <i class="nav-icon {{$n['icon'] ?? ''}}"></i> <span class="nav-text">{{$n['text']}}</span>
                </a>
            </li>
            @endforeach                    
        </ul>    
    </div>
    <div class="side-footer">
        
    </div>
</div>
