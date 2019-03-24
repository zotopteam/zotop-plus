<div class="input-group upload-field" id="view-field-{{$id}}">
    {{Form::text($name,$value,$attrs)}}
    <span class="input-group-append">
        <button type="button" tabindex="-1" class="btn btn-light btn-icon-text btn-select" data-url="{{$select}}" data-title="{{$button}}">
            <i class="btn-icon fa fa-fw {{$icon ?? 'fa-list-alt'}}"></i>
            <b class="btn-text">{{$button}}</b>           
        </button>     
    </span>
</div>
@push('js')
    @once('select-view')
    <script type="text/javascript" src="{{Module::asset('site:js/jquery.view.js')}}"></script>
    @endonce

    <script type="text/javascript">
    $(function(){
        $("#view-field-{{$id}}").view();
    });
    </script>
@endpush

