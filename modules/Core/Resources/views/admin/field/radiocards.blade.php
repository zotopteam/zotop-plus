<div class="radiocards {{$class}} clearfix">
    @foreach((array)$options as $k=>$v)
    <label class="card-check float-left" style="width:{{$column ? value(100/$column).'%' : 'auto'}};">
        <input type="radio" id="{{$name}}-{{$k}}" class="form-control form-control-check" name="{{$name}}" value="{{$k}}" {{($k==$value)?'checked':''}}/>
        {{-- $v 数组，图片卡片模式 0=图片地址 1=图片标题（可选） 2=图片描述（可选） --}}
        {{-- $v 字符，文字卡片模式 --}}
        @if (is_array($v))
        <div class="card card-md bg-light" title="{{$v[2] or ''}}" data-toggle="tooltip">
            <div class="card-image">
                <img class="card-img-top img-fluid" src="{{$v[0]}}" alt="{{$v[2] or ''}}">
            </div>
            @if (isset($v[1]) && $v[1])
            <div class="card-body text-overflow">
                {{$v[1]}}                                   
            </div>
            @endif
        </div>
        @else
        <div class="card">
            <div class="card-body text-overflow">
                {{$v}}
            </div>                
        </div>
        @endif            
    </label>
    @endforeach
</div>
