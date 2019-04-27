@extends('core::layouts.dialog')

@section('content')

<div class="main">
    <div class="main-header">
        @if($keywords = request('keywords'))
            <div class="main-back">
                <a href="{{route('content.content.move',[$parent->id])}}"><i class="fa fa-angle-left"></i><b>{{trans('core::master.back')}}</b></a>
            </div>           
            <div class="main-title mr-auto">
                {{$parent->title}}
            </div>                          
            <div class="main-title mx-auto">
                {{trans('core::master.searching', [$keywords])}}
            </div>        
        @else
        <div class="main-title">
            {{$parent->title}}
        </div>      
        @endif
        <div class="main-action ml-auto">
            {form route="['content.content.move', $parent->id]" class="form-inline form-search" method="get"}
                <div class="input-group">
                    <input name="keywords" value="{{$keywords}}" class="form-control" type="search" placeholder="{{trans('core::master.keywords.placeholder')}}" required="required" aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"> <i class="fa fa-fw fa-search"></i></button>
                    </div>
                </div>
            {/form}
        </div>
        <div class="main-action">
            <a href="javascript:location.reload()" class="btn btn-light" title="{{trans('core::master.refresh')}}">
                <span class="fa fa-sync"></span>
            </a>
        </div>             
    </div>
    @if (empty($keywords))    
    <div class="main-header breadcrumb text-xs p-2 m-0">
        @if ($parent->id)
        <a href="{{route('content.content.move',[$parent->parent_id])}}" class="breadcrumb-item breadcrumb-extra">
            <i class="fa fa-fw fa-arrow-up"></i>{{trans('content::content.up')}}
        </a>
        @else
        <a href="javascript:;" class="breadcrumb-item breadcrumb-extra disabled"><i class="fa fa-arrow-up"></i>{{trans('content::content.up')}}</a>
        @endif
        <a class="breadcrumb-item" href="{{route('content.content.move', 0)}}">{{trans('content::content.root')}}</a>
        @foreach($parents as $p)
        <a class="breadcrumb-item" href="{{route('content.content.move', $p->id)}}">{{$p->title}}</a> 
        @endforeach      
    </div>
    @endif    
    <div class="main-body p-3 scrollable">
        @if($contents->count() == 0)
        <div class="d-flex full-width full-height">
            <div class="align-self-center mx-auto text-muted text-center">
                <div class="p-3"><i class="fa fa-arrows-alt fa-4x"></i></div>
                <div class="p-3">{{trans('content::content.move.help')}}</div>
            </div>
        </div>
        @else
        <div class="container-fluid">
            <div class="row">
                @foreach($contents as $content)
                <div class="col-sm-4 col-md-3 col-lg-2 col-xl-2 p-1">
                    <a href="{{route('content.content.move', $content->id)}}" class="shortcut shortcut-thumb"  title="{{$content->title}}">
                        <div class="p-2 d-flex">
                            <i class="{{$content->model->icon}} fa-6x text-warning align-self-center mx-auto"></i>
                        </div>
                        <div class="p-2 text-truncate">
                            {{$content->title}}
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>    
        @endif                  
    </div><!-- main-body -->
    @if ($contents->lastPage() > 1)
    <div class="main-footer">
        <div class="mx-auto">
        {{ $contents->appends($_GET)->links('core::pagination.default') }}
        </div>
    </div>
    @endif
</div>
@endsection
@push('css')
@endpush
@push('js')
<script type="text/javascript">
$(function(){
    // statusbar
    $dialog.statusbar('{{trans('content::content.move.help')}}');

    // 设置当前所在的节点编号
    $dialog.parent_id = {{$parent->id}};
})
</script>
@endpush
