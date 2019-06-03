<div class="radiocards {{$class}} clearfix">
    @foreach((array)$options as $k=>$v)
    <label class="card-check float-left" style="width:{{$column ? value(100/$column).'%' : 'auto'}};">
        <input type="radio" id="{{$name}}-{{$k}}" class="form-control form-control-check" name="{{$name}}" value="{{$k}}" {{($k==$value)?'checked':''}}/>
        {{-- $v 数组，高级模式，显示带图片、标题、描述和提示的卡片 --}}
        {{-- $v 字符，简单模式，显示为文字卡片模式 --}}
        @if (is_array($v))
        
        @php($class = array_get($v, 'class', 'bg-light'))
        @php($image = array_get($v, 'image'))
        @php($title = array_get($v, 'title'))
        @php($tooltip = array_get($v, 'tooltip'))
        @php($description = array_get($v, 'description'))

        <div class="card card-md {{$class}}" @if($tooltip) title="{{$tooltip}}" data-toggle="tooltip" @endif>
            @if ($image)
            <div class="card-image">
                <img class="card-img-top img-fluid" src="{{$image}}">
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
                <h5 class="card-title m-0 text-truncate">{{$title}}</h5>                                
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
                <p class="card-text text-truncate">{{$v}}</p>
            </div>                
        </div>
        @endif            
    </label>
    @endforeach
</div>
