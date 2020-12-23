<div class="side w-auto">
    <div class="side-header">
        <a href="{{route('developer.index')}}" class="mr-3"><i class="fa fa-angle-left"></i></a>
        {{trans('developer::form.title')}}
    </div>
    <div class="side-body scrollable">
        <ul class="nav nav-pills nav-side">
            @foreach(Module::data('developer::form') as $g)
                <li class="nav-item">
                    <a class="nav-link" href="{{$g['href']}}">
                        <i class="nav-icon {{$g['icon'] ?? ''}}"></i> <span class="nav-text">{{$g['text']}}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="side-footer">

    </div>
</div>
