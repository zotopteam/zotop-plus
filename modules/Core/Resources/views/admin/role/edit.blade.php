@extends('layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-back">
            <a href="{{Request::referer()}}"><i class="fa fa-angle-left"></i><b>{{trans('master.back')}}</b></a>
        </div>
        <div class="main-title mr-auto">
            {{$title}}
        </div>
        <div class="main-action">
            <z-field type="submit" form="role" value="trans('master.save')" class="btn btn-primary"/>
        </div>
    </div>
    
    <div class="main-body scrollable">
        <div class="container-fluid">

            <z-form bind="$role" route="['core.role.update',$id]" method="PUT" id="role" autocomplete="off">

            <div class="form-title row">{{trans('core::role.form.base')}}</div>

            <div class="form-group row">
                <label for="name" class="col-2 col-form-label required">{{trans('core::role.name.label')}}</label>
                <div class="col-8">
                    <z-field type="text" name="name" required="required"/>

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
                    <z-field type="textarea" name="description" rows="3" maxlength="255"/>

                    @if ($errors->has('description'))
                    <span class="form-help text-error">{{ $errors->first('description') }}</span>
                    @else
                    <span class="form-help">{{trans('core::role.description.help')}}</span>                    
                    @endif                          
                </div>
            </div>

            <div class="form-title row">
                <div class="col-2 p-0">{{trans('core::role.form.permission')}}</div>
                <div class="col-8 p-0">
                    <a href="javascript:;" class="btn btn-success btn-sm mr-3 select-all" data-select="select-all">{{trans('core::role.select.all')}}</a>
                    <a href="javascript:;" class="btn btn-warning btn-sm  mr-3 select-none" data-select="select-none">{{trans('core::role.select.none')}}</a>
                </div>
            </div>            
            
            @foreach ($permissions as $m=>$module)    
            <div class="form-group row text-sm">
                <label class="col-2 col-form-label" data-type="module" data-module="{{$m}}">
                    <div >{{$module['title']}}</div>                
                </label>
                <div class="col-10 ">
                    <div class="row">
                        @foreach ($module['permissions'] as $key=>$val)
                        @if (is_array($val))
                        <div class="col-6 role-group mb-2">
                            <label class="role-group-title cur-p" data-type="group">
                                <b>{{trans($key)}}</b>                           
                            </label>
                            <div class="role-group-body">
                            @foreach ($val as $k=>$v)
                                <div class="d-inline-block">
                                <z-field type="checkbox" name="permissions[]" value="$k" data-module="$m" label="trans($v)"/>
                                </div>
                            @endforeach
                            </div>
                        </div>
                        @else
                        <div class="col-auto">
                            <div class="d-inline-block">
                            <z-field type="checkbox" name="permissions[]" id="$key" value="$key" data-module="$m" label="trans($val)"/>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>  
                </div>
            </div>
            @endforeach
            </z-form>

        </div>
    </div><!-- main-body -->
</div>


@endsection

@push('js')
<script type="text/javascript">
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
        $('[data-type=module]').on('click', function() {
            var checkbox = $(this).parent('.form-group').find('input[data-module]');
            if (checkbox.length == checkbox.filter(':checked').length) {
                checkbox.prop('checked', false);
            } else {
                checkbox.prop('checked', true);
            }
        });

        // 选择组
        $('[data-type=group]').on('click', function() {
            var checkbox = $(this).parent('.role-group').find('input[data-module]');

            if (checkbox.length == checkbox.filter(':checked').length) {
                checkbox.prop('checked', false);
            } else {
                checkbox.prop('checked', true);
            }            
        });

    });    
</script>
@endpush
