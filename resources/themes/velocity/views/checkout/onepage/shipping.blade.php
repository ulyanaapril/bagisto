<div>
    <form data-vv-scope="shipping-form" class="shipping-form">
        <div class="form-container">
            <accordian :title="'{{ __('shop::app.checkout.onepage.shipping-method') }}'" :active="true">
                <div class="form-header" slot="header">
                    <h3 class="fw6 display-inbl">
                        {{ __('shop::app.checkout.onepage.shipping-method') }}
                    </h3>
                    <i class="rango-arrow"></i>
                </div>

                <div :class="`shipping-methods ${errors.has('shipping-form.shipping_method') ? 'has-error' : ''}`" slot="body">

                    @foreach ($shippingRateGroups as $rateGroup)

                        {!! view_render_event('bagisto.shop.checkout.shipping-method.before', ['rateGroup' => $rateGroup]) !!}
                        @foreach ($rateGroup['rates'] as $rate)
                            <div class="row col-12">
                                <div>
                                    <label class="radio-container">
                                        <input
                                                type="radio"
                                                v-validate="'required'"
                                                name="shipping_method"
                                                id="{{ $rate->method }}"
                                                value="{{ $rate->method }}"
                                                @change="methodSelected()"
                                                v-model="selected_shipping_method"
                                                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.shipping-method') }}&quot;" />

                                        <span class="checkmark"></span>
                                    </label>
                                </div>

                                <div class="pl30">
                                    <div class="row">
                                        <b>{{ core()->currency($rate->base_price) }}</b>
                                    </div>

                                    <div class="row">
                                        <b>{{ $rate->method_title }}</b> - {{ __($rate->method_description) }}
                                    </div>
                                </div>
                            </div>

                            @if ($rate->carrier == 'np')
                                <div class="delivery-{{$rate->carrier}}" hidden>
                                    <div class="col-md-12">
                                        <label for="delivery-ajax-city" class="mandatory">Місто</label>
                                        <select
                                                v-validate="'required'"
                                                class="delivery-ajax-city-{{$rate->carrier}} form-control"
                                                name="delivery-ajax-city-{{$rate->carrier}}"
                                                @change="methodSelected()"
                                                :v-model="city">
                                        </select>
                                    </div>
                                    <br>
                                    <div :class="`col-12 form-field ${errors.has('shipping-form.warehouse') ? 'has-error' : ''}`">
                                        <label for="warehouse" class="mandatory">
                                            Виберіть відділення
                                        </label>

                                        <select
                                            type="text"
                                            id="warehouse"
                                            name="warehouse"
                                            v-validate="'required'"
                                            class="form-control select2"
                                            v-model="warehouse"
                                            @change="validateMethod()"
                                            data-vv-as="&quot;{{ __('shop::app.checkout.onepage.warehouse') }}&quot;">

                                            <option :value="null" disabled></option>

                                            <option v-for='(warehouse, index) in warehouses' :value="warehouse.id">
                                                @{{ warehouse.text }}
                                            </option>
                                        </select>

{{--                                        <span class="control-error" v-if="errors.has('shipping-form.warehouse')">--}}
{{--                                            @{{ errors.first('shipping-form.warehouse') }}--}}
{{--                                        </span>--}}
                                    </div>
                                    <br>
                                </div>

                            @endif

                        @endforeach

                        {!! view_render_event('bagisto.shop.checkout.shipping-method.after', ['rateGroup' => $rateGroup]) !!}

                    @endforeach

                    <span
                            class="control-error"
                            v-if="errors.has('shipping-form.shipping_method')">

                    @{{ errors.first('shipping-form.shipping_method') }}
                </span>
                </div>
            </accordian>
        </div>
    </form>
</div>

