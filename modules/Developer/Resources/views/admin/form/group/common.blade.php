<span class="badge badge-primary my-2">
    {{trans('developer::form.example')}}
</span>
<div>
    {{Form::field($attributes)}}
</div>
<span class="badge badge-warning my-2">
    {{trans('developer::form.code')}}
</span>
<div class="input-group">
    <input class="form-control bg-light" id="code-{{$control}}"
           value="{{Html::tag('z-field', $attributes)}}">
    <div class="input-group-append">
        <button class="btn btn-light btn-copy" type="button"
                data-clipboard-target="#code-{{$control}}"
                data-success="{{trans('master.copied')}}" data-toggle="tooltip"
                title="{{trans('master.copy')}}">
            <i class="far fa-copy"></i>
        </button>
    </div>
</div>

