<span class="badge badge-primary mt-0 mb-2">
    {{trans('developer::form.example')}}
</span>
<div>
    {{Form::field($attribute->toArray())}}
</div>
<span class="badge badge-warning mt-4 mb-2">
    {{trans('developer::form.code')}}
</span>
<div class="input-group">
    @if($attribute->has('options') && is_array($attribute->get('options')))
        <textarea class="form-control bg-light" id="code-{{$control}}" rows="4">
{{Html::tag('z-field', $attribute->toArray())}}
        </textarea>
    @else
        <input class="form-control bg-light" id="code-{{$control}}"
               value="{{Html::tag('z-field', $attribute->toArray())}}"/>
    @endif
    <div class="input-group-append">
        <button class="btn btn-light btn-copy" type="button"
                data-clipboard-target="#code-{{$control}}"
                data-success="{{trans('master.copied')}}" data-toggle="tooltip"
                title="{{trans('master.copy')}}">
            <i class="far fa-copy"></i>
        </button>
    </div>
</div>

