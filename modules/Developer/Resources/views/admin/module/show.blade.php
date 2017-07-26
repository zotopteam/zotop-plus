@extends('core::layouts.master')

@section('content')
@include('developer::module.side')
<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>
    </div>
    <div class="main-body scrollable">

        <table class="table table-hover">
            <tr>
                <td class="left" width="15%">{{trans('developer::module.name.label')}}</td>
                <td class="right">{{$json->name}}</td>
            </tr>
            <tr>
                <td class="left" width="15%">{{trans('developer::module.alias.label')}}</td>
                <td class="right">{{$json->alias}}</td>
            </tr>  
            <tr>
                <td class="left" width="15%">{{trans('developer::module.title.label')}}</td>
                <td class="right">
                    {{$json->title}}
                    <a href="javascript:;" title="{{trans('core::master.edit')}}" class="btn btn-sm js-prompt" data-url="{{route('developer.module.update',[$name,'title'])}}" data-value="{{$json->title}}" data-prompt="{{trans('developer::module.title.label')}}">
                        <i class="fa fa-edit"></i> {{trans('core::master.edit')}}
                    </a> 

                    <div class="text-sm text-muted pt-3">{{trans('developer::module.title.help',[strtolower($name)])}}</div>                   
                </td>
            </tr>
            <tr>
                <td class="left" width="15%">{{trans('developer::module.description.label')}}</td>
                <td class="right">
                    {{$json->description}}
                    <a href="javascript:;" title="{{trans('core::master.edit')}}" class="btn btn-sm js-prompt" data-url="{{route('developer.module.update',[$name,'description'])}}" data-value="{{$json->description}}" data-prompt="{{trans('developer::module.description.label')}}" data-type="textarea">
                        <i class="fa fa-edit"></i> {{trans('core::master.edit')}}
                    </a>

                    <div class="text-sm text-muted pt-3">{{trans('developer::module.description.help',[strtolower($name)])}}</div>
                </td>
            </tr>
            <tr>
                <td class="left" width="15%">{{trans('developer::module.version.label')}}</td>
                <td class="right">
                    {{$json->version}}
                    <a href="javascript:;" title="{{trans('core::master.edit')}}" class="btn btn-sm js-prompt" data-url="{{route('developer.module.update',[$name,'version'])}}" data-value="{{$json->version}}" data-prompt="{{trans('developer::module.version.label')}}">
                        <i class="fa fa-edit"></i> {{trans('core::master.edit')}}
                    </a>
                </td>
            </tr>              
            <tr>
                <td class="left" width="15%">{{trans('developer::module.order.label')}}</td>
                <td class="right">
                    {{$json->order}}
                    <a href="javascript:;" title="{{trans('core::master.edit')}}" class="btn btn-sm js-prompt" data-url="{{route('developer.module.update',[$name,'order'])}}" data-value="{{$json->order}}" data-prompt="{{trans('developer::module.order.label')}}">
                        <i class="fa fa-edit"></i> {{trans('core::master.edit')}}
                    </a>
                </td>
            </tr>            
            <tr>
                <td class="left" width="15%">{{trans('developer::module.author.label')}}</td>
                <td class="right">
                    {{$json->author}}
                    <a href="javascript:;" title="{{trans('core::master.edit')}}" class="btn btn-sm js-prompt" data-url="{{route('developer.module.update',[$name,'author'])}}" data-value="{{$json->author}}" data-prompt="{{trans('developer::module.author.label')}}">
                        <i class="fa fa-edit"></i> {{trans('core::master.edit')}}
                    </a>
                </td>
            </tr>
            <tr>
                <td class="left" width="15%">{{trans('developer::module.email.label')}}</td>
                <td class="right">
                    {{$json->email}}
                    <a href="javascript:;" title="{{trans('core::master.edit')}}" class="btn btn-sm js-prompt" data-url="{{route('developer.module.update',[$name,'email'])}}" data-value="{{$json->email}}" data-prompt="{{trans('developer::module.email.label')}}">
                        <i class="fa fa-edit"></i> {{trans('core::master.edit')}}
                    </a>
                </td>
            </tr>
            <tr>
                <td class="left" width="15%">{{trans('developer::module.homepage.label')}}</td>
                <td class="right">
                    {{$json->homepage}}
                    <a href="javascript:;" title="{{trans('core::master.edit')}}" class="btn btn-sm js-prompt" data-url="{{route('developer.module.update',[$name,'homepage'])}}" data-value="{{$json->homepage}}" data-prompt="{{trans('developer::module.homepage.label')}}">
                        <i class="fa fa-edit"></i> {{trans('core::master.edit')}}
                    </a>
                </td>
            </tr>                                                  
            <tr>
                <td class="left" width="15%">{{trans('developer::module.providers.label')}}</td>
                <td class="right">
                    @if ($json->providers)
                    <table class="table table-sm table-inside bg-transparent">
                        @foreach($json->providers as $k=>$v)
                        <tr>
                            <td class="px-0 border-0">{{$v}}</td>
                        </tr>
                        @endforeach
                    </table>
                    @endif

                    <div class="text-sm text-muted pt-3">{{trans('developer::module.providers.help',[$path])}}</div>
                </td>
            </tr>
            <tr>
                <td class="left" width="15%">{{trans('developer::module.aliases.label')}}</td>
                <td class="right">
                    @if ($json->aliases)
                    <table class="table table-sm table-inside bg-transparent">
                        @foreach($json->aliases as $k=>$v)
                        <tr>
                            <td class="px-0 border-0">{{$k}}</td>
                            <td class="border-0">{{$v}}</td>
                        </tr>
                        @endforeach
                    </table>
                    @else
                    {{trans('core::master.nodata')}}
                    @endif

                    <div class="text-sm text-muted pt-3">{{trans('developer::module.aliases.help',[$path])}}</div>
                </td>
            </tr>
            <tr>
                <td class="left" width="15%">{{trans('developer::module.files.label')}}</td>
                <td class="right">
                    @if ($json->files)
                    <table class="table table-sm table-inside bg-transparent">
                        @foreach($json->files as $k=>$v)
                        <tr>
                            <td class="px-0 border-0">{{$v}}</td>
                        </tr>
                        @endforeach
                    </table>
                    @endif

                    <div class="text-sm text-muted pt-3">{{trans('developer::module.files.help',[$path])}}</div>
                </td>
            </tr>                        
        </table>
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="footer-text mr-auto">
            {{trans('developer::module.path')}}: {{$path}}
        </div>
    </div>
</div>


@endsection

@push('js')

@endpush