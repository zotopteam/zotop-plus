<div class="{{$class}}">
    <a class="breadcrumb-item breadcrumb-extra {{$up_href ? 'enabled' : 'disabled'}}"
        href="{{$up_href ?? 'javascript:;'}}">
        <i class="fa fa-arrow-up"></i> {{trans('media::media.up')}}
    </a>
    @foreach($breadcrumb as $b)
    <a class="breadcrumb-item" href="{{$b.href}}">
        <i class="{{$b.icon}} fa-fw"></i> {{$b.text}}
    </a>
    @endforeach
</div>
