@extends('layouts.dialog')

@section('content')
<div class="main scrollable">
    {form bind="$region" route="['region.update', $region['id']]" method="post" class="form m-5" autocomplete="off"}
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
