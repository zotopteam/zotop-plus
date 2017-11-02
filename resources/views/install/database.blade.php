@extends('install.master')

@section('content')
    <section class="main d-flex scrollable">
        <div class="jumbotron bg-transparent full-width align-self-center text-center">           
            
            @if($installed)
            <h1>{{trans("installer.$current.installed")}}</h1>
            <p>{{trans("installer.$current.installed.description", [env('DB_HOST'), env('DB_DATABASE')])}}</p>
            @else
            <h1><i class="fa fa-check-circle fa-lg"></i> </h1>
            <h1>{{trans("installer.$current")}}</h1>
            <p>{{trans("installer.$current.description", [env('DB_HOST'), env('DB_DATABASE')])}}</p>
            @endif
      
        </div>
    </section>
@endsection

@section('wizard')

            <a href="{{route("install.$prev")}}" class="btn btn-outline text-white prev d-inline-block mr-auto">
                <i class="fa fa-angle-left fa-fw"></i> {{trans('installer.prev')}}
            </a>
            
            @if($installed)
            <button class="btn btn-lg btn-danger btn-override d-inline-block ml-auto" data-confirm="{{trans("installer.$current.override.confirm")}}">
                <i class="fa fa-warning fa-fw"></i> {{trans("installer.$current.override")}} 
            </button>            
            @else              
            <button class="btn btn-lg btn-success btn-init d-inline-block ml-auto">
                {{trans('installer.next')}} <i class="fa fa-angle-right fa-fw"></i> 
            </button>
            @endif                       

@endsection

@push('js')
<script type="text/javascript">

    $('.btn-override').on('click',function(){
        
        var self    = $(this);

        if (confirm(self.data('confirm'))) {

            self.find('.fa').addClass('fa-spin fa-spinner');
            self.prop('disabled',true);

            $.post(location.href, {action:'override'}, function(msg){
                
                if ( msg.state ) {
                    location.href = msg.url;
                    return true;
                } else {
                    alert(msg.content);
                }

                self.find('.fa').removeClass('fa-spin fa-spinner');
                self.prop('disabled',false);

                return false;                
            },'json').fail(function(jqXHR){
                
                self.find('.fa').removeClass('fa-spin fa-spinner');                    
                self.prop('disabled',false);

                return alert(jqXHR.responseJSON.message);
            });
        }       
    });

    $('.btn-init').on('click',function(){
        
        var self    = $(this);

        self.find('.fa').addClass('fa-spin fa-spinner');
        self.prop('disabled',true);

        $.post(location.href, {action:'init'}, function(msg){
            
            if ( msg.state ) {
                location.href = msg.url;
                return true;
            } else {
                alert(msg.content);
            }

            self.find('.fa').removeClass('fa-spin fa-spinner');
            self.prop('disabled',false);

            return false;                
        },'json').fail(function(jqXHR){
            
            self.find('.fa').removeClass('fa-spin fa-spinner');                    
            self.prop('disabled',false);

            return alert(jqXHR.responseJSON.message);
        });

    });    
</script>
@endpush