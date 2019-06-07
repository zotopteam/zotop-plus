    <div class="form-group row">
        <label for="settings-options" class="col-2 col-form-label">{{trans('content::field.options.label')}}</label>
        <div class="col-8">
            <div class="settings-options form-control">
                <table class="table table-nowrap table-sortable table-hover">
                    <thead>
                        <tr>
                            <td width="10%" class="drag"></td>
                            <td>{{trans('content::field.options.text')}}</td>
                            <td width="40%">{{trans('content::field.options.value')}}</td>
                            <td width="10%"></td>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td colspan="3">
                                <a href="javascript:;" class="btn btn-primary btn-sm settings-options-add">
                                    <i class="fa fa-plus"></i> {{trans('master.create')}}
                                </a>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <script type="text/template" class="settings-options-template">
                    <tr>
                        <td class="drag"></td>
                        <td>
                            <input type="text" class="form-control required" name="settings[options][:index][text]" value=":text" placeholder="{{trans('content::field.options.text')}}">
                        </td>
                        <td>
                            <input type="text" class="form-control required" name="settings[options][:index][value]" value=":value" placeholder="{{trans('content::field.options.value')}}">
                        </td>
                        <td class="text-center"><a href="javascript:;" class="settings-options-delete"> <i class="fa fa-times"></i> </a></td>
                    </tr>            
                </script>                
            </div>                
        </div>
    </div>
    
   

    <script type="text/javascript">
        $(function(){

            var item = {text:'',value:''};
            var data = @json($field->settings->options ?? $type->settings->options ?? null) || [item, item];
                data = Object.values(data);
            var count = data.length;

            function render_options(container, template, index, value) {
                var html = template.replace(/:index/g, index).replace(/:text/g, value.text).replace(/:value/g, value.value);

                $(html).on('click', 'a.settings-options-delete', function() {
                    if (container.find('tr').length > 1) {
                        $(this).parents('tr').remove();
                        $(window).trigger('resize'); 
                    }
                }).appendTo(container);

                $(window).trigger('resize');             
            }            

            $('.settings-options').each(function() {
                var container = $(this).find('tbody');
                var template  = $(this).find('.settings-options-template').html();

                $.each(data, function(index, value){
                    render_options(container, template, index, value);
                });

                $(this).find('a.settings-options-add').on('click', function(){
                    count++;
                    render_options(container, template, count, item);
                });

                container.sortable({
                    axis: "y",
                    handle: "td.drag",
                    placeholder:"ui-sortable-placeholder",
                    start: function(e, ui){
                        ui.placeholder.height(ui.helper[0].scrollHeight);
                    },
                    helper: "clone"
                }).disableSelection();                                          
            });

        });
    </script>
    

