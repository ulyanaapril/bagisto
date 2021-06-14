<?php $paypalStandard = app('Red\Privat24\Payment\Privat24') ?>

<body data-gr-c-s-loaded="true" cz-shortcut-listen="true">
    You will be redirected to the PayPal website in a few seconds.

    <form action="{{ $paypalStandard->getPrivat24Url() }}" id="privat24_standard_checkout" method="POST">
        <div class="row">
            <input value="Click here if you are not redirected within 10 seconds..." type="submit">

            @foreach ($paypalStandard->getFormFields() as $name => $value)

                <input type="hidden" id="{{ $name }}" name="{{ $name }}" value="{{ $value }}">

            @endforeach

        </div>
    </form>

    <script type="text/javascript">
        document.getElementById("privat24_standard_checkout").submit();
    </script>
</body>