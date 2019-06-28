@extends('core::layouts.master')

@section('content')
<div class="container">
    {content:path id="$content->id"}

    <div class="content">
        <h1 class="content-title">{{$content->title}}</h1>
        <div class="content-body">
            <div id="gallery-view" class="carousel slide carousel-fade" data-ride="carousel">
                <div class="carousel-inner">
                    @foreach ($content->gallery as $gallery)                
                    <div class="carousel-item {{$loop->index==0 ? 'active' : ''}}">
                        <div class="carousel-image">
                            <img src="{{$gallery.image}}">
                        </div>
                        @if ($gallery.description)
                        <div class="carousel-caption">
                            {{$gallery.description}}
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                <a class="carousel-control-prev" href="#gallery-view" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#gallery-view" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>            
        </div>
        <div class="content-body">{!! $content->content !!}</div>        
    </div>
</div>
@endsection

@push('css')
<style type="text/css">
    .carousel-image{width:100%;height:30rem;display:flex;background:#f7f7f7;justify-content:center;align-items:center;}
    .carousel-image img{max-width:100%;max-height:100%;}
    .carousel-caption{right:0;left:0;bottom:0;background: rgba(0,0,0,.5);}
    .carousel-control-prev:hover,.carousel-control-next:hover{
        background: rgba(0,0,0,.1);
    }
    .carousel-control-prev:hover{
        background:-webkit-linear-gradient(left,rgba(0,0,0,0.3) 0%,rgba(255,255,255,0) 100%);
    }
    .carousel-control-next:hover{
        background:-webkit-linear-gradient(right,rgba(0,0,0,0.3) 0%,rgba(255,255,255,0) 100%);
    }    
</style>
@endpush
