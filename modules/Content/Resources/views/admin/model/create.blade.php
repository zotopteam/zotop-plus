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
            <z-field type="submit" form="model-form" value="trans('master.save')" class="btn btn-primary"/>
        </div>
    </div>
    
    <div class="main-body scrollable">
        <div class="container-fluid">

            <z-form bind="$model" route="content.model.store" id="model-form" method="post" autocomplete="off">

            <div class="form-group row">
                <label for="name" class="col-2 col-form-label required">{{trans('content::model.name.label')}}</label>
                <div class="col-8">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <z-field type="icon" name="icon" required="required"/>
                        </div>
                        <z-field type="text" name="name" required="required"/>
                    </div>

                    @if ($errors->has('name'))
                    <span class="form-help text-error">{{ $errors->first('name') }}</span>
                    @else
                    <span class="form-help">{{trans('content::model.name.help')}}</span>                     
                    @endif                       
                </div>
            </div>

            <div class="form-group row">
                <label for="id" class="col-2 col-form-label required">{{trans('content::model.id.label')}}</label>
                <div class="col-8">
                    <z-field type="translate" name="id" source="name" format="id" required="required" maxlength="64"/>

                    @if ($errors->has('id'))
                    <span class="form-help text-error">{{ $errors->first('id') }}</span>
                    @else
                    <span class="form-help">{{trans('content::model.id.help')}}</span>                     
                    @endif                       
                </div>
            </div>            

            <div class="form-group row">
                <label for="description" class="col-2 col-form-label">{{trans('content::model.description.label')}}</label>
                <div class="col-8">
                    <z-field type="textarea" name="description" maxlength="255" rows="3"/>

                    @if ($errors->has('description'))
                    <span class="form-help text-error">{{ $errors->first('description') }}</span>
                    @else
                    <span class="form-help">{{trans('content::model.description.help')}}</span>                     
                    @endif                       
                </div>
            </div>

            <div class="form-group row">
                <label for="view" class="col-2 col-form-label">{{trans('content::model.view.label')}}</label>
                <div class="col-8">
                    <z-field type="view" name="view"/>

                    @if ($errors->has('view'))
                    <span class="form-help text-error">{{ $errors->first('view') }}</span>
                    @else
                    <span class="form-help">{{trans('content::model.view.help')}}</span>                     
                    @endif                       
                </div>
            </div>

            <div class="form-group row">
                <label for="nestable" class="col-2 col-form-label">{{trans('content::model.nestable.label')}}</label>
                <div class="col-8">
                    <z-field type="toggle" name="nestable"/>

                    @if ($errors->has('nestable'))
                    <span class="form-help text-error">{{ $errors->first('nestable') }}</span>
                    @else
                    <span class="form-help">{{trans('content::model.nestable.help')}}</span>                     
                    @endif                       
                </div>
            </div>             

            </z-form>

        </div>
    </div><!-- main-body -->
</div>
@endsection
