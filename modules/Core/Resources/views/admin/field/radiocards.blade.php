<div class="radiocards {{$class}} {{$column ? "radiocards-column-{$column}" : ''}}">
    <div class="radiocards-item">
        @foreach((array)$options as $k=>$v)
        <label>
            <input type="radio" id="{{$name}}-{{$k}}" class="form-control" name="{{$name}}" value="{{$k}}" {{($k==$value)?'checked':''}}/>
             @if (is_array($v))
            <div class="card" title="{{$v[2] or ''}}" data-toggle="tooltip">
                <div class="image">
                    <img class="card-img-top img-fluid" src="{{$v[0]}}" alt="{{$v[2] or ''}}">
                </div>
                @if ($v[1])
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
        <div class="radiocards-item">
        @endif
        @endforeach
    </div>
</div>
