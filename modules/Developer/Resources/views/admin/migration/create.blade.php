@extends('layouts.dialog')

@section('content')
<div class="main scrollable">

    {form route="['developer.migration.create', $module]" method="post" class="p-3" autocomplete="off"}

        <div class="container-fluid">
            <div class="form-group">
                <label for="name" class="form-label required">
                    {{trans('developer::migration.command')}}
                </label>
                <div class="form-field">
                    {field type="radiogroup" name="command" options="Module::data('developer::migration.command')" column="1"}

                    @if ($errors->has('command'))
                    <span class="form-help text-error">{{ $errors->first('command') }}</span>
                    @endif
                    <span class="form-help">{{trans('developer::migration.command.help')}}</span>
                </div>                      
            </div>                          
            <div class="form-group">
                <label for="name" class="form-label required">
                    <span data-depend="[name=command]" data-when="value=module:make-migration" data-then="hide">
                        {{trans('developer::migration.name.table')}}
                    </span>
                    <span data-depend="[name=command]" data-when="value=module:make-migration" data-then="show" class="d-none">
                        {{trans('developer::migration.name.blank')}}
                    </span>
                </label>
                <div class="form-field">
                    {field type="text" name="name" pattern="^[a-z][a-z0-9_]+[a-z]$" required="required"}

                    @if ($errors->has('name'))
                    <span class="form-help text-error">{{ $errors->first('name') }}</span>
                    @endif
                    <span class="form-help">{{trans('developer::migration.name.help')}}</span>
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

    // $(function(){
    //     $('form.form').submited({
    //         message : function(msg) {
    //             $.dialog({
    //                 skin  : 'ui-cmd',
    //                 width : '80%',
    //                 height: '60%',
    //                 title : '{{trans('developer::developer.artisan.result')}}',
    //                 content: msg.content,
    //                 onclose: function(){
    //                     parent.location.reload();
    //                 }
    //             }, true);
    //         }
    //     });        
    // });
 
</script>
@endpush
