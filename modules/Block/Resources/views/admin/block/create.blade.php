@extends('layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-back">
            <a href="{{Request::referer()}}"><i class="fa fa-angle-left"></i><b>{{trans('master.back')}}</b></a>
        </div>
        <div class="main-title mx-auto">
            {{$title}}
        </div>
        <div class="main-action">
            <z-field type="submit" form="block-form" value="trans('block::block.save.next')" class="btn btn-primary"/>            
        </div>
    </div>
    
    <div class="main-body scrollable">
        <div class="container-fluid">

            <z-form bind="$block" route="block.store" id="block-form" method="post" autocomplete="off">
            
            <z-field type="hidden" name="type" required="required"/>

            <div class="form-group row">
                <label for="category_id" class="col-2 col-form-label required">{{trans('block::block.category_id')}}</label>
                <div class="col-8">
                    <z-field type="select" name="category_id" options="Module::data('block::category.select')" required="required"/>

                    @if ($errors->has('category_id'))
                    <span class="form-help text-error">{{ $errors->first('category_id') }}</span>
                    @else
                    <span class="form-help">{{trans('block::block.category_id.help')}}</span>                     
                    @endif                       
                </div>
            </div>

            <div class="form-group row">
                <label for="name" class="col-2 col-form-label required">{{trans('block::block.name')}}</label>
                <div class="col-8">
                    <z-field type="text" name="name" required="required"/>

                    @if ($errors->has('name'))
                    <span class="form-help text-error">{{ $errors->first('name') }}</span>
                    @else
                    <span class="form-help">{{trans('block::block.name.help')}}</span>                     
                    @endif                       
                </div>
            </div>

            <div class="form-group row">
                <label for="slug" class="col-2 col-form-label required">{{trans('block::block.slug')}}</label>
                <div class="col-8">
                    <z-field type="translate" name="slug" source="name" format="slug" required="required" maxlength="64"/>

                    @if ($errors->has('slug'))
                    <span class="form-help text-error">{{ $errors->first('slug') }}</span>
                    @else
                    <span class="form-help">{{trans('block::block.slug.help')}}</span>                     
                    @endif                       
                </div>
            </div>            

            <div class="form-group row">
                <label for="description" class="col-2 col-form-label">{{trans('block::block.description')}}</label>
                <div class="col-8">
                    <z-field type="textarea" name="description" rows="3"/>

                    @if ($errors->has('description'))
                    <span class="form-help text-error">{{ $errors->first('description') }}</span>
                    @else
                    <span class="form-help">{{trans('block::block.description.help')}}</span>                     
                    @endif                       
                </div>
            </div>            

            <div class="form-group row">
                <label for="view" class="col-2 col-form-label required">{{trans('block::block.view')}}</label>
                <div class="col-8">
                    <z-field type="view" name="view" required="required"/>

                    @if ($errors->has('view'))
                    <span class="form-help text-error">{{ $errors->first('view') }}</span>
                    @else
                    <span class="form-help">{{trans('block::block.view.help')}}</span>                     
                    @endif                       
                </div>
            </div>            
            </z-form>

        </div>
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="ml-auto">
        </div>
    </div>
</div>
@endsection

