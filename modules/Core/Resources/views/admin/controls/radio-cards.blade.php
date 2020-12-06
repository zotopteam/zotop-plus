<div class="radiocards {{$class}} clearfix">
    @foreach((array)$options as $key=>$value)
        <label class="card-check float-left" style="width:{{$column ? value(100/$column).'%' : 'auto'}};">
            <input {{$control->radio($key)->addClass('form-control-check')}}/>
            {{-- $v 数组，高级模式，显示带图片、标题、描述和提示的卡片 --}}
            {{-- $v 字符，简单模式，显示为文字卡片模式 --}}
            @if ($v = $control->advanced($value))

                <div class="card card-md {{$v->get('class','bg-light')}}"
                     @if($v->has('tooltip')) title="{{$v->get('tooltip')}}"
                     data-toggle="tooltip" @endif>
                    @if ($v->has('image'))
                        <div class="card-image">
                            <img class="card-img-top img-fluid" src="{{$v->get('image')}}">
                        </div>
                    @endif
                    @if ($v->hasMany('title','description'))
                        <div class="card-body">
                            <h5 class="card-title mb-1 text-truncate">{{$v->get('title')}}</h5>
                            <p class="card-text text-xs">
                                {{$v->get('description')}}
                            </p>
                        </div>
                    @elseif ($v->has('title'))
                        <div class="card-body">
                            <h6 class="card-title m-0 text-truncate">{{$v->get('title')}}</h6>
                        </div>
                    @elseif ($v->has('description'))
                        <div class="card-body">
                            <p class="card-text">{{$v->get('description')}}}</p>
                        </div>
                    @endif
                </div>
            @else
                <div class="card">
                    <div class="card-body">
                        <p class="card-text text-truncate">{{$value}}</p>
                    </div>
                </div>
            @endif
        </label>
    @endforeach
</div>