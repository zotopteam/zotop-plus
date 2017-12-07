<div class="side">
    <div class="side-header">
        {{trans('media::media.title')}}   
    </div>
    <div class="side-body scrollable">
        <div class="tree clearfix" id="tree" style="width:100%;overflow:hidden;">         
        </div>   
    </div>
</div>
@push('css')
<link rel="stylesheet" href="{{theme::asset('vendor/fancytree/skin-zotop/ui.fancytree.css')}}" rel="stylesheet">
@endpush
@push('js')
<script src="{{theme::asset('vendor/fancytree/jquery-ui.custom.js')}}"></script>
<script src="{{theme::asset('vendor/fancytree/jquery.fancytree-all.min.js')}}"></script>

<script type="text/javascript">
$(function(){
    // Initialize Fancytree
    $("#tree").fancytree({
        extensions: ["glyph","wide","persist"],
        source: {url: "http://127.0.0.7/themes/admin/vendor/fancytree/ajax-tree-products.json"},
        checkbox: false,
        toggleEffect:false,
        autoCollapse:true,
        clickFolderMode:3,
        glyph: {preset: "awesome4",map: {}},
        persist: {
            store: "local" // 'cookie', 'local': use localStore, 'session': sessionStore
        },
        expand: function() {
            $(".scrollable").getNiceScroll().resize();
        },
        collapse:function() {
            $(".scrollable").getNiceScroll().resize();
        }
    });
});
</script>
@endpush

