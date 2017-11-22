<div class="input-group input-upload" id="input-upload-{{$id}}">
    {{Form::text($name,$value,$attrs)}}
    <span class="input-group-btn">
        <button type="button" tabindex="-1" class="btn btn-secondary btn-upload btn-progress">
            <i class="fa fa-fw {{$icon or 'fa-upload'}}"></i>
            {{$button}}
            <div class="progress d-none">
              <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width:0%">0%</div>
            </div>            
        </button>
        @if($tools)
        <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <div class="dropdown-menu dropdown-menu-primary dropdown-menu-right">
            @foreach($tools as $tool)
            <a class="dropdown-item js-open" href="javascript:;" data-url="{{$tool['href']}}">
                <i class="dropdown-item-icon fa fa-fw {{$tool['icon'] or ''}}"></i>
                <b class="dropdown-item-text">{{$tool['text']}}</b>
            </a>
            @endforeach
        </div>
        @endif        
    </span>
</div>
@push('js')
    @once('field_pupload')
    <script type="text/javascript" src="{{Module::asset('core:plupload/plupload.full.min.js')}}"></script>
    <script type="text/javascript" src="{{Module::asset('core:plupload/i18n/'.App::getLocale().'.js')}}"></script>
    <script type="text/javascript" src="{{Module::asset('core:plupload/jquery.plupload.js')}}"></script>
    @endonce

    @once('field_pupload_single')
    <script type="text/javascript" src="{{Module::asset('core:plupload/jquery.upload_single.js')}}"></script>
    @endonce

    <script type="text/javascript">
    $(function(){
        $("#input-upload-{{$id}}").upload_single({!! json_encode($options) !!});
    });
    </script>
@endpush

