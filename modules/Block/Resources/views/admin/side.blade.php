<div class="side">
    <div class="side-header">
        {{trans('block::block.title')}}
    </div>
    <div class="side-body scrollable">
        <ul class="nav nav-pills nav-side">
            @foreach(Module::data('block::side.navbar') as $n) 
            <li class="nav-item">
                <a class="nav-link {{$n['class'] or ''}}" href="{{$n['href']}}">
                    <i class="nav-icon {{$n['icon'] or ''}}"></i> <span class="nav-text">{{$n['text']}}</span>
                </a>
            </li>
            @endforeach                                      
        </ul>        
    </div>
    <div class="side-divider m-0"></div>
    <div class="side-body">
        <ul class="nav nav-pills nav-side">
            <li class="nav-item">
                <a class="nav-link {{Route::active('block.category.*')}}" href="{{route('block.category.index')}}">
                    <i class="nav-icon fa fa-sitemap"></i> <span class="nav-text">{{trans('block::category.title')}}</span>
                </a>
                <a class="nav-badge js-open {{Route::is('block.category.*') ? 'text-white' : ''}}" href="javascript:;" data-url="{{route('block.category.create')}}" data-width="800"  data-height="300" title="{{trans('core::master.create')}}">
                    <i class="fa fa-plus"></i>
                </a>                
            </li>            
        </ul>
    </div> 
</div>
