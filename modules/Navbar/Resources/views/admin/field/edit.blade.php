@extends('layouts.master')

@section('content')
    @include('navbar::sidebar', ['navbar_id'=>$field->navbar_id])

    <div class="main">
        <div class="main-header">
            <div class="main-back">
                <a href="{{Request::referer()}}"><i class="fa fa-angle-left"></i><b>{{trans('master.back')}}</b></a>
            </div>
            <div class="main-title">
                {{$title}}
            </div>
            <nav class="breadcrumb mr-auto">
                <a class="breadcrumb-item" href="{{route('navbar.item.index', $field->navbar_id)}}">
                    <i class="fa fa-home"></i>
                </a>
                @if($parents)
                    @foreach($parents as $p)
                        <a class="breadcrumb-item"
                           href="{{route('navbar.item.index', ['navbar_id'=>$field->navbar_id, 'parent_id'=>$p->id])}}">
                            {{$p->title}}
                        </a>
                    @endforeach
                @endif
                <a class="breadcrumb-item"
                   href="{{route('navbar.item.index', ['navbar_id'=>$field->navbar_id, 'parent_id'=>$field->parent_id])}}">
                    {{trans('navbar::field.title')}}
                </a>
            </nav>
            <div class="main-action">
                <z-field type="submit" form="form-field" value="trans('master.save')" class="btn btn-primary"/>
            </div>
        </div>

        <div class="main-body scrollable">
            <div class="container-fluid">

                <z-form bind="$field" route="['navbar.field.update', $field->id]" method="PUT" id="form-field"
                        autocomplete="off">

                    <div class="form-group row d-none">
                        <label for="navbar_id"
                               class="col-2 col-form-label required">{{trans('navbar::field.navbar_id.label')}}</label>
                        <div class="col-10">
                            <z-field type="number" name="navbar_id" required="required"/>

                            @if ($errors->has('navbar_id'))
                                <span class="form-help text-error">{{ $errors->first('navbar_id') }}</span>
                            @else
                                <span class="form-help">{{trans('navbar::field.navbar_id.help')}}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row d-none">
                        <label for="parent_id"
                               class="col-2 col-form-label required">{{trans('navbar::field.parent_id.label')}}</label>
                        <div class="col-10">
                            <z-field type="number" name="parent_id" required="required"/>

                            @if ($errors->has('parent_id'))
                                <span class="form-help text-error">{{ $errors->first('parent_id') }}</span>
                            @else
                                <span class="form-help">{{trans('navbar::field.parent_id.help')}}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="label"
                               class="col-2 col-form-label required">{{trans('navbar::field.label.label')}}</label>
                        <div class="col-10">
                            <z-field type="text" name="label" required="required" maxlength="100"/>

                            @if ($errors->has('label'))
                                <span class="form-help text-error">{{ $errors->first('label') }}</span>
                            @else
                                <span class="form-help">{{trans('navbar::field.label.help')}}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name"
                               class="col-2 col-form-label required">{{trans('navbar::field.name.label')}}</label>
                        <div class="col-10">
                            <z-field type="slug" name="name" source="label" separator="_" required="required"
                                     maxlength="100"/>

                            @if ($errors->has('name'))
                                <span class="form-help text-error">{{ $errors->first('name') }}</span>
                            @else
                                <span class="form-help">{{trans('navbar::field.name.help')}}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="type"
                               class="col-2 col-form-label required">{{trans('navbar::field.type.label')}}</label>
                        <div class="col-10">
                            <z-field type="select" name="type" options="Module::data('navbar::field.type.options')"
                                     required="required" maxlength="100"/>

                            @if ($errors->has('type'))
                                <span class="form-help text-error">{{ $errors->first('type') }}</span>
                            @else
                                <span class="form-help">{{trans('navbar::field.type.help')}}</span>
                            @endif
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="default"
                               class="col-2 col-form-label ">{{trans('navbar::field.default.label')}}</label>
                        <div class="col-10">
                            <z-field type="textarea" name="default" rows="3"/>

                            @if ($errors->has('default'))
                                <span class="form-help text-error">{{ $errors->first('default') }}</span>
                            @else
                                <span class="form-help">{{trans('navbar::field.default.help')}}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="help" class="col-2 col-form-label ">{{trans('navbar::field.help.label')}}</label>
                        <div class="col-10">
                            <z-field type="text" name="help" maxlength="255"/>

                            @if ($errors->has('help'))
                                <span class="form-help text-error">{{ $errors->first('help') }}</span>
                            @else
                                <span class="form-help">{{trans('navbar::field.help.help')}}</span>
                            @endif
                        </div>
                    </div>

                    <div id="field-settings">
                        <i class="fa fa-spinner fa-spin d-none"></i>
                    </div>

                    <div class="form-group row d-none">
                        <label for="sort" class="col-2 col-form-label ">{{trans('navbar::field.sort.label')}}</label>
                        <div class="col-10">
                            <z-field type="number" name="sort" min="0"/>

                            @if ($errors->has('sort'))
                                <span class="form-help text-error">{{ $errors->first('sort') }}</span>
                            @else
                                <span class="form-help">{{trans('navbar::field.sort.help')}}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row d-none">
                        <label for="disabled"
                               class="col-2 col-form-label ">{{trans('navbar::field.disabled.label')}}</label>
                        <div class="col-10">
                            <z-field type="toggle" name="disabled"/>

                            @if ($errors->has('disabled'))
                                <span class="form-help text-error">{{ $errors->first('disabled') }}</span>
                            @else
                                <span class="form-help">{{trans('navbar::field.disabled.help')}}</span>
                            @endif
                        </div>
                    </div>


                </z-form>


            </div>
        </div><!-- main-body -->
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        function show_settings(type) {
            var field = @json($field);
            field.type = type;

            $.post("{{route('navbar.field.settings')}}", {field: field}, function (html) {
                $('#field-settings').html(html);
                $(window).trigger('resize');
            });
        }

        $(function () {
            show_settings('{{$field->type}}');

            $('[name=type]').on('change', function () {
                show_settings($(this).val());
            });
        });
    </script>
@endpush
