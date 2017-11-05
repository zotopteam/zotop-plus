@extends('install.master')

@section('content')
    <section class="main scrollable">
        <div class="jumbotron bg-transparent full-width text-center clearfix">          
            
            <h2>{{trans("installer.$current")}}</h2>
            <p>{{trans("installer.$current.description")}}</p>
        
        </div>

        <form class="form form-config form-sm" action="{{route('install.config')}}">
            
            <div class="form-title">{{trans("installer.config.site")}}</div>
            <div class="form-group">
                <label for="site[name]">{{trans("installer.config.site.name")}}</label>
                <input type="text" class="form-control" name="site[name]" value="{{trans("installer.config.site.name.value")}}" required="required">
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="admin[username]">{{trans("installer.config.admin.username")}}</label>
                    <input type="text" class="form-control" name="admin[username]" value="admin" required="required">
                </div>
                <div class="form-group col-md-6">
                    <label for="admin[password]">{{trans("installer.config.admin.password")}}</label>
                    <input type="text" class="form-control" name="admin[password]" value="admin999" required="required">
                </div>
            </div>
            <div class="form-group">
                <label for="admin[email]">{{trans("installer.config.admin.email")}}</label>
                <input type="email" class="form-control" name="admin[email]" value="{{config('admin.email','admin@admin.com')}}" required="required">
            </div>
        
            <div class="form-title">{{trans("installer.config.db")}}</div>

            <div class="form-group">
                <label for="env[DB_CONNECTION]">{{trans("installer.config.db.connection")}}</label>
                <select name="env[DB_CONNECTION]" class="form-control" id="connection">
                    <option value="mysql">Mysql</option>
                </select>
            </div>            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="env[DB_HOST]">{{trans("installer.config.db.host")}}</label>
                    <input type="text" class="form-control" name="env[DB_HOST]" value="{{env('DB_HOST')}}" required="required">
                </div>
                <div class="form-group col-md-6">
                    <label for="env[DB_PORT]">{{trans("installer.config.db.port")}}</label>
                    <input type="text" class="form-control" name="env[DB_PORT]" value="{{env('DB_PORT')}}" required="required">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="env[DB_USERNAME]">{{trans("installer.config.db.username")}}</label>
                    <input type="text" class="form-control" name="env[DB_USERNAME]" value="{{env('DB_USERNAME')}}" required="required">
                </div>
                <div class="form-group col-md-6">
                    <label for="env[DB_PASSWORD]">{{trans("installer.config.db.password")}}</label>
                    <input type="text" class="form-control" name="env[DB_PASSWORD]" value="{{env('DB_PASSWORD')}}" required="required">
                </div>
            </div>      
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="env[DB_DATABASE]">{{trans("installer.config.db.database")}}</label>
                    <input type="text" class="form-control" name="env[DB_DATABASE]" value="{{env('DB_DATABASE')}}" required="required">
                </div>
                <div class="form-group col-md-6">
                    <label for="env[DB_PREFIX]">{{trans("installer.config.db.prefix")}}</label>
                    <input type="text" class="form-control" name="env[DB_PREFIX]" value="{{env('DB_PREFIX')}}" required="required">
                </div>
            </div>

        </form>
    </section>
@endsection

@section('wizard')

            <a href="{{route("install.$prev")}}" class="btn btn-outline btn-prev text-white d-inline-block mr-auto">
                <i class="fa fa-angle-left fa-fw"></i> {{trans('installer.prev')}}
            </a>
            
            <button class="btn btn-lg btn-success form-submit d-inline-block">
                {{trans('installer.next')}} <i class="fa fa-angle-right fa-fw"></i> 
            </button>
                                 

@endsection


@push('js')
    <script type="text/javascript">

    $(function(){

        $('.form-submit').on('click',function(){
            $('.form-config').submit();
        });

        $('.form-config').validate({
         
            submitHandler:function(form){                
                var validator = this;

                $('.form-submit').find('.fa').addClass('fa-spin fa-spinner');
                $('.form-submit').prop('disabled',true);

                $.post($(form).attr('action'), $(form).serialize(), function(msg){
                    
                    if ( msg.state ) {
                        location.href = msg.url;
                        return true;
                    } else {
                        alert(msg.content);
                    }

                    $('.form-submit').find('.fa').removeClass('fa-spin fa-spinner');
                    $('.form-submit').prop('disabled',false);

                    return false;                
                },'json').fail(function(jqXHR){
                    
                    $('.form-submit').find('.fa').removeClass('fa-spin fa-spinner');                    
                    $('.form-submit').prop('disabled',false);

                    return validator.showErrors(jqXHR.responseJSON);
                });
            }            
        });
    })
</script>

@endpush