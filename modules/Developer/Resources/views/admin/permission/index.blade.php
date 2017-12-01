@extends('core::layouts.master')

@section('content')
@include('developer::module.side')

<div class="main">
    <div class="main-header">        
        <div class="main-title mr-auto">
            {{trans('developer::permission.title')}}
        </div>
        <div class="main-action">
            <a href="javascript:;" class="btn btn-danger js-confirm" data-url="{{route('developer.permission.scan',[$module->name])}}" data-confirm="{{trans('developer::permission.scan.confirm')}}">
                <i class="fa fa-plus"></i> {{trans('developer::permission.scan')}}
            </a>
        </div>           
    </div>
    <div class="main-body scrollable">
        @if ($permissions)
        <table class="table table-hover">
            <thead>
                <tr>
                    <td>{{trans('developer::permission.key')}}</td>
                    <td>{{trans('developer::permission.val')}}</td>
                    <td>{{trans('developer::permission.name')}}</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($permissions as $key=>$val)
                {{-- 分组模式，key 为分组标题 --}}
                @if (is_array($val))
                <tr>
                    <td>
                        <i class="fa fa-fw fa-folder text-warning"></i>
                        {{trans($key)}}
                    </td>
                    <td>{{$key}}</td>
                    <td></td>
                </tr>
                @foreach ($val as $k=>$v)
                <tr>
                    <td>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <i class="fa fa-fw fa-key text-warning"></i>
                        {{$k}} 
                        
                        @if (! in_array($k, array_keys($allows)))
                            <i class="fa fa-fw fa-question-circle text-danger cur-p" data-toggle="tooltip" title="{{trans('developer::permission.question')}}"></i>
                        @else
                            @php unset($allows[$k]); @endphp
                        @endif

                    </td>
                    <td>{{$v}}</td>
                    <td>{{trans($v)}}</td>
                </tr>
                @endforeach                   
                @else
                <tr>
                    <td>
                        <i class="fa fa-fw fa-key text-warning"></i>
                        {{$key}}
                        @if (! in_array($key, array_keys($allows)))
                           <i class="fa fa-fw fa-question-circle text-danger cur-p" data-toggle="tooltip" title="{{trans('developer::permission.question')}}"></i>
                        @else
                            @php unset($allows[$key]); @endphp                       
                        @endif
                    </td>
                    <td>{{$val}}</td>
                    <td>{{trans($val)}}</td>
                </tr>
                @endif
                @endforeach
                
                @if($allows)
                <tr>
                    <td>
                        <i class="fa fa-fw fa-folder text-danger"></i>
                        {{trans('developer::permission.missing')}}
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                @foreach ($allows as $key=>$val)
                <tr>
                    <td>
                        <i class="fa fa-fw fa-key text-danger"></i>
                        {{$key}}
                    </td>
                    <td>{{$val}}</td>
                    <td>{{trans($val)}}</td>
                </tr>
                @endforeach
                @endif
            </tbody>            
        </table>
        @else
            <div class="nodata">{{trans('core::master.nodata')}}</div>
        @endif
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
