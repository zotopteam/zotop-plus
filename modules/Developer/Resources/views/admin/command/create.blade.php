@extends('layouts.dialog')

@section('content')
<div class="main scrollable">

    {form route="['developer.command.create', $module, $key]" method="post" class="form p-3" autocomplete="off"}

        <div class="container-fluid">

            <div class="form-group">
                <label for="name" class="form-label required">{{$name_label}}</label>
                <div class="form-field">
                    {field type="text" name="name" pattern="$name_pattern" required="required"}

                    @if ($errors->has('name'))
                    <span class="form-help text-error">{{ $errors->first('name') }}</span>
                    @else
                    <span class="form-help">{{$name_help}}</span>                     
                    @endif
                </div>                      
            </div>

            @foreach ($options as $option)
                @include($option)
            @endforeach                                          
                       
        </div>

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

    // 对话框高度
    @if ($count = count($options))
        currentDialog.height(350);
    @endif

</script>
@endpush
