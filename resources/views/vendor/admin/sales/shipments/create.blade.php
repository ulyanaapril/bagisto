@extends('admin::layouts.master')

@section('page_title')
    {{ __('admin::app.sales.shipments.add-title') }}
@stop

@section('content-wrapper')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <div class="content full-page">

        <form method="POST" action="{{ route('admin.sales.shipments.store', $order->id) }}" @submit.prevent="onSubmit">
            @csrf()
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.sales.shipments.index') }}'"></i>

                        {{ __('admin::app.sales.shipments.add-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.sales.shipments.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="sale-container">

                    <accordian :title="'{{ __('admin::app.sales.orders.order-and-account') }}'" :active="true">
                        <div slot="body">

                            <div class="sale-section">
                                <div class="secton-title">
                                    <span>{{ __('admin::app.sales.orders.order-info') }}</span>
                                </div>

                                <div class="section-content">
                                    <div class="row">
                                        <span class="title">
                                            {{ __('admin::app.sales.shipments.order-id') }}
                                        </span>

                                        <span class="value">
                                            <a href="{{ route('admin.sales.orders.view', $order->id) }}">#{{ $order->increment_id }}</a>
                                        </span>
                                    </div>

                                    <div class="row">
                                        <span class="title">
                                            {{ __('admin::app.sales.orders.order-date') }}
                                        </span>

                                        <span class="value">
                                            {{ $order->created_at }}
                                        </span>
                                    </div>

                                    <div class="row">
                                        <span class="title">
                                            {{ __('admin::app.sales.orders.order-status') }}
                                        </span>

                                        <span class="value">
                                            {{ $order->status_label }}
                                        </span>
                                    </div>

                                    <div class="row">
                                        <span class="title">
                                            {{ __('admin::app.sales.orders.channel') }}
                                        </span>

                                        <span class="value">
                                            {{ $order->channel_name }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="sale-section">
                                <div class="secton-title">
                                    <span>{{ __('admin::app.sales.orders.account-info') }}</span>
                                </div>

                                <div class="section-content">
                                    <div class="row">
                                        <span class="title">
                                            {{ __('admin::app.sales.orders.customer-name') }}
                                        </span>

                                        <span class="value">
                                            {{ $order->customer_full_name }}
                                        </span>
                                    </div>

                                    <div class="row">
                                        <span class="title">
                                            {{ __('admin::app.sales.orders.email') }}
                                        </span>

                                        <span class="value">
                                            {{ $order->customer_email }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </accordian>

                    <accordian :title="'{{ __('admin::app.sales.orders.address') }}'" :active="true">
                        <div slot="body">

                            <div class="sale-section">
                                <div class="secton-title">
                                    <span>{{ __('admin::app.sales.orders.billing-address') }}</span>
                                </div>

                                <div class="section-content">

                                    @include ('admin::sales.address', ['address' => $order->billing_address])

                                </div>
                            </div>

                            @if ($order->shipping_address)
                                <div class="sale-section">
                                    <div class="secton-title">
                                        <span>{{ __('admin::app.sales.orders.shipping-address') }}</span>
                                    </div>

                                    <div class="section-content">

                                        @include ('admin::sales.address', ['address' => $order->shipping_address])

                                    </div>
                                </div>
                            @endif

                        </div>
                    </accordian>

                    <accordian :title="'{{ __('admin::app.sales.orders.payment-and-shipping') }}'" :active="true">
                        <div slot="body">

                            <div class="sale-section">
                                <div class="secton-title">
                                    <span>{{ __('admin::app.sales.orders.payment-info') }}</span>
                                </div>

                                <div class="section-content">
                                    <div class="row">
                                        <span class="title">
                                            {{ __('admin::app.sales.orders.payment-method') }}
                                        </span>

                                        <span class="value">
                                            {{ core()->getConfigData('sales.paymentmethods.' . $order->payment->method . '.title') }}
                                        </span>
                                    </div>

                                    <div class="row">
                                        <span class="title">
                                            {{ __('admin::app.sales.orders.currency') }}
                                        </span>

                                        <span class="value">
                                            {{ $order->order_currency_code }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="sale-section">
                                <div class="secton-title">
                                    <span>{{ __('admin::app.sales.orders.shipping-info') }}</span>
                                </div>

                                <div class="section-content">
                                    <div class="row">
                                        <span class="title">
                                            {{ __('admin::app.sales.orders.shipping-method') }}
                                        </span>

                                        <span class="value">
                                            {{ $order->shipping_title }}
                                        </span>
                                    </div>

                                    <div class="row">
                                        <span class="title">
                                            {{ __('admin::app.sales.orders.shipping-price') }}
                                        </span>

                                        <span class="value">
                                            {{ core()->formatBasePrice($order->base_shipping_amount) }}
                                        </span>
                                    </div>

                                    <div class="control-group" style="margin-top: 40px">
                                        <label for="shipment[carrier_title]">{{ __('admin::app.sales.shipments.carrier-title') }}</label>
                                        <input type="text" class="control" id="shipment[carrier_title]" name="shipment[carrier_title]"/>
                                    </div>

                                    <div class="control-group">
                                        <label for="shipment[track_number]">{{ __('admin::app.sales.shipments.tracking-number') }}</label>
                                        <input type="text" class="control" id="shipment[track_number]" name="shipment[track_number]"/>
                                    </div>
                                </div>
                                @if ($order->shipping_method == 'np')
                                    <order-shipping-np></order-shipping-np>
                                @endif
                                @if ($order->shipping_method == 'justin')
                                    <order-shipping-justin></order-shipping-justin>
                                @endif
                                @if ($order->shipping_method == 'ukrposhta')
                                    <order-shipping-ukrposhta></order-shipping-ukrposhta>
                                @endif

                            </div>
                        </div>
                    </accordian>

                    <accordian :title="'{{ __('admin::app.sales.orders.products-ordered') }}'" :active="true">
                        <div slot="body">

                            <order-item-list></order-item-list>

                        </div>
                    </accordian>

                </div>
            </div>
        </form>
    </div>
@stop

@push('scripts')

    <script type="text/x-template" id="order-item-list-template">
        <div>
        <div class="control-group" :class="[errors.has('shipment[source]') ? 'has-error' : '']">
            <label for="shipment[source]" class="required">{{ __('admin::app.sales.shipments.source') }}</label>

            <select v-validate="'required'" class="control" name="shipment[source]" id="shipment[source]" data-vv-as="&quot;{{ __('admin::app.sales.shipments.source') }}&quot;" v-model="source">
                <option value="">{{ __('admin::app.sales.shipments.select-source') }}</option>

                @foreach ($order->channel->inventory_sources as $key => $inventorySource)
                    <option value="{{ $inventorySource->id }}">{{ $inventorySource->name }}</option>
                @endforeach

            </select>

            <span class="control-error" v-if="errors.has('shipment[source]')">
                @{{ errors.first('shipment[source]') }}
            </span>
        </div>

        <div class="table">

            <table>
                <thead>
                    <tr>
                        <th>{{ __('admin::app.sales.orders.SKU') }}</th>
                        <th>{{ __('admin::app.sales.orders.product-name') }}</th>
                        <th>{{ __('admin::app.sales.shipments.qty-ordered') }}</th>
                        <th>{{ __('admin::app.sales.shipments.qty-invoiced') }}</th>
                        <th>{{ __('admin::app.sales.shipments.qty-to-ship') }}</th>
                        <th>{{ __('admin::app.sales.shipments.available-sources') }}</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($order->items as $item)
                        @if ($item->qty_to_ship > 0 && $item->product)
                            <tr>
                                <td>{{ $item->getTypeInstance()->getOrderedItem($item)->sku }}</td>
                                <td>
                                    {{ $item->name }}

                                    @if (isset($item->additional['attributes']))
                                        <div class="item-options">

                                            @foreach ($item->additional['attributes'] as $attribute)
                                                <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                            @endforeach

                                        </div>
                                    @endif
                                </td>
                                <td>{{ $item->qty_ordered }}</td>
                                <td>{{ $item->qty_invoiced }}</td>
                                <td>{{ $item->qty_to_ship }}</td>
                                <td>

                                    <table>
                                        <thead>
                                            <tr>
                                                <th>{{ __('admin::app.sales.shipments.source') }}</th>
                                                <th>{{ __('admin::app.sales.shipments.qty-available') }}</th>
                                                <th>{{ __('admin::app.sales.shipments.qty-to-ship') }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($order->channel->inventory_sources as $key => $inventorySource)
                                                <tr>
                                                    <td>
                                                        {{ $inventorySource->name }}
                                                    </td>

                                                    <td>
                                                        @php
                                                            $product = $item->getTypeInstance()->getOrderedItem($item)->product;

                                                            $sourceQty = $product->type == 'bundle' ? $item->qty_ordered : $product->inventory_source_qty($inventorySource->id);
                                                        @endphp

                                                        {{ $sourceQty }}
                                                    </td>

                                                    <td>
                                                        @php
                                                            $inputName = "shipment[items][$item->id][$inventorySource->id]";
                                                        @endphp

                                                        <div class="control-group" :class="[errors.has('{{ $inputName }}') ? 'has-error' : '']">

                                                            <input type="text" v-validate="'required|numeric|min_value:0|max_value:{{$sourceQty}}'" class="control" id="{{ $inputName }}" name="{{ $inputName }}" value="{{ $item->qty_invoiced }}" data-vv-as="&quot;{{ __('admin::app.sales.shipments.qty-to-ship') }}&quot;" :disabled="source != '{{ $inventorySource->id }}'"/>

                                                            <span class="control-error" v-if="errors.has('{{ $inputName }}')">
                                                                @verbatim
                                                                    {{ errors.first('<?php echo $inputName; ?>') }}
                                                                @endverbatim
                                                            </span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endif
                    @endforeach

                </tbody>
            </table>
        </div>
        </div>

    </script>

    <script type="text/x-template" id="order-shipping-np-template">
        <div>
            <div class="secton-title">
                <span>{{ __('admin::app.sales.orders.create-ttn') }}</span>
            </div>

            <div class="section-content">
                <div class="row">
                    <div class="col-6 control-group">
                        <label for="cost" class="mandatory">
                            {{ __('admin::app.sales.orders.cost') }}
                        </label>

                        <input
                                type="text"
                                class="control"
                                id="cost"
                                v-validate="'required'"
                                name="cost"
                                value="0"
                                v-model="cost"
                                data-vv-as="&quot;{{ __('admin::app.sales.orders.cost') }}&quot;"/>
                    </div>
                    <div class="col-6 control-group">
                        <label for="seatsamount" class="mandatory">
                            {{ __('admin::app.sales.orders.seatsamount') }}
                        </label>

                        <input
                                type="text"
                                class="control"
                                id="seatsamount"
                                v-validate="'required'"
                                name="seatsamount"
                                value="1"
                                v-model="seatsamount"
                                data-vv-as="&quot;{{ __('admin::app.sales.orders.seatsamount') }}&quot;"/>
                    </div>
                    <div class="col-6 control-group">
                        <label for="weight" class="mandatory">
                            {{ __('admin::app.sales.orders.weight') }}
                        </label>

                        <select
                                type="text"
                                id="weight"
                                name="weight"
                                v-validate="'required'"
                                class="control select2"
                                v-model="weight"
                                data-vv-as="&quot;{{ __('admin::app.sales.orders.weight') }}&quot;">

                            <option :value="1">1кг</option>
                            <option :value="1.5">1.5кг</option>
                            <option :value="0.10">0.10кг(коробки)</option>

                        </select>
                    </div>
                    <div class="col-6 control-group">
                        <label for="сargotype" class="mandatory">
                            {{ __('admin::app.sales.orders.сargoеype') }}
                        </label>

                        <select
                                type="text"
                                id="cargotype"
                                name="cargotype"
                                v-validate="'required'"
                                v-model="cargotype"
                                class="control select2"
                                data-vv-as="&quot;{{ __('admin::app.sales.orders.сargotype') }}&quot;">

                            <option :value="36">36/20/20</option>
                            <option :value="38">38/28/23</option>
                            <option :value="40">40/40/40</option>

                        </select>
                    </div>
                </div>
            </div>
            <div class="informer-widget">
                <div class="alert alert-info alert-info-product fade" role="alert">
                    <span></span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="page-action">
                <a class="btn btn-sm btn-primary"
                   @click="createTtn()">{{ __('admin::app.sales.orders.create-ttn') }}
                </a>
                <a class="btn btn-sm btn-primary"
                        :class="`${trackNumber == '' ? 'disabled' : ''}`"
                        :href="printTtn"
                        target="_blank"
                        type="button">
                    <i class="icon ion-ios-print-outline" data-tooltip="tooltip"></i><span>{{ __('admin::app.sales.orders.print-100') }}</span>
                </a>
            </div>
            <input type="hidden" name="npKey" ref="npKey" id="npKey" value='{{env('NP_KEY')}}'>
            <input type="hidden" name="npTrack" ref="npTrack" id="npTrack" value='{{$order->track_number}}'>
        </div>
    </script>
    <script type="text/x-template" id="order-shipping-ukrposhta-template">
        <div>
            <div class="secton-title">
                <span>{{ __('admin::app.sales.orders.create-ttn') }}</span>
            </div>

            <div class="section-content">
                <div class="row">
                    <div class="control-group">
                        <label class="form-control-label required" for="ukrposhta-first-name">{{ __('admin::app.sales.orders.first-name') }}</label>
                        <input type="text" class="control" name="ukrposhta-first-name" v-validate="'required'" v-model="firstName">
                    </div>
                    <div class="control-group">
                        <label class="form-control-label required" for="ukrposhta-last-name">{{ __('admin::app.sales.orders.last-name') }}</label>
                        <input type="text" class="control" name="ukrposhta-last-name" v-validate="'required'" v-model="lastName">
                    </div>
                    <div class="control-group">
                        <label class="form-control-label required" for="ukrposhta-phone">{{ __('admin::app.sales.orders.phone') }}</label>
                        <input type="text" class="control" name="ukrposhta-phone" v-validate="'required'" v-model="phone">
                    </div>
                    <div class="control-group">
                        <label class="form-control-label required" for="ukrposhta-postcode">{{ __('admin::app.sales.orders.postcode') }}</label>
                        <input type="text" class="control" name="ukrposhta-postcode" v-validate="'required'" v-model="postcode">
                    </div>
                    <div class="control-group">
                        <label class="form-control-label required" for="ukrposhta-city">{{ __('admin::app.sales.orders.city') }}</label>
                        <input type="text" class="control" name="ukrposhta-city" v-validate="'required'" v-model="city">
                    </div>
                    <div class="control-group">
                        <label class="form-control-label required" for="ukrposhta-state">{{ __('admin::app.sales.orders.state') }}</label>
                        <input type="text" class="control" name="ukrposhta-state" v-validate="'required'" v-model="state">
                    </div>
                    <div class="control-group">
                        <label class="form-control-label required" for="ukrposhta-district">{{ __('admin::app.sales.orders.district') }}</label>
                        <input type="text" class="control" name="ukrposhta-district" v-validate="'required'" v-model="district">
                    </div>
                    <div class="control-group">
                        <label class="form-control-label required" for="ukrposhta-street">{{ __('admin::app.sales.orders.street') }}</label>
                        <input type="text" class="control"  name="ukrposhta-street" v-validate="'required'" v-model="street">
                    </div>
                    <div class="control-group">
                        <label class="form-control-label required" for="ukrposhta-house">{{ __('admin::app.sales.orders.house') }}</label>
                        <input type="text" class="control" name="ukrposhta-house" v-validate="'required'" v-model="house">
                    </div>
                    <div class="control-group">
                        <label class="form-control-label required" for="ukrposhta-apartment">{{ __('admin::app.sales.orders.apartment') }}</label>
                        <input type="text" class="control" name="ukrposhta-apartment" v-validate="'required'" v-model="apartment">
                    </div>
                    <div class="control-group">
                        <label class="form-control-label required" for="ukrposhta-weight">{{ __('admin::app.sales.orders.parcel-weight') }}</label>
                        <input type="text" required class="control" name="ukrposhta-weight" v-validate="'required'" v-model="weight">
                    </div>
                    <div class="control-group">
                        <label class="control-label required" for="ukrposhta-biggest-side">{{ __('admin::app.sales.orders.biggest-side') }}</label>
                        <input type="text" class="control" required name="ukrposhta-biggest-side" v-validate="'required'" v-model="biggestSide">
                    </div>
                    <div class="control-group">
                        <label class="control-label required" for="ukrposhta-declared-value">{{ __('admin::app.sales.orders.declared-value') }}</label>
                        <input type="text" class="control" value='{{$order->sub_total}}' required name="ukrposhta-declared-value" v-validate="'required'" v-model="declaredValue">
                    </div>
                    <div class="control-group">
                        <label class="control-label required" for="ukrposhta-postpayd">{{ __('admin::app.sales.orders.postpayd') }}</label>
                        <input type="text" class="control" value='{{$order->sub_total}}' required name="ukrposhta-postpayd" v-validate="'required'" v-model="postpayd">
                    </div>
                    <div class="control-group">
                        <label class="form-control-label required" for="ukrposhta-additional-info">{{ __('admin::app.sales.orders.additional-info') }}</label>
                        <textarea type="text" class="control" name="ukrposhta-additional-info" v-validate="'required'" v-model="additionalInfo"></textarea>
                    </div>
                    <div class="control-group">
                        <label for="ukrposhta-group" class="required">
                            {{ __('admin::app.sales.orders.group') }}
                        </label>

                        <select
                                type="text"
                                id="ukrposhta-group"
                                name="ukrposhta-group"
                                v-validate="'required'"
                                class="control select2"
                                v-model="group"
                                data-vv-as="&quot;{{ __('admin::app.sales.orders.group') }}&quot;">

                            <option :value="1">1</option>
                            <option :value="2">2</option>

                        </select>
                    </div>
                    <div class="control-group">
                        <label for="ukrposhta-enroll-postpayment">
                            {{ __('admin::app.sales.orders.enroll-postpayment') }}
                        </label>

                        <select
                                type="text"
                                id="ukrposhta-enroll-postpayment"
                                name="ukrposhta-enroll-postpayment"
                                v-validate="'required'"
                                class="control select2"
                                v-model="enrollPostpayment"
                                data-vv-as="&quot;{{ __('admin::app.sales.orders.enroll-postpayment') }}&quot;">

                            <option :value="1">{{ __('admin::app.sales.orders.yes') }}</option>
                            <option :value="0">{{ __('admin::app.sales.orders.no') }}</option>

                        </select>
                    </div>
                    <div class="control-group">
                        <label for="ukrposhta-pays-shipping">
                            {{ __('admin::app.sales.orders.pays-shipping') }}
                        </label>

                        <select
                                type="text"
                                id="ukrposhta-pays-shipping"
                                name="ukrposhta-pays-shipping"
                                v-model="paysShipping"
                                v-validate="'required'"
                                class="control select2"
                                data-vv-as="&quot;{{ __('admin::app.sales.orders.pays-shipping') }}&quot;">

                            <option :value="1">{{ __('admin::app.sales.orders.recipient') }}</option>
                            <option :value="0">{{ __('admin::app.sales.orders.sender') }}</option>

                        </select>
                    </div>
                    <div class="control-group">
                        <label for="ukrposhta-pays-postage">
                            {{ __('admin::app.sales.orders.pays-postage') }}
                        </label>

                        <select
                                type="text"
                                id="ukrposhta-pays-postage"
                                name="ukrposhta-pays-postage"
                                v-model="paysPostage"
                                v-validate="'required'"
                                class="control select2"
                                data-vv-as="&quot;{{ __('admin::app.sales.orders.pays-postage') }}&quot;">

                            <option :value="0">{{ __('admin::app.sales.orders.sender') }}</option>
                            <option :value="1">{{ __('admin::app.sales.orders.recipient') }}</option>

                        </select>
                    </div>
                    <div class="informer-widget">
                        <div class="alert alert-info alert-info-product fade" role="alert">
                            <span></span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    <div class="page-action">
                        <a class="btn btn-sm btn-primary" :disabled="isDisable()"
                            @click="createTtn()">{{ __('admin::app.sales.orders.create-ttn') }}
                        </a>
                        <a class="btn btn-sm btn-primary"
                           :class="`${trackNumber == '' ? 'disabled' : ''}`"
                           :href="printTtn"
                           target="_blank"
                           type="button">
                            <i class="icon ion-ios-print-outline" data-tooltip="tooltip"></i><span>{{ __('admin::app.sales.orders.print-100') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </script>
    <script type="text/x-template" id="order-shipping-justin-template">
        <div>
            <div class="secton-title">
                <span>{{ __('admin::app.sales.orders.create-ttn') }}</span>
            </div>

            <div class="section-content">
                <div class="row">
                    <div class="control-group">
                        <label class="form-control-label">Получатель:</label>
                        <input type="text" class="control" v-model="receiver" name="receiver">
                    </div>
                    <div class="control-group">
                        <label class="control-label">Телефон:</label>
                        <input type="text" class="control" v-model="receiver_phone" name="receiver_phone">
                    </div>
                    <div  class="control-group">
                        <label for="delivery-ajax-city-justin" class="mandatory">Місто</label>
                        <select
                                style="width:70%"
                                class="delivery-ajax-city-justin form-control control"
                                name="delivery-ajax-city-justin"
                                :v-model="city_ref">
                            <option v-for='(el, index) in city' :value="el.id">
                                @{{ el.text }}
                            </option>
                        </select>
                    </div>
                    <div class="control-group">
                        <label for="delivery-ajax-warehouse-justin" class="mandatory">
                            Виберіть відділення
                        </label>

                        <select
                                type="text"
                                id="delivery-ajax-warehouse-justin"
                                name="delivery-ajax-warehouse-justin"
                                class="form-control control"
                                v-model="warehouse_ref"
                                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.warehouse') }}&quot;">

                            <option v-for='(el, index) in warehouse' :value="el.id">
                                @{{ el.text }}
                            </option>
                        </select>
                    </div>
                    <div class="control-group">
                        <label class="form-control-label">Вага посилки: </label>
                        <input type="text" required class="control" v-model="weight" name="weight">
                    </div>
                    <div class="control-group">
                        <label class="form-control-label">Кількість місць: </label>
                        <select class="control" name="count_cargo_places" v-model="count_cargo_places" required>
                            <option value="1" selected>1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>
                    <div class="control-group">
                        <label class="form-control-label">Оцінка: </label>
                        <input type="text" required class="control" v-model="declared_cost" name="declared_cost">
                    </div>
                    <div class="control-group">
                        <label class="control-label">Оплачує доставку:</label>
                        <div>
                            <select class="control" name="delivery_payment_payer" v-model="delivery_payment_payer" required>
                                <option value="0" selected>Відправник</option>
                                <option value="1">Одержувач</option>
                            </select>
                        </div>
                    </div>

                    <div class="informer-widget">
                        <div class="alert alert-info alert-info-product fade" role="alert">
                            <span></span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    <div class="page-action">
                        <a class="btn btn-sm btn-primary"
                           @click="createTtn()">{{ __('admin::app.sales.orders.create-ttn') }}
                        </a>
                        <a class="btn btn-sm btn-primary"
                           :class="`${trackNumber == '' ? 'disabled' : ''}`"
                           :href="printTtn"
                           target="_blank"
                           type="button">
                            <i class="icon ion-ios-print-outline" data-tooltip="tooltip"></i><span>{{ __('admin::app.sales.orders.print-100') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </script>
    <script>
        Vue.component('order-shipping-ukrposhta', {

            template: '#order-shipping-ukrposhta-template',

            inject: ['$validator'],

            data: function() {
                return {
                    trackNumber:'',
                    printTtn:'',
                    source: "",
                    weight: 0,
                    biggestSide: 0,
                    declaredValue: '{{$order->sub_total}}',
                    postpayd: '{{$order->sub_total}}',
                    additionalInfo: '',
                    group: 1,
                    enrollPostpayment: 0,
                    paysShipping: 1,
                    paysPostage: 0,
                    firstName: '{{$order->shipping_address->first_name}}',
                    lastName: '{{$order->shipping_address->last_name}}',
                    phone: '{{$order->shipping_address->phone}}',
                    postcode: '{{$order->shipping_address->postcode}}',
                    city: '{{$order->shipping_address->city}}',
                    state: '{{$order->shipping_address->state}}',
                    district: '{{$order->shipping_address->district}}',
                    street: '{{$order->shipping_address->street}}',
                    house: '{{$order->shipping_address->house}}',
                    apartment: '{{$order->shipping_address->apartment}}'
                }
            },

            methods: {
                isDisable() {
                    return this.firstName === '' || this.lastName === '' || this.phone === '' || this.postcode === ''
                        || this.city === '' || this.state === '' || this.district === '' || this.street === ''
                        || this.house === '' || this.apartment === '';
                },
                createTtn: function () {
                    this.$http.post("{{ route('red.ukrposhta.create-ttn', ['orderId' => $order->id]) }}", {
                        'weight': this.weight,
                        'length': this.biggestSide,
                        'declaredPrice': this.declaredValue,
                        'postPay': this.postpayd,
                        'description': this.additionalInfo,
                        'shipmentGroupUuid': this.group,
                        'transferPostPayToBankAccount': this.enrollPostpayment,
                        'paidByRecipient': this.paysShipping,
                        'postPayPaidByRecipient': this.paysPostage,
                        'lastName': this.lastName,
                        'firstName': this.firstName,
                        'phone': this.phone,
                        'postcode': this.postcode,
                        'city': this.city,
                        'state': this.state,
                        'district': this.district,
                        'street': this.street,
                        'house': this.house,
                        'apartment': this.apartment
                    })
                        .then(response => {
                            if (response.data.status === 500) {
                                $(".alert-info-product:first").clone().prependTo(".informer-widget").addClass('show').find('span:first').html(response.data.message);
                                if (response.data.logMessage) {
                                    console.log(response.data.logMessage)
                                }
                            } else {
                                if (response.data.data.barcode !== '' ) {
                                    $('input[name="shipment[track_number]"]').val(response.data.data.barcode);
                                    $('input[name="shipment[carrier_title]"]').val('Ukrpost');
                                    this.trackNumber = response.data.data.barcode;
                                    this.printTtn = '{{ route("red.ukrposhta.print-ttn") }}' + '?trackNumber=' + this.trackNumber;
                                    $(".alert-info-product:first").clone().prependTo(".informer-widget").addClass('show').find('span:first').html(response.data.message);
                                }

                            }
                        })
                        .catch(error => {
                            console.log(error)
                        });
                },
            },
        });
    </script>
    <script>
        Vue.component('order-shipping-justin', {

            template: '#order-shipping-justin-template',

            inject: ['$validator'],

            mounted: function () {
                this.getJustinCity()
            },

            data: function() {
                return {
                    apiKey: '08d0c6b5-1d89-11ea-abe1-0050569b41a9',
                    trackNumber: "",
                    printTtn: "",
                    source: "",
                    telephone: "{{$order->shipping_address->phone}}",
                    price: 0,
                    receiver: "{{$order->shipping_address->last_name . ' ' . $order->shipping_address->first_name}}",
                    receiver_phone: "{{$order->shipping_address->phone}}",
                    weight: 1,
                    count_cargo_places: 1,
                    declared_cost: 1,
                    delivery_payment_payer: 0,
                    city: [],
                    warehouse: [],
                    city_ref: "{{$order->shipping_address->city_ref}}",
                    warehouse_ref: "{{$order->shipping_address->warehouse_ref}}",
                }
            },

            methods: {
                createTtn: function () {
                    this.$http.post("{{ route('red.justin.create-ttn', ['orderId' => $order->id]) }}",
                        {
                            'receiver': this.receiver,
                            'receiver_phone' : this.receiver_phone,
                            'weight' : this.weight,
                            'count_cargo_places' : this.count_cargo_places,
                            'declared_cost' : this.declared_cost,
                            'delivery_payment_payer' : this.delivery_payment_payer,
                            'city_ref' : this.city_ref,
                            'warehouse_ref' : this.warehouse_ref
                        })
                        .then(response => {
                            if (response.data.status === 500) {
                                $(".alert-info-product:first").clone().prependTo(".informer-widget").addClass('show').find('span:first').html(response.data.message);
                                if (response.data.logMessage) {
                                    console.log(response.data.logMessage)
                                }
                            } else {
                                console.log(response.data)
                                if (response.data.data.data.ttn !== '' ) {
                                    $('input[name="shipment[track_number]"]').val(response.data.data.data.ttn);
                                    $('input[name="shipment[carrier_title]"]').val('Justin');
                                    this.trackNumber = response.data.data.data.ttn;
                                    this.printTtn = 'https://api.justin.ua/pms/hs/api/v1/printSticker/order?order_number={{$order->id}}' +  '&api_key=' + this.apiKey;
                                    $(".alert-info-product:first").clone().prependTo(".informer-widget").addClass('show').find('span:first').html(response.data.message);
                                }

                            }
                        })
                        .catch(error => {
                            console.log(error)
                        });
                },
                getJustinCity: function () {
                    let justinCity = `{{route('red.justin.city')}}?q=` + this.city_ref;

                    this.$http.get(justinCity)
                        .then(response => {
                            this.city = response.data;
                            this.initSelect2();
                            this.getJustinWarehouse()
                        })
                        .catch(function (error) {});
                },
                getJustinWarehouse: function () {
                    let justinWarehouse = `{{route('red.justin.warehouse')}}?q=` + this.city_ref;

                    this.$http.get(justinWarehouse)
                        .then(response => {
                            this.warehouse = response.data;
                        })
                        .catch(function (error) {});
                },
                initSelect2: function() {
                    var citiesUrl = "{{route('red.justin.cities')}}";
                    var vue = this;
                    $('.delivery-ajax-city-justin').select2({
                        minimumInputLength: 3,
                        placeholder: 'Виберіть місто',
                        "pagination": {
                            "more": true
                        },
                        ajax: {
                            url: citiesUrl,
                            dataType: 'json',
                            delay: 250,
                            processResults: function (data) {
                                return {
                                    results: data
                                };
                            },
                            cache: true
                        }
                    }).on("change", function (e) {
                        vue.city_ref = $(e.target).val();
                        vue.fetchWarehouses();
                        vue.warehouse = '';
                    });
                },

                fetchWarehouses: function () {
                    var warehousesUrl = "{{route('red.justin.warehouses')}}" + "?q=" + this.city_ref;

                    this.$http.get(warehousesUrl)
                        .then(response => {
                            this.warehouse = response.data;
                        })
                        .catch(function (error) {});
                },
            },

        });
    </script>
    <script>
        Vue.component('order-shipping-np', {
            mounted () {
                this.npKey = $('#npKey').val();
                this.npTrack = $('#npTrack').val();
                this.trackNumber = $('input[name="shipment[track_number]"]').val();
                this.printTtn = 'https://my.novaposhta.ua/orders/printDocument/orders[]/' + this.trackNumber + '/type/html/apiKey/' + this.npKey
            },

            template: '#order-shipping-np-template',

            inject: ['$validator'],

            data: function() {
                return {
                    source: "",
                    orderId: null,
                    cost: 0,
                    weight: 1,
                    cargotype: 36,
                    seatsamount: 1,
                    trackNumber: '',
                    printTtn: '',
                    npKey: "{{env('NP_KEY')}}",
                    npTrack : ''
                }
            },

            methods: {
                createTtn: function () {
                    this.$http.post("{{ route('red.np.create-ttn', ['orderId' => $order->id]) }}", {'cost': this.cost, 'weight': this.weight, 'cargotype': this.cargotype, 'seatsamount': this.seatsamount})
                        .then(response => {
                            if (response.data.status === 500) {
                                $(".alert-info-product:first").clone().prependTo(".informer-widget").addClass('show').find('span:first').html(response.data.message);
                                if (response.data.logMessage) {
                                    console.log(response.data.logMessage)
                                }
                            } else {
                                if (response.data.data[0]['IntDocNumber'] !== '' ) {
                                    $('input[name="shipment[track_number]"]').val(response.data.data[0]['IntDocNumber']);
                                    $('input[name="shipment[carrier_title]"]').val('NP');
                                    this.trackNumber = response.data.data[0]['IntDocNumber'];
                                    this.printTtn = 'https://my.novaposhta.ua/orders/printDocument/orders[]/' + this.trackNumber + '/type/html/apiKey/' + this.npKey
                                    $(".alert-info-product:first").clone().prependTo(".informer-widget").addClass('show').find('span:first').html(response.data.message);
                                }

                            }
                        })
                        .catch(error => {
                            console.log(error)
                        });
                },
            },
        });
    </script>

    <script>
        Vue.component('order-item-list', {

            template: '#order-item-list-template',

            inject: ['$validator'],

            data: function() {
                return {
                    source: "",
                }
            },
        });
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


@endpush