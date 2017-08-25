<div class="side">
    <div class="side-header">
        {{trans('core::mine.title')}}   
    </div>
    <div class="side-body scrollable">
        <ul class="nav nav-pills nav-side">
            <li class="nav-item">
                <a class="nav-link {{Route::active('core.mine.edit')}}" href="{{route('core.mine.edit')}}"><i class="fa fa-user fa-fw"></i> {{trans('core::mine.edit')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{Route::active('core.mine.password')}}" href="{{route('core.mine.password')}}"><i class="fa fa-edit fa-fw"></i> {{trans('core::mine.password')}}</a>
            </li>
            <li class="nav-item d-none">
                <a class="nav-link {{Route::active('core.mine.permission')}}" href="{{route('core.mine.permission')}}"><i class="fa fa-sitemap fa-fw"></i> {{trans('core::mine.permission')}}</a>
            </li>
            <li class="nav-item d-none">
                <a class="nav-link {{Route::active('core.mine.log')}}" href="{{route('core.mine.log')}}"><i class="fa fa-flag fa-fw"></i> {{trans('core::mine.log')}}</a>
            </li>            
        </ul>        
    </div>
</div>