@extends('core::layouts.master')

@section('content')
@include('developer::module.side')

<div class="main">
    <div class="main-header">        
        <div class="main-title mr-auto">
            {{trans('developer::permission.title')}}
        </div>
        <div class="main-action">
            <a href="#" class="btn btn-primary"> <i class="fa fa-plus"></i> 添加</a>
        </div>           
    </div>
    <div class="main-body scrollable">
        <table class="table table-hover">
            <thead>
                <tr>
                    <td>{{trans('developer::permission.key')}}</td>
                    <td>{{trans('developer::permission.name')}}</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                @foreach ($permissions as $key=>$node)
                <tr>
                    <td>
                        {{str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $node['depth'])}}
                        <i class="fa fa-fw {{$node['depth'] ? 'fa-file' : 'fa-folder'}} text-warning"></i>
                        {{$key}}
                    </td>
                    <td>{{$node['name']}}</td>
                    <td></td>
                </tr>
                @endforeach
            </tbody>            
        </table>
        
            {{-- expr --}}
        
    </div>
    <div class="main-footer">
        <span class="footer-text">
            {{trans('developer::permission.description')}}
        </span>
        <span class="footer-text">
        {{path_base($path)}}
        </span>
    </div>
</div>
@endsection

@push('css')

@endpush

@push('js')

@endpush
