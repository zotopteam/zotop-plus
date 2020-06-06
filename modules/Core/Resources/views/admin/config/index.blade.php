@extends('layouts.master')

@section('content')
<x-sidebar data="core::config.navbar" :header="trans('core::config.title')" />
<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>
    </div>
    <div class="main-body scrollable">
        <div class="container-fluid">

            {form bind="config('module.core')" route="core.config.index" method="post" id="config" autocomplete="off"}

            {/form}
        </div>
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="mr-auto">
            {field type="submit" form="config" value="trans('master.save')" class="btn btn-primary"}
        </div>
    </div>

</div>

@endsection
