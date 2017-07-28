<div class="side">
    <div class="side-header">
        {{trans('site::config.title')}}   
    </div>
    <div class="side-body scrollable">
        <ul class="nav nav-pills nav-side">
            <li class="nav-item">
                <a class="nav-link {{Request::is('*/site/config/base') ? 'active' : 'normal'}}" href="{{route('site.config.base')}}">
                    <i class="fa fa-cog fa-fw"></i> {{trans('site::config.base')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{Request::is('*/site/config/seo') ? 'active' : 'normal'}}" href="{{route('site.config.seo')}}">
                    <i class="fa fa-search fa-fw"></i> {{trans('site::config.seo')}}
                </a>
            </li>                      
        </ul>        
    </div>
</div>