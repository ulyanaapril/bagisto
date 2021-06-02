<b>{{ $address->name }}</b><br>
 {{ !empty($address->city_ref) ? 'Місто: ' . $address->city_ref : '' }}<br>
 {{ !empty($address->warehouse_ref) ? 'Відділення: ' . $address->warehouse_ref : ''}}<br>
 {{ core()->country_name($address->country) }}<br>
 {{ __('shop::app.checkout.onepage.contact') }} : {{ $address->phone }}