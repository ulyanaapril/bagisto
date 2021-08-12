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
                                        <b>{{ !empty((int)core()->currency($rate->base_price)) ? core()->currency($rate->base_price) : '' }}</b>
                                    </div>

                                    <div class="row">
                                        <b>{{ $rate->method_title }}</b>
                                    </div>
                                </div>
                            </div>

                            <div class="delivery-{{$rate->carrier}}" hidden>
                                @if ($rate->carrier == 'np' || $rate->carrier == 'justin')
                                    <div class="col-md-12">
                                        <label for="delivery-ajax-city" class="mandatory">Місто</label>
                                        <select
                                                v-validate=" this.selected_shipping_method == '{{$rate->carrier}}' ? 'required' : '' "
                                                class="delivery-ajax-city-{{$rate->carrier}} form-control"
                                                name="delivery-ajax-city-{{$rate->carrier}}"
                                                @change="methodSelected()"
                                                :v-model="city">
                                        </select>
                                    </div>
                                    <br>
                                    <div :class="`col-12 form-field ${errors.has('shipping-form.delivery-ajax-warehouse-{{$rate->carrier}}') ? 'has-error' : ''}`">
                                        <label for="delivery-ajax-warehouse-{{$rate->carrier}}" class="mandatory">
                                            Виберіть відділення
                                        </label>

                                        <select
                                                type="text"
                                                id="delivery-ajax-warehouse-{{$rate->carrier}}"
                                                name="delivery-ajax-warehouse-{{$rate->carrier}}"
                                                v-validate=" this.selected_shipping_method == '{{$rate->carrier}}' ? 'required' : '' "
                                                class="form-control select2"
                                                v-model="warehouse"
                                                @change="validateMethod()"
                                                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.warehouse') }}&quot;">

                                            <option :value="null" disabled></option>

                                            <option v-for='(warehouse, index) in warehouses' :value="warehouse.id">
                                                @{{ warehouse.text }}
                                            </option>
                                        </select>

                                        {{--<span class="control-error" v-if="errors.has('shipping-form.warehouse')">--}}
                                        {{-- @{{ errors.first('shipping-form.warehouse') }}--}}
                                        {{--</span>--}}
                                    </div>
                                    <br>

                                @endif
                                @if ($rate->carrier == 'ukrposhta')
                                    <div class="row">
                                        <div class="delivery-{{$rate->carrier}} col-6" hidden>
                                            <div :class="`form-field ${errors.has('shipping-form.postcode') ? 'has-error' : ''}`">
                                                <label for="postcode" class="mandatory">
                                                    {{ __('shop::app.checkout.onepage.postcode') }}
                                                </label>

                                                <input
                                                        type="text"
                                                        class="form-control"
                                                        id="postcode"
                                                        v-validate=" this.selected_shipping_method == '{{$rate->carrier}}' ? 'required' : '' "
                                                        name="postcode"
                                                        v-model="postcode"
                                                        @keyup="validateMethod()"
                                                        data-vv-as="&quot;{{ __('shop::app.checkout.onepage.postcode') }}&quot;"/>
                                            </div>
                                        </div>
                                        <div class="delivery-{{$rate->carrier}} col-6" hidden>
                                            <div :class="`form-field ${errors.has('shipping-form.state') ? 'has-error' : ''}`">
                                                <label for="state" class="mandatory">
                                                    {{ __('shop::app.checkout.onepage.state') }}
                                                </label>

                                                <input
                                                        type="text"
                                                        class="form-control"
                                                        id="state"
                                                        v-validate=" this.selected_shipping_method == '{{$rate->carrier}}' ? 'required' : '' "
                                                        name="state"
                                                        v-model="state"
                                                        @keyup="validateMethod()"
                                                        data-vv-as="&quot;{{ __('shop::app.checkout.onepage.state') }}&quot;"/>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="delivery-{{$rate->carrier}} col-6" hidden>
                                            <div :class="`form-field ${errors.has('shipping-form.city') ? 'has-error' : ''}`">
                                                <label for="city" class="mandatory">
                                                    {{ __('shop::app.checkout.onepage.city') }}
                                                </label>

                                                <input
                                                        type="text"
                                                        class="form-control"
                                                        id="city"
                                                        v-validate=" this.selected_shipping_method == '{{$rate->carrier}}' ? 'required' : '' "
                                                        name="city"
                                                        v-model="city"
                                                        @keyup="validateMethod()"
                                                        data-vv-as="&quot;{{ __('shop::app.checkout.onepage.city') }}&quot;"/>
                                            </div>
                                        </div>
                                        <div class="delivery-{{$rate->carrier}} col-6" hidden>
                                            <div :class="`form-field ${errors.has('shipping-form.district') ? 'has-error' : ''}`">
                                                <label for="district" class="mandatory">
                                                    {{ __('shop::app.checkout.onepage.district') }}
                                                </label>

                                                <input
                                                        type="text"
                                                        class="form-control"
                                                        id="district"
                                                        v-validate=" this.selected_shipping_method == '{{$rate->carrier}}' ? 'required' : '' "
                                                        name="district"
                                                        v-model="district"
                                                        @keyup="validateMethod()"
                                                        data-vv-as="&quot;{{ __('shop::app.checkout.onepage.district') }}&quot;"/>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="delivery-{{$rate->carrier}} col-6" hidden>
                                            <div :class="`form-field ${errors.has('shipping-form.street') ? 'has-error' : ''}`">
                                                <label for="street" class="mandatory">
                                                    {{ __('shop::app.checkout.onepage.street') }}
                                                </label>

                                                <input
                                                        type="text"
                                                        class="form-control"
                                                        id="street"
                                                        v-validate=" this.selected_shipping_method == '{{$rate->carrier}}' ? 'required' : '' "
                                                        name="street"
                                                        v-model="street"
                                                        @keyup="validateMethod()"
                                                        data-vv-as="&quot;{{ __('shop::app.checkout.onepage.street') }}&quot;"/>
                                            </div>
                                        </div>
                                        <div class="delivery-{{$rate->carrier}} col-6" hidden>
                                            <div :class="`form-field ${errors.has('shipping-form.house') ? 'has-error' : ''}`">
                                                <label for="house" class="mandatory">
                                                    {{ __('shop::app.checkout.onepage.house') }}
                                                </label>

                                                <input
                                                        type="text"
                                                        class="form-control"
                                                        id="house"
                                                        v-validate=" this.selected_shipping_method == '{{$rate->carrier}}' ? 'required' : '' "
                                                        name="house"
                                                        v-model="house"
                                                        @keyup="validateMethod()"
                                                        data-vv-as="&quot;{{ __('shop::app.checkout.onepage.house') }}&quot;"/>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="delivery-{{$rate->carrier}} col-6" hidden>
                                            <div :class="`form-field ${errors.has('shipping-form.apartment') ? 'has-error' : ''}`">
                                                <label for="apartment" class="mandatory">
                                                    {{ __('shop::app.checkout.onepage.apartment') }}
                                                </label>

                                                <input
                                                        type="text"
                                                        class="form-control"
                                                        id="apartment"
                                                        v-validate=" this.selected_shipping_method == '{{$rate->carrier}}' ? 'required' : '' "
                                                        name="apartment"
                                                        v-model="apartment"
                                                        @keyup="validateMethod()"
                                                        data-vv-as="&quot;{{ __('shop::app.checkout.onepage.apartment') }}&quot;"/>
                                            </div>
                                        </div>
                                    </div>
                                    <br>

                                @endif
                                @if ($rate->carrier == 'deliverypoint')
                                    <div class="delivery-{{$rate->carrier}} col-6" hidden>
                                        <div :class="`col-12 form-field ${errors.has('shipping-form.delivery-ajax-warehouse-{{$rate->carrier}}') ? 'has-error' : ''}`">
                                            <label for="delivery-ajax-warehouse-{{$rate->carrier}}" class="mandatory">
                                                Виберіть відділення
                                            </label>

                                            <select
                                                    type="text"
                                                    id="delivery-ajax-warehouse-{{$rate->carrier}}"
                                                    name="delivery-ajax-warehouse-{{$rate->carrier}}"
                                                    v-validate=" this.selected_shipping_method == '{{$rate->carrier}}' ? 'required' : '' "
                                                    class="form-control select2"
                                                    v-model="warehouse"
                                                    @change="validateMethod()"
                                                    data-vv-as="&quot;{{ __('shop::app.checkout.onepage.warehouse') }}&quot;">

                                                <option :value="null" disabled></option>

                                                <option v-for='(warehouse, index) in warehouses' :value="warehouse.id">
                                                    @{{ warehouse.text }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                @endif

                            </div>


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

