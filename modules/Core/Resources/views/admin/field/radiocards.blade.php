<div class="radiocards {{$class}} clearfix">
@if($column)
    <div class="radiocards-row">
        @foreach((array)$options as $k=>$v)
        <label class="radiocards-col" style="width:{{value(100/$column)}}%;">
            <input type="radio" id="{{$name}}-{{$k}}" class="form-control" name="{{$name}}" value="{{$k}}" {{($k==$value)?'checked':''}}/>
            @if (is_array($v))
            <div class="card" title="{{$v[2] or ''}}" data-toggle="tooltip">
                <div class="image">
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
        @if($column && $loop->iteration%$column==0 && $loop->iteration < $loop->count)
        </div>
        <div class="radiocards-row">
        @endif
        @endforeach
    </div>
@else
    @foreach((array)$options as $k=>$v)
    <label class="radiocards-item">
        <input type="radio" id="{{$name}}-{{$k}}" class="form-control" name="{{$name}}" value="{{$k}}" {{($k==$value)?'checked':''}}/>
        @if (is_array($v))
        <div class="card" title="{{$v[2] or ''}}" data-toggle="tooltip">
            <div class="image">
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
@endif
</div>
