@extends('layouts.master')

@section('content')
    @include('developer::form.side')

    <div class="main">
        <div class="main-header">
            <div class="main-title mr-auto">
                {{$title}}
            </div>
            <div class="main-action">

            </div>
        </div>
        <div class="main-body scrollable">

            <div class="card m-3">
                <div class="card-header">
                    {{trans('developer::form.example')}}
                </div>
                <div class="card-body">

                    <span class="badge badge-primary mt-0 mb-2">
                        {{trans('developer::form.example')}}
                    </span>

                    <z-form :bind="$bind" method="post">
                        <div class="form-group">
                            <label class="form-label required" for="title">Title</label>
                            <z-field type="text" name="title" required maxlength="200"></z-field>
                            <div class="form-help">
                                help……
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="title">Content</label>
                            <z-field type="textarea" name="content" rows="5"></z-field>
                        </div>

                        <div class="form-group">

                            <z-field type="submit"></z-field>
                        </div>

                    </z-form>

                    <span class="badge badge-warning mt-4 mb-2">
                        {{trans('developer::form.code')}}
                    </span>

                    <div class="input-group">

                        <pre class="form-control bg-light text-sm" id="code-form1"
                             style="height:auto;">
{{Html::openTag('z-form',[
    ':bind' => '$bind',
    'route' => 'developer.form.index',
    'method' => 'post',
    'autocomplete' => 'off',
])}}
……
{{Html::closeTag('z-form')}}
                        </pre>

                        <div class="input-group-append">
                            <button class="btn btn-light btn-copy" type="button"
                                    data-clipboard-target="#code-form1"
                                    data-success="{{trans('master.copied')}}" data-toggle="tooltip"
                                    title="{{trans('master.copy')}}">
                                <i class="far fa-copy"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card m-3">
                <div class="card-header">
                    {{trans('developer::form.control.attributes')}}
                </div>
                <div class="card-body text-primary">
                    {{trans('developer::form.attribute.value.help')}}
                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <td class="text-sm">{{trans('developer::form.control.attributes.key')}}</td>
                        <td class="text-sm">{{trans('developer::form.control.attributes.type')}}</td>
                        <td class="text-sm text-center">{{trans('developer::form.control.attributes.required')}}</td>
                        <td class="text-sm">{{trans('developer::form.control.attributes.example')}}</td>
                        <td class="text-sm">{{trans('developer::form.control.attributes.text')}}</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($attributes as $key=>$attribute)
                        <tr>
                            <td class="text-sm">{{$key}}</td>
                            <td class="text-sm">
                                @if($type = Arr::get($attribute, 'type'))
                                    @foreach((array)$type as $val)
                                        <div>{{$val}}</div>
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-sm text-center">
                                @if(Arr::get($attribute, 'required'))
                                    <i class="fa fa-check-circle text-success"></i>
                                @endif
                            </td>
                            <td class="text-sm">
                                @if($value = Arr::get($attribute, 'value'))
                                    @foreach((array)$value as $val)
                                        <div class="text-primary">{{$val}}</div>
                                    @endforeach
                                @elseif($examples = Arr::get($attribute, 'examples'))
                                    @foreach((array)$examples as $example)
                                        <div>{{$example}}</div>
                                    @endforeach
                                @elseif($example = Arr::get($attribute, 'example'))
                                    @if(is_array($example))
                                        <pre>{{var_export_pretty($example, true)}}</pre>
                                    @else
                                        {{$example}}
                                    @endif
                                @endif
                            </td>
                            <td class="text-sm">{{$attribute['text'] ?? ''}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>

        </div> <!-- main-body -->
    </div>
@endsection
