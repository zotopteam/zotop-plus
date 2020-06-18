<div class="side fw-20">
    <div class="side-header">
        {{trans('content::content.title')}}
    </div>
    <div class="side-body scrollable">
        <div class="tree clearfix" id="tree" style="width:100%;overflow:hidden;">
        </div>
    </div>
    <div class="side-divider m-0"></div>
    <div class="side-body">
        <ul class="nav nav-pills nav-side">
            @foreach(Module::data('content::navbar') as $n)
            <li class="nav-item">
                <a class="nav-link {{$n['class'] ?? ''}}" href="{{$n['href']}}">
                    <i class="nav-icon {{$n['icon'] ?? ''}}"></i> <span class="nav-text">{{$n['text']}}</span>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@push('css')
<link rel="stylesheet" href="{{Theme::asset('vendor/fancytree/skin-zotop/ui.fancytree.css')}}" rel="stylesheet">
@endpush
@push('js')
<script src="{{Theme::asset('vendor/fancytree/jquery.fancytree-all.min.js')}}"></script>
<script type="text/javascript">
    $(function(){
    // Initialize Fancytree
    $("#tree").fancytree({
        extensions      : ["glyph","wide","persist"],
        source          : @json(Module::data('content::tree')),
        minExpandLevel  : 1,
        checkbox        : false,
        selectMode      : 3,
        toggleEffect    : false,
        autoCollapse    : true,
        clickFolderMode : 4, //1:activate, 2:expand, 3:activate and expand, 4:activate (dblclick expands)
        glyph: {preset: "awesome4",map: {
            expanderClosed: "fas fa-angle-right",
            expanderLazy: "fas fa-angle-right",
            expanderOpen: "fas fa-angle-down",            
            checkbox: "far fa-square",
            checkboxSelected:'far fa-check-square',
            folder: "fas fa-folder text-warning",
            folderOpen: "fas fa-folder-open  text-warning",
            doc: "fas fa-file text-warning",
            docOpen: "fas fa-file  text-warning",                    
        }},
        persist: {
            store: "local" // 'cookie', 'local': use localStore, 'session': sessionStore
        },
        expand: function() {
            $(window).trigger('resize');
        },
        collapse:function() {
            $(window).trigger('resize');
        },
        click: function(event, data){
            if (data.targetType == 'title' && data.node.data.href) {
                location.href = data.node.data.href;
            }
        }        
    })
    @if(Route::is('content.content.index'))
    $("#tree").fancytree("getTree").activateKey('{{$parent->id ?? 0}}').setExpanded(true);
    @endif
});
</script>
@endpush
