@extends('layouts.master')

@section('content')
    @include('navbar::sidebar', ['navbar_id'=>$item->navbar_id])

    <div class="main">
        <div class="main-header">
            <div class="main-back">
                <a href="{{route('navbar.item.index', ['navbar_id'=>$item->navbar_id, 'parent_id'=>$item->parent_id])}}">
                    <i class="fa fa-angle-left"></i><b>{{trans('master.back')}}</b>
                </a>
            </div>
            <div class="main-title">
                {{$title}}
            </div>
            <nav class="breadcrumb mr-auto">
                <a class="breadcrumb-item" href="{{route('navbar.item.index', $item->navbar_id)}}">
                    <i class="fa fa-home"></i>
                </a>
                @if($parents = $item->parents)
                    @foreach($parents as $p)
                        <a class="breadcrumb-item"
                           href="{{route('navbar.item.index', ['navbar_id'=>$p->navbar_id, 'parent_id'=>$p->id])}}">
                            {{$p->title}}
                        </a>
                    @endforeach
                @endif
            </nav>
            <div class="main-action">
                <z-field type="submit" form="form-item" value="trans('master.save')" class="btn btn-primary"/>
            </div>
        </div>

        <div class="main-body scrollable">
            <div class="container-fluid">

                <z-form bind="$item" route="['navbar.item.update', $item->id]" method="PUT" id="form-item"
                        autocomplete="off">
                    <z-field type="hidden" name="navbar_id" required="required" min="0"/>
                    <z-field type="hidden" name="parent_id" required="required" min="0"/>

                    <div class="form-group row">
                        <label for="title"
                               class="col-2 col-form-label required">{{trans('navbar::item.title.label')}}</label>
                        <div class="col-10">
                            <z-field type="text" name="title" required="required" maxlength="200"/>
                            @if ($errors->has('title'))
                                <span class="form-help text-error">{{ $errors->first('title') }}</span>
                            @else
                                <span class="form-help">{{trans('navbar::item.title.help')}}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="link"
                               class="col-2 col-form-label ">{{trans('navbar::item.link.label')}}</label>
                        <div class="col-10">
                            <z-field type="link" name="link" maxlength="200"/>
                            @if ($errors->has('link'))
                                <span class="form-help text-error">{{ $errors->first('link') }}</span>
                            @else
                                <span class="form-help">{{trans('navbar::item.link.help')}}</span>
                            @endif
                        </div>
                    </div>

                    @foreach ($form->fields() as $item)
                        @include('navbar::item.field')
                    @endforeach

                    <div class="form-group row">
                        <label for="disabled"
                               class="col-2 col-form-label required">{{trans('navbar::item.disabled.label')}}</label>
                        <div class="col-10">
                            <z-field type="toggle" name="disabled" required="required"/>
                            @if ($errors->has('disabled'))
                                <span class="form-help text-error">{{ $errors->first('disabled') }}</span>
                            @else
                                <span class="form-help">{{trans('navbar::item.disabled.help')}}</span>
                            @endif
                        </div>
                    </div>

                </z-form>

            </div>
        </div><!-- main-body -->
    </div>
@endsection
