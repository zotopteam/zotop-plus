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

    <pre class="form-control bg-light text-sm" id="code-{{$control}}-{{$index}}"
         style="height:auto;">{{Html::tag('z-field', $attribute->toArray())}}</pre>

    <div class="input-group-append">
        <button class="btn btn-light btn-copy" type="button"
                data-clipboard-target="#code-{{$control}}-{{$index}}"
                data-success="{{trans('master.copied')}}" data-toggle="tooltip"
                title="{{trans('master.copy')}}">
            <i class="far fa-copy"></i>
        </button>
    </div>
</div>

