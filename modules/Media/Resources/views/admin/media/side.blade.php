<div class="side">
    <div class="side-header">
        {{trans('media::media.title')}}   
    </div>
    <div class="side-body scrollable">
        <div class="tree clearfix" id="tree" style="width:100%;overflow:hidden;">     
        </div>
    </div>
    <div class="divider m-0"></div>
    @foreach(Module::data('media::info') as $i)
    <div class="side-footer justify-content-between align-items-center">
        {{$i['title']}}
        @if(isset($i['badge']))
        <span class="badge {{$i['badge_class'] or 'badge-primary'}} badge-pill">{{$i['badge']}}</span>
        @endif
    </div>
    @endforeach
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
        source: {!! json_encode(Module::data('media::tree')) !!},
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
    @if(Route::is('media.index'))
    $("#tree").fancytree("getTree").activateKey('{{$folder_id or 0}}').setExpanded(true);
    @endif
});
</script>
@endpush

