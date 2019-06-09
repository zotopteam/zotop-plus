@extends('core::layouts.dialog')

@section('content')

<div class="main">
    <div class="main-header">
        @if($keywords = request('keywords'))
            <div class="main-back">
                <a href="{{route('media.move',[$parent->id ?? 0])}}"><i class="fa fa-angle-left"></i><b>{{trans('master.back')}}</b></a>
            </div>           
            <div class="main-title mr-auto">
                {{$parent->title ?? trans('media::media.root')}}
            </div>                          
            <div class="main-title mx-auto">
                {{trans('master.searching', [$keywords])}}
            </div>        
        @else
        <div class="main-title">
            {{$parent->title ?? trans('media::media.root')}}
        </div>      
        @endif
        <div class="main-action ml-auto">
            {form route="['media.move', $parent->id ?? 0]" class="form-inline form-search" method="get"}
                <div class="input-group">
                    <input name="keywords" value="{{$keywords}}" class="form-control" type="search" placeholder="{{trans('master.keywords.placeholder')}}" required="required" aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"> <i class="fa fa-fw fa-search"></i></button>
                    </div>
                </div>
            {/form}
        </div>
        <div class="main-action">
            <a href="javascript:location.reload()" class="btn btn-light" title="{{trans('master.refresh')}}">
                <span class="fa fa-sync"></span>
            </a>
        </div>             
    </div>
    @if (empty($keywords))    
    <div class="main-header breadcrumb text-xs p-2 m-0">
        @if ($parent->id)
        <a href="{{route('media.move',[$parent->parent_id])}}" class="breadcrumb-item breadcrumb-extra">
            <i class="fa fa-fw fa-arrow-up"></i>{{trans('media::media.up')}}
        </a>
        @else
        <a href="javascript:;" class="breadcrumb-item breadcrumb-extra disabled"><i class="fa fa-arrow-up"></i>{{trans('media::media.up')}}</a>
        @endif
        <a class="breadcrumb-item" href="{{route('media.move', 0)}}">{{trans('media::media.root')}}</a>
        @foreach($parents as $p)
        <a class="breadcrumb-item" href="{{route('media.move', $p->id)}}">{{$p->name}}</a> 
        @endforeach      
    </div>
    @endif    
    <div class="main-body p-3 scrollable">
        @if($media->count() == 0)
        <div class="d-flex full-width full-height">
            <div class="align-self-center mx-auto text-muted text-center">
                <div class="p-3"><i class="fa fa-arrows-alt fa-4x"></i></div>
                <div class="p-3">{{trans('media::media.move.help')}}</div>
            </div>
        </div>
        @else
        <div class="container-fluid">
            <div class="row">
                @foreach($media as $m)
                <div class="col-sm-4 col-md-3 col-lg-2 col-xl-2 p-1">
                    <a href="{{route('media.move', $m->id)}}" class="shortcut shortcut-thumb"  title="{{$m->name}}">
                        <div class="p-2 d-flex">
                            <i class="{{$m->icon}} fa-6x text-warning align-self-center mx-auto"></i>
                        </div>
                        <div class="p-2 text-truncate">
                            {{$m->name}}
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>    
        @endif                  
    </div><!-- main-body -->
    @if ($media->lastPage() > 1)
    <div class="main-footer">
        <div class="mx-auto">
        {{ $media->appends($_GET)->links() }}
        </div>
    </div>
    @endif
</div>
@endsection

@push('js')
<script type="text/javascript">
$(function(){
    // statusbar
    currentDialog.statusbar('{{trans('media::media.move.help')}}');

    // 设置当前所在的节点编号
    currentDialog.parent_id = {{$parent->id ?? 0}};
})
</script>
@endpush
