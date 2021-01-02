<div class="side w-auto">
    <div class="side-header">
        <a href="{{route('developer.index')}}" class="mr-3"><i class="fa fa-angle-left"></i></a>
        {{trans('developer::form.title')}}
    </div>
    <div class="side-body scrollable">
        <ul class="nav nav-pills nav-side">
            <li class="nav-item">
                <a class="nav-link {{Route::is('developer.form.index') ? 'active' : ''}}"
                   href="{{route('developer.form.index')}}">
                    <i class="nav-icon fa-fw fa fa-list-alt"></i>
                    <span class="nav-text">
                        {{trans('developer::form.form')}}
                    </span>
                </a>
            </li>
            @foreach(Module::data('developer::form.controls') as $k=>$g)
                <li class="nav-item">
                    <a class="nav-link {{request('control') == $k ? 'active' : ''}}" href="{{$g['href']}}">
                        <i class="nav-icon fa-fw {{$g['icon'] ?? ''}}"></i> <span class="nav-text">{{$g['text']}}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="side-footer">

    </div>
</div>
