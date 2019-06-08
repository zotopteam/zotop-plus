@extends('core::layouts.dialog')

@section('content')
<div class="main scrollable">

    {form model="$region" route="region.store" method="post" class="m-5" autocomplete="off"}

        @include('region::region.form')

    {/form}
</div>
@endsection

@push('js')
<script type="text/javascript">
    // 对话框设置
    currentDialog.callbacks['ok'] = function(){
        $('form.form').submit();
        return false;
    };
</script>
@endpush
