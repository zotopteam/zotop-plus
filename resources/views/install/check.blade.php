@extends('install.master')

@section('content')
    
    
    @if($check)
        <section class="main d-flex scrollable">
            <div class="jumbotron bg-transparent full-width align-self-center text-center"> 
            <h1><i class="fa fa-check-circle fa-lg"></i></h1>
            <h1>{{trans('installer.check.success')}}</h1>
            <p>{{trans('installer.check.success.description')}}</p>
            </div>
        </section>
    @else
        <section class="main scrollable">
            <div class="jumbotron bg-transparent full-width text-center"> 
                <h1><i class="fa fa-times-circle fa-lg"></i> </h1>
                <h1>{{trans('installer.check.error')}}</h1>
                <p>{{trans('installer.check.error.description')}}</p>
                <p>&nbsp;</p>
                @if($error)
                <table class="table table-sm table-dark table-hover">
                <thead>
                    <tr>
                        <td class="text-left">{{trans('installer.check.key')}}</td>
                        <td class="text-center">{{trans('installer.check.need')}}</td>
                        <td class="text-center">{{trans('installer.check.current')}}</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($error as $k=>$e)
                        @if($k=='php_version')
                            <tr>
                                <td class="text-left">{{trans('installer.check.php_version')}}</td>
                                <td class="text-center">{{$e[0]}}</td>
                                <td class="text-center">{{$e[1]}}</td>
                            </tr>
                        @elseif($k=='php_extensions')
                            @foreach($e as $key=>$val)
                            <tr>
                                <td class="text-left">{{trans('installer.check.php_extensions',[$key])}}</td>
                                <td class="text-center">{{trans('installer.check.enabled')}}</td>
                                <td class="text-center">{{trans('installer.check.disabled')}}</td>
                            </tr> 
                            @endforeach
                        @elseif($k=='apache')
                            @foreach($e as $key=>$val)
                            <tr>
                                <td class="text-left">{{trans('installer.check.apache',[$key])}}</td>
                                <td class="text-center">{{trans('installer.check.enabled')}}</td>
                                <td class="text-center">{{trans('installer.check.disabled')}}</td>
                            </tr> 
                            @endforeach
                        @elseif($k=='permissions')
                            @foreach($e as $key=>$val)
                            <tr>
                                <td class="text-left">{{trans('installer.check.permission',[$key])}}</td>
                                <td class="text-center">{{$val[0]}}</td>
                                <td class="text-center">{{$val[1] or trans('installer.check.notfound')}}</td>
                            </tr> 
                            @endforeach                                                                                                         
                        @endif                            
                    @endforeach
                </tbody>
                </table>
                @endif
            </div>
        </section>
    @endif
    
@endsection

@section('wizard')

            <a href="{{route("install.$prev")}}" class="btn btn-outline text-white prev d-inline-block mr-auto">
                <i class="fa fa-angle-left fa-fw"></i> {{trans('installer.prev')}}
            </a>
            
            @if($check)
            <a href="{{route("install.$next")}}" class="btn btn-lg btn-success d-inline-block ml-auto">
                {{trans('installer.next')}} <i class="fa fa-angle-right fa-fw"></i> 
            </a>
            @else
            <a href="{{route("install.$current")}}" class="btn btn-lg btn-primary d-inline-block ml-auto">
                <i class="fa fa-refresh fa-fw"></i> {{trans('installer.retry')}} 
            </a>            
            @endif                      

@endsection