<div class="radiocards {{$class}} clearfix">
    @foreach((array)$options as $key=>$value)
        <label class="card-check float-left" style="width:{{$column ? value(100/$column).'%' : 'auto'}};">
            <input {{$radio($key)}}/>
            {{-- $v 数组，高级模式，显示带图片、标题、描述和提示的卡片 --}}
            {{-- $v 字符，简单模式，显示为文字卡片模式 --}}
            @if ($v = $advaced($value))

                @php($class = array_get($v, 'class', 'bg-light'))
                @php($image = array_get($v, 'image'))
                @php($title = array_get($v, 'title'))
                @php($tooltip = array_get($v, 'tooltip'))
                @php($description = array_get($v, 'description'))

                <div class="card card-md {{$v->get('class', 'bg-light')}}"
                     @if($tooltip = $v->get('tooltip')) title="{{$tooltip}}"
                     data-toggle="tooltip" @endif>
                    @if ($v->has('image'))
                        <div class="card-image">
                            <img class="card-img-top img-fluid" src="{{$v->get('image')}}">
                        </div>
                    @endif
                    @if ($title && $description)
                        <div class="card-body">
                            <h5 class="card-title mb-1 text-truncate">{{$title}}</h5>
                            <p class="card-text text-xs">
                                {{$description}}
                            </p>
                        </div>
                    @elseif ($title)
                        <div class="card-body">
                            <h6 class="card-title m-0 text-truncate">{{$title}}</h6>
                        </div>
                    @elseif ($description)
                        <div class="card-body">
                            <p class="card-text">{{$title}}}</p>
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
