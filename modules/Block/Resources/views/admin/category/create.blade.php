@extends('layouts.dialog')

@section('content')
<div class="main scrollable">

    {form model="$category" route="block.category.store" id="category-form" class="p-3" autocomplete="off"}

        <div class="container-fluid">

            <div class="form-group">
                <label for="name" class="form-label required">{{trans('block::category.name')}}</label>
                <div class="form-field">
                    {field type="text" name="name" required="required"}

                    @if ($errors->has('name'))
                    <span class="form-help text-error">{{ $errors->first('name') }}</span>
                    @else
                    <span class="form-help">{{trans('block::category.name.help')}}</span>                     
                    @endif
                </div>                      
            </div>                                            

            <div class="form-group">
                <label for="description" class="form-label">{{trans('block::category.description')}}</label>
                <div class="form-field">
                    {field type="textarea" name="description" rows="5"}

                    @if ($errors->has('description'))
                    <span class="form-help text-error">{{ $errors->first('description') }}</span>
                    @else
                    <span class="form-help">{{trans('block::category.description.help')}}</span>                     
                    @endif
                </div>                      
            </div>                       
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

    $(function(){
        
        $('form.form').submited(function(msg){
            msg.onclose = function () {
                currentDialog.opener.location.reload();
            }
            currentDialog.close();            
        });
        
    })  
</script>
@endpush
