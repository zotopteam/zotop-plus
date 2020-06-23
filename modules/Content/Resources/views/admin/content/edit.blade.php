@extends('layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-back">
            <a href="{{Request::session()->get('content-back-url')}}"><i
                    class="fa fa-angle-left"></i><b>{{trans('master.back')}}</b></a>
        </div>
        <div class="main-title">
            {{$title}}
        </div>
        <div class="badge badge-{{$content->status_color}} scale-n2 scale-left mr-auto">
            <i class="{{$content->status_icon}} fa-fw"></i> {{$content->status_name}}
        </div>

        <a href="javascript:;" class="text-decoration-none js-future mr-2">
            <span class="text-primary js-future-label">
                <i class="fa fa-clock"></i> {{trans('content::content.status.future')}}
            </span>
            <span class="text-secondary d-inline-block js-future-datetime">
                {{$content->publish_at > now() ? $content->publish_at : null}}
            </span>
        </a>

        <z-field type="submit" form="content-form" value="trans('content::content.save')" class="btn btn-primary" />

        <z-field type="submit" form="content-form" value="trans('content::content.publish')" class="btn btn-success"
            data-status="publish" data-action="back" />
    </div>
    <div class="main-body bg-light scrollable">
        <div class="container-fluid">

            <z-form bind="$content" route="['content.content.update', $id]" id="content-form" method="put"
                autocomplete="off">

                <z-field type="hidden" name="_action" />
                <z-field type="hidden" name="parent_id" required="required" />
                <z-field type="hidden" name="model_id" required="required" />
                <z-field type="hidden" name="source_id" required="required" />
                <z-field type="hidden" name="status" required="required" />
                <z-field type="hidden" name="publish_at" />


                <div class="row">
                    <div class="{{$form->side->count() ? 'col-9 col-md-9 col-sm-12' : 'col-12'}} d-flex flex-wrap p-0">
                        @foreach ($form->main as $item)
                        @include('content::content.field')
                        @endforeach
                    </div>

                    @if ($form->side->count())
                    <div class="col-3 col-md-3 col-sm-12 p-0">
                        @foreach ($form->side as $item)
                        @include('content::content.field')
                        @endforeach
                    </div>
                    @endif
                </div>
            </z-form>

        </div>
    </div><!-- main-body -->
</div>
@endsection

@push('js')
{!! Module::load('core:laydate/laydate.js') !!}
<script type="text/javascript">
    $(function(){

            $('.js-future').on('click',function() {
                laydate.render({
                    elem      : '.js-future-datetime',
                    closeStop : '.js-future-label',
                    type      : 'datetime',
                    btns      : ['clear','confirm'],
                    min       : '{{now()}}',
                    show      : true,
                    done: function(value){
                        $('[name=publish_at]').val(value);
                    }
                  });            
            });

            // 表单提交
            $('[type=submit]').on('click', function(event) {
                event.preventDefault();
                var status = $(this).data('status');
                var action = $(this).data('action');
                if (status) {
                    $('form.form').find('[name=status]').val(status);
                }
                if (action) {
                    $('form.form').find('[name=_action]').val(action);
                }           
                $('form.form').submit();
            });

        })
</script>
@endpush
