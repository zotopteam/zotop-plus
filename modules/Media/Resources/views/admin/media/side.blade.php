<div class="side">
    <div class="side-header">
        {{trans('media::media.title')}}   
    </div>
    <div class="side-body scrollable">
        <div class="tree clearfix" id="tree" style="width:100%;overflow:hidden;">     
        </div>
        <div class="divider"></div>      
        <ul class="nav nav-pills nav-side">
            @foreach(Module::data('media::media.navbar') as $n)
            <li class="nav-item">
                <a class="nav-link {{$n['class'] or ''}} {{$n['active'] or ''}}" href="{{$n['href']}}">
                    <i class="nav-icon {{$n['icon'] or ''}}"></i> <span class="nav-text">{{$n['text']}}</span>
                </a>               
            </li>
            @endforeach                    
        </ul>

    </div>
</div>
@push('css')
<link rel="stylesheet" href="{{theme::asset('vendor/fancytree/skin-zotop/ui.fancytree.css')}}" rel="stylesheet">
@endpush
@push('js')
<script src="{{theme::asset('vendor/fancytree/jquery.fancytree-all.min.js')}}"></script>
<script type="text/javascript">
$(function(){
    // Initialize Fancytree
    $("#tree").fancytree({
        extensions: ["glyph","wide"],
        source: {!! json_encode(Module::data('media::media.tree')) !!},
        //minExpandLevel: 2,
        checkbox: false,
        selectMode: 3,
        toggleEffect:false,
        autoCollapse:true,
        clickFolderMode:3, //1:activate, 2:expand, 3:activate and expand, 4:activate (dblclick expands)
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
            $(".scrollable").getNiceScroll().resize();
        },
        collapse:function() {
            $(".scrollable").getNiceScroll().resize();
        },
        click: function(event, data){
            if (data.targetType == 'title' && data.node.data.href) {
                location.href = data.node.data.href;
            }
        }        
    })

    $("#tree").fancytree("getTree").activateKey('{{$folder_id}}').setExpanded(true);
});
</script>
@endpush

