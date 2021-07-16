@component('shop::emails.layouts.master')
    <div style="text-align: center;">
        <a href="{{ config('app.url') }}">
            @include ('shop::emails.layouts.logo')
        </a>
    </div>

    <div style="padding: 30px;">
        <div style="font-size: 20px;color: #242424;line-height: 30px;margin-bottom: 34px;">
            <span style="font-weight: bold;">
                {{ __('shop::app.mail.order.heading') }}
            </span> <br>

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {{ __('shop::app.mail.order.dear', ['customer_name' => $order->customer_full_name]) }},
            </p>

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {!! __('shop::app.mail.order.greeting', [
                    'order_id' => '<a href="' . route('customer.orders.view', $order->id) . '" style="color: #0041FF; font-weight: bold;">#' . $order->increment_id . '</a>',
                    'created_at' => $order->created_at
                    ])
                !!}
            </p>
        </div>

        <div style="font-weight: bold;font-size: 20px;color: #242424;line-height: 30px;margin-bottom: 20px !important;">
            {{ __('shop::app.mail.order.summary') }}
        </div>

        <div style="display: flex;flex-direction: row;margin-top: 20px;justify-content: space-between;margin-bottom: 40px;">
            @if ($order->shipping_address)
                <div style="line-height: 25px;">
                    @if ($order->shipping_method == 'np' || $order->shipping_method == 'justin')
                        {{ !empty($cityName) ? __('admin::app.sales.orders.city') . ': ' . $cityName : '' }}<br>
                        {{ !empty($warehouseName) ? $warehouseName : ''}}<br>
                    @elseIf ($order->shipping_method == 'deliverypoint')
                        <?php
                        $inventorySource = null;
                        if (!empty($warehouse = $order->shipping_address->warehouse_ref)) {
                            $inventorySource = core()->getCurrentChannel()->inventory_sources()
                                ->where('id', $warehouse)
                                ->first();
                        }
                        ?>
                        @if (!empty($inventorySource))
                            {{ __('admin::app.sales.shipments.delivery-to-shop') }} : {{ !empty($inventorySource->desscription) ? $inventorySource->description : $inventorySource->name }}<br>
                        @endif
                    @else
                        @if(!empty($order->shipping_address->postcode) && !empty($order->shipping_address->city))
                            {{ __('admin::app.sales.orders.postcode') }} : {{ $order->shipping_address->postcode }}<br>
                            {{ __('admin::app.sales.orders.city') }} : {{ $order->shipping_address->city }}<br>
                        @endif
                        @if (!empty($order->shipping_address->state))
                            {{ $order->shipping_address->state }} {{ __('admin::app.sales.orders.state') }},
                        @endif
                        @if (!empty($order->shipping_address->district))
                            {{$order->shipping_address->district}} {{ __('admin::app.sales.orders.district') }},
                        @endif
                        @if (!empty($order->shipping_address->street))
                            {{ __('admin::app.sales.orders.st') }} {{ $order->shipping_address->street }}
                        @endif
                        @if (!empty($order->shipping_address->house))
                            {{ __('admin::app.sales.orders.bld') }} {{ $order->shipping_address->house }}
                        @endif
                        @if(!empty($order->shipping_address->apartment))
                            {{ __('admin::app.sales.orders.apt') }} {{ $order->shipping_address->apartment }}
                        @endif
                    @endif

                    <div style="font-size: 16px;color: #242424;">
                        {{ __('shop::app.mail.order.shipping') }}
                    </div>

                    <div style="font-weight: bold;font-size: 16px;color: #242424;">
                        {{ $order->shipping_title }}
                    </div>
                </div>
            @endif

        </div>

        <div class="section-content">
            <div class="table mb-20">
                <table style="overflow-x: auto; border-collapse: collapse;
                border-spacing: 0;width: 100%">
                    <thead>
                        <tr style="background-color: #f2f2f2">
                            <th style="text-align: left;padding: 8px">{{ __('shop::app.customer.account.order.view.SKU') }}</th>
                            <th style="text-align: left;padding: 8px">{{ __('shop::app.customer.account.order.view.product-name') }}</th>
                            <th style="text-align: left;padding: 8px">{{ __('shop::app.customer.account.order.view.price') }}</th>
                            <th style="text-align: left;padding: 8px">{{ __('shop::app.customer.account.order.view.qty') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td data-value="{{ __('shop::app.customer.account.order.view.SKU') }}" style="text-align: left;padding: 8px">{{ $item->getTypeInstance()->getOrderedItem($item)->sku }}</td>

                                <td data-value="{{ __('shop::app.customer.account.order.view.product-name') }}" style="text-align: left;padding: 8px">
                                    {{ $item->name }}

                                    @if (isset($item->additional['attributes']))
                                        <div class="item-options">

                                            @foreach ($item->additional['attributes'] as $attribute)
                                                <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                            @endforeach

                                        </div>
                                    @endif
                                </td>

                                <td data-value="{{ __('shop::app.customer.account.order.view.price') }}" style="text-align: left;padding: 8px">{{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                </td>

                                <td data-value="{{ __('shop::app.customer.account.order.view.qty') }}" style="text-align: left;padding: 8px">{{ $item->qty_ordered }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div style="font-size: 16px;color: #242424;line-height: 30px;float: right;width: 40%;margin-top: 20px;">
            <div>
                <span>{{ __('shop::app.mail.order.subtotal') }}</span>
                <span style="float: right;">
                    {{ core()->formatPrice($order->sub_total, $order->order_currency_code) }}
                </span>
            </div>

            @if ($order->shipping_address)
                <div>
                    <span>{{ __('shop::app.mail.order.shipping-handling') }}</span>
                    <span style="float: right;">
                        {{ core()->formatPrice($order->shipping_amount, $order->order_currency_code) }}
                    </span>
                </div>
            @endif

            @foreach (Webkul\Tax\Helpers\Tax::getTaxRatesWithAmount($order, false) as $taxRate => $taxAmount )
                <div>
                    <span id="taxrate-{{ core()->taxRateAsIdentifier($taxRate) }}">{{ __('shop::app.mail.order.tax') }} {{ $taxRate }} %</span>
                    <span id="taxamount-{{ core()->taxRateAsIdentifier($taxRate) }}" style="float: right;">
                        {{ core()->formatPrice($taxAmount, $order->order_currency_code) }}
                    </span>
                </div>
            @endforeach

            @if ($order->discount_amount > 0)
                <div>
                    <span>{{ __('shop::app.mail.order.discount') }}</span>
                    <span style="float: right;">
                        {{ core()->formatPrice($order->discount_amount, $order->order_currency_code) }}
                    </span>
                </div>
            @endif

            <div style="font-weight: bold">
                <span>{{ __('shop::app.mail.order.grand-total') }}</span>
                <span style="float: right;">
                    {{ core()->formatPrice($order->grand_total, $order->order_currency_code) }}
                </span>
            </div>
        </div>

        <div style="margin-top: 65px;font-size: 16px;color: #5E5E5E;line-height: 24px;display: inline-block">
            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {{ __('shop::app.mail.order.final-summary') }}
            </p>

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {!!
                    __('shop::app.mail.order.help', [
                        'support_email' => '<a style="color:#0041FF" href="mailto:' . config('mail.from.address') . '">' . config('mail.from.address'). '</a>'
                        ])
                !!}
            </p>

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {{ __('shop::app.mail.order.thanks') }}
            </p>
        </div>
    </div>
@endcomponent
