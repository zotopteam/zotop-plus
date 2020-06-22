@extends('layouts.dialog')

@section('content')

<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{trans('content::content.sort.help', [Str::limit($sort->title, 20)])}}
        </div>
        <x-search />
        <a href="javascript:location.reload()" class="btn btn-light" title="{{trans('master.refresh')}}">
            <span class="fa fa-sync"></span>
        </a>
    </div>
    <div class="main-body scrollable">
        <x-content-admin-list :list="$contents" :action="false" allow="view" :mutiple="false" :nestable="false" />
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="mx-auto">
            {{ $contents->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
    $(function(){

    // 确定按钮回调
    currentDialog.callbacks['ok'] = function () {

        var selected = $('.selectable-item').filter('.selected');

        if (selected.length) {

            var newsort  = selected.data('sort') + 1;
            var newstick = selected.data('stick');
            
            $.post('{{route('content.content.sort', $sort->parent_id)}}',{id:{{$sort->id}}, sort:newsort, stick:newstick}, function(msg) {
                $.msg(msg);

                if (msg.type == 'success') {
                    currentDialog.opener.location.reload();
                    currentDialog.close();
                }

            },'json');

            return false;
        }
        
        $.error('{{ trans('master.select.min', [1]) }}');
        return false;
    }      
})




   
</script>
@endpush
