@extends('admin::layouts.content')

@section('page_title')
    {{ __('delivery-point::app.add-delivery-point') }}
@stop

@section('content')
    <div class="content">

        <form method="POST" action="{{ route('admin.deliverypoint.store') }}" @submit.prevent="onSubmit" enctype="multipart/form-data">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.deliverypoint.index') }}'"></i>

                        {{ __('delivery-point::app.add-delivery-point') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('delivery-point::app.save-delivery-point-btn') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()
                    <input type="hidden" name="locale" value="all"/>

                    <div slot="body">

                        <div class="control-group" :class="[errors.has('active') ? 'has-error' : '']">
                            <label for="active" class="required">{{ __('delivery-point::app.active') }}</label>
                            <select class="control" v-validate="'required'" id="active" name="active" data-vv-as="&quot;{{ __('delivery-point::app.active') }}&quot;">
                                <option value="1" {{ $point->active == 1 ? 'selected' : '' }}>
                                    Так
                                </option>
                                <option value="0" {{ $point->display_mode == 0 ? 'selected' : '' }}>
                                    Ні
                                </option>
                            </select>
                            <span class="control-error" v-if="errors.has('active')">@{{ errors.first('active') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('address') ? 'has-error' : '']">
                            <label for="address" :class="'required'">{{ __('delivery-point::app.address') }}</label>
                            <textarea v-validate="'required'" class="control" id="address" name="address" data-vv-as="&quot;{{ __('delivery-point::app.address') }}&quot;">{{ old('address')}}</textarea>
                            <span class="control-error" v-if="errors.has('address')">@{{ errors.first('address') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('info') ? 'has-error' : '']">
                            <label for="info" :class="'required'">{{ __('delivery-point::app.info') }}</label>
                            <textarea v-validate="'required'" class="control" id="info" name="info" data-vv-as="&quot;{{ __('delivery-point::app.info') }}&quot;">{{ old('info') }}</textarea>
                            <span class="control-error" v-if="errors.has('info')">@{{ errors.first('info') }}</span>
                        </div>

                    </div>

                </div>
            </div>

        </form>
    </div>
@stop

@push('scripts')
    <script src="{{ asset('vendor/webkul/admin/assets/js/tinyMCE/tinymce.min.js') }}"></script>

    <script type="text/x-template" id="description-template">

        <div class="control-group" :class="[errors.has('description') ? 'has-error' : '']">
            <label for="description" :class="isRequired ? 'required' : ''">{{ __('admin::app.catalog.categories.description') }}</label>
            <textarea v-validate="isRequired ? 'required' : ''"  class="control" id="description" name="description" data-vv-as="&quot;{{ __('admin::app.catalog.categories.description') }}&quot;">{{ old('description') }}</textarea>
            <span class="control-error" v-if="errors.has('description')">@{{ errors.first('description') }}</span>
        </div>

    </script>

    <script>
        $(document).ready(function () {
            tinymce.init({
                selector: 'textarea#description',
                height: 200,
                width: "100%",
                plugins: 'image imagetools media wordcount save fullscreen code table lists link hr',
                toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor link hr | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent  | removeformat | code | table',
                image_advtab: true
            });
        });

        Vue.component('description', {

            template: '#description-template',

            inject: ['$validator'],

            data: function() {
                return {
                    isRequired: true,
                }
            }
        })
    </script>
@endpush