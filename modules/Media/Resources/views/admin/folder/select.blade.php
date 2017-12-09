@extends('core::layouts.dialog')

@section('content')
<div class="main">
    <div class="main-body scrollable" style="border:solid 1px #eee">
        <div class="tree" id="tree">     
        </div>        
    </div><!-- main-body -->
</div>
@endsection
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
        source: {!! json_encode($tree) !!},
        //minExpandLevel: 2,
        checkbox: false,
        selectMode: 3,
        toggleEffect:false,
        //autoCollapse:true,
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
        activate: function(event, data){
            $dialog.selected_folder_id = data.node.key;
        }        
    })

    $("#tree").fancytree("getTree").activateKey('{{$id}}').setExpanded(true);
});
</script>
@endpush
