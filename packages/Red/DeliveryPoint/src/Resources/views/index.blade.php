@extends('admin::layouts.content')

@section('page_title')
    {{ __('delivery-point::app.delivery-points') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1> {{ __('delivery-point::app.delivery-points') }} </h1>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.deliverypoint.create') }}" class="btn btn-lg btn-primary">
                    {{ __('delivery-point::app.add-delivery-point') }}
                </a>
            </div>
        </div>

        {!! view_render_event('bagisto.admin.catalog.categories.list.before') !!}

        <div class="page-content">
            {!! app('Red\DeliveryPoint\DataGrids\DeliveryPointDataGrid')->render() !!}
        </div>

        {!! view_render_event('bagisto.admin.catalog.categories.list.after') !!}
    </div>
@stop

@push('scripts')
    <script>
        $(document).ready(function(){
            $("input[type='checkbox']").change(deleteFunction);
        });

        var deleteFunction = function(e,type) {
            if (type == 'delete') {
                var indexes = $(e.target).parent().attr('id');
            } else {
                $("input[type='checkbox']").attr('disabled', true);

                var formData = {};
                $.each($('form').serializeArray(), function(i, field) {
                    formData[field.name] = field.value;
                });

                var indexes = formData.indexes;
            }

            if (indexes) {
                $("input[type='checkbox']").attr('disabled', false);
                var message = "{{ __('ui::app.datagrid.click_on_action') }}";
                if (type == 'delete') {
                    doAction(e, message);
                } else {
                    $('form').attr('onsubmit', 'return confirm("'+message+'")');
                }
            }

        }
    </script>
@endpush