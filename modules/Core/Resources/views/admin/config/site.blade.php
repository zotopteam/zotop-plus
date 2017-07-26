<div class="side">
    <div class="side-header">
        {{trans('core::config.site.title')}}   
    </div>
    <div class="side-body scrollable">
        <ul class="nav nav-pills nav-side">
            <li class="nav-item">
                <a class="nav-link {{Request::is('*/site/base') ? 'active' : 'normal'}}" href="{{route('core.config.site.base')}}">
                    <i class="fa fa-cog fa-fw"></i> {{trans('core::config.site.base')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{Request::is('*/site/seo') ? 'active' : 'normal'}}" href="{{route('core.config.site.seo')}}">
                    <i class="fa fa-search fa-fw"></i> {{trans('core::config.site.seo')}}
                </a>
            </li>                      
        </ul>        
    </div>
</div>