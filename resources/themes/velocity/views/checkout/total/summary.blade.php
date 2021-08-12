<div class="order-summary fs16">
    <h3 class="fw6">{{ __('velocity::app.checkout.cart.cart-summary') }}</h3>

    <div class="row">
        <span class="col-8">{{ __('velocity::app.checkout.sub-total') }}</span>
        <span class="col-4 text-right">{{ core()->currency($cart->base_sub_total) }}</span>
    </div>

    @if (
        $cart->base_discount_amount
        && $cart->base_discount_amount > 0
    )
        <div
            id="discount-detail"
            class="row">

            <span class="col-8">{{ __('shop::app.checkout.total.disc-amount') }}</span>
            <span class="col-4 text-right">
                -{{ core()->currency($cart->base_discount_amount) }}
            </span>
        </div>
    @endif

    <div class="payable-amount row" id="grand-total-detail">
        <span class="col-8">{{ __('shop::app.checkout.total.grand-total') }}</span>
        <span class="col-4 text-right fw6" id="grand-total-amount-detail">
            {{ core()->currency($cart->base_grand_total) }}
        </span>
    </div>

    <div class="row">
        @php
            $minimumOrderAmount = (float) core()->getConfigData('sales.orderSettings.minimum-order.minimum_order_amount') ?? 0;
        @endphp

        <proceed-to-checkout
            href="{{ route('shop.checkout.onepage.index') }}"
            add-class="theme-btn text-uppercase col-12 remove-decoration fw6 text-center"
            text="{{ __('velocity::app.checkout.proceed') }}"
            is-minimum-order-completed="{{ $cart->checkMinimumOrder() }}"
            minimum-order-message="{{ __('shop::app.checkout.cart.minimum-order-message', ['amount' => core()->currency($minimumOrderAmount)]) }}">
        </proceed-to-checkout>
    </div>
</div>