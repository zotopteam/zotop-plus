<form action="{{$action}}" class="{{$class}}" method="get" {{$attributes}}>
    @foreach ($params as $k=>$v)
    <input type="hidden" name="{{$k}}" value="{{$v}}">
    @endforeach
    <div class="input-group">
        <input name="keywords" value="{{request('keywords')}}" class="form-control border-primary bg-light"
            type="search" placeholder="{{$placeholder}}" required="required" aria-label="Search">
        <div class="input-group-append">
            <button class="btn btn-primary" type="submit"> <i class="fa fa-search"></i> {{$search}} </button>
        </div>
        @if (request('keywords') && $cancel)
        <div class="input-group-append">
            <a class="btn btn-danger" href="{{$cancel}}" title="{{trans('master.cancel')}}"><i
                    class="fa fa-times"></i></a>
        </div>
        @endif
    </div>
</form>
