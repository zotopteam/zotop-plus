@extends('core::layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-back">
            <a href="{{request::referer()}}"><i class="fa fa-angle-left"></i><b>{{trans('core::master.back')}}</b></a>
        </div>
        <div class="main-title mr-auto">
            {{$title}}
        </div>
    </div>
    
    <div class="main-body scrollable">
        <div class="container-fluid">

            {form model="$role" route="core.role.store" method="post" id="role" autocomplete="off"}

            <div class="form-title row">{{trans('core::role.form.base')}}</div>

            <div class="form-group row">
                <label for="name" class="col-2 col-form-label required">{{trans('core::role.name.label')}}</label>
                <div class="col-8">
                    {field type="text" name="name" required="required"}

                    @if ($errors->has('name'))
                    <span class="form-help text-error">{{ $errors->first('name') }}</span>
                    @else
                    <span class="form-help">{{trans('core::role.name.help')}}</span>                     
                    @endif                       
                </div>
            </div>

            <div class="form-group row">
                <label for="description" class="col-2 col-form-label required">{{trans('core::role.description.label')}}</label>
                <div class="col-8">
                    {field type="textarea" name="description" rows="3" maxlength="255"}

                    @if ($errors->has('description'))
                    <span class="form-help text-error">{{ $errors->first('description') }}</span>
                    @else
                    <span class="form-help">{{trans('core::role.description.help')}}</span>                    
                    @endif                          
                </div>
            </div>

            <div class="form-title row">
                <span class="d-inline-block mr-3">{{trans('core::role.form.permission')}}</span>
                <a href="javascript:;" class="text-sm d-inline-block p-1 select-all" data-select="select-all">{{trans('core::role.select.all')}}</a>
                <a href="javascript:;" class="text-sm d-inline-block p-1 select-none" data-select="select-none">{{trans('core::role.select.none')}}</a>
            </div>            
            
            @foreach ($permissions as $m=>$module)    
            <div class="form-group row">
                <label class="col-2 checkbox">
                    <input type="checkbox" name="permissions[]" value="{{$module['key']}}" data-type="module" data-module="{{$m}}">
                    <span>{{$module['title']}}</span>
                </label>
                <div class="col-10">
                    @foreach ($module['permissions'] as $c=>$controller)
                    <div class="row">
                        <div class="col-2">
                            <label class="checkbox">
                                <input type="checkbox" name="permissions[]" value="{{$controller['key']}}" data-type="controller"  data-module="{{$m}}" data-controller="{{$c}}">
                                <span>{{$controller['title']}}</span>
                            </label>
                        </div>
                        <div class="col-10">
                            @foreach ($controller['permissions'] as $a=>$action)
                            <label class="checkbox">
                                <input type="checkbox" name="permissions[]" value="{{$action['key']}}" data-type="action"  data-module="{{$m}}" data-controller="{{$c}}" data-action="{{$a}}">
                                <span>{{$action['title']}}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
            {/form}

        </div>
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="mr-auto">
            {field type="submit" form="role" value="trans('core::master.save')" class="btn btn-primary"}
        </div>
    </div>
</div>


@endsection

@push('js')
<script type="text/javascript">
    // 表单提交
    $(function(){

        $('form.form').validate({
       
            submitHandler:function(form){                
                var validator = this;

                $('.form-submit').prop('disabled',true);

                $.post($(form).attr('action'), $(form).serialize(), function(msg){
                    
                    $.msg(msg);

                    if ( msg.state && msg.url ) {
                        location.href = msg.url;
                        return true;
                    }

                    $('.form-submit').prop('disabled',false);
                    return false;

                },'json').fail(function(jqXHR){
                    
                    $('.form-submit').prop('disabled',false);
                    return validator.showErrors(jqXHR.responseJSON.errors);
                });
            }            
        });
    })

    //权限选择
    $(function(){
        //全选
        $('[data-select=select-all]').on('click',function() {
            $('[data-module]').prop('checked', true);
        });

        //全不选
        $('[data-select=select-none]').on('click',function() {
            $('[data-module]').prop('checked', false);
        });

        //选择模块
        $('[data-type=module]').change(function() {
            $('[data-module='+ $(this).data('module') +']').prop('checked', $(this).prop("checked"));
        });

        // 选择控制器
        $('[data-type=controller]').change(function() {
            $('[data-module='+ $(this).data('module') +'][data-controller='+ $(this).data('controller') +']').prop('checked', $(this).prop("checked"));

            if ($('[data-module='+ $(this).data('module') +'][data-type=controller]:checked').length == $('[data-module='+ $(this).data('module') +'][data-type=controller]').length) {
                $('[data-module='+ $(this).data('module') +'][data-type=module]').prop('checked', true);
            } else {
                $('[data-module='+ $(this).data('module') +'][data-type=module]').prop('checked', false);
            }
        });

        // 选择动作
        $('[data-type=action]').change(function() {
            if(false == $(this).prop("checked")){
                $('[data-module='+ $(this).data('module') +'][data-controller='+ $(this).data('controller') +'][data-type=module]').prop('checked', false);
                $('[data-module='+ $(this).data('module') +'][data-controller='+ $(this).data('controller') +'][data-type=controller]').prop('checked', false);
            }

            if ($('[data-module='+ $(this).data('module') +'][data-controller='+ $(this).data('controller') +'][data-type=action]:checked').length == $('[data-module='+ $(this).data('module') +'][data-controller='+ $(this).data('controller') +'][data-type=action]').length) {
                $('[data-module='+ $(this).data('module') +'][data-controller='+ $(this).data('controller') +'][data-type=controller]').prop('checked', true);
            } else {
                $('[data-module='+ $(this).data('module') +'][data-controller='+ $(this).data('controller') +'][data-type=controller]').prop('checked', false);
            }

            if ($('[data-module='+ $(this).data('module') +'][data-type=controller]:checked').length == $('[data-module='+ $(this).data('module') +'][data-type=controller]').length) {
                $('[data-module='+ $(this).data('module') +'][data-type=module]').prop('checked', true);
            } else {
                $('[data-module='+ $(this).data('module') +'][data-type=module]').prop('checked', false);
            }                  
        });   
    });
</script>
@endpush
