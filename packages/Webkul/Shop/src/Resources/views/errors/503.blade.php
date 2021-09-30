<html lang="en"><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="title" content="RED">
    <meta name="ROBOTS" content="RED">
    <meta name="description" content="RED">
    <meta name="abstract" content="RED">

    <meta name="author" content="red.ua">
    <meta name="publisher" content="red.ua">
    <meta name="copyright" content="red.ua">
    <meta name="revisit-after" content="2 days">
    <link rel="shortcut icon" href="/gug/img/favicon.png">

    <title>RED.UA - магазин модной одежды</title>

    <!-- Bootstrap -->
    <link href="/gug/css/bootstrap.css" rel="stylesheet">
    <link href="/gug/css/font-awesome.css" rel="stylesheet">
    <link href="/gug/css/bootstrap-theme.css" rel="stylesheet">
    <link rel="stylesheet" href="/gug/css/animations.css">

    <!-- siimple style -->
    <link href="/gug/css/style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <style>
        .home-ad{
            position: fixed;
            right: 20px;
            top:200px;
            border: 1px solid #f2f2f2;
            background: rgb(255, 255, 255);
            border-radius: 0;
            z-index: 9999;
        }
        .home-ad .close{
            float: right;
            color: #FFF;
            background: #6B6B6B;
            width: 20px;
            height: 20px;
            line-height: 16px;
            display: inline-block;
            font-size: 12px;
            font-weight: normal;
            text-align: center;
            text-decoration: none;
            text-shadow: none;
            margin-bottom: 5px;
        }
        .home-ad div#carbonads {
            padding: 10px;
            max-width: 150px;
        }
        .home-ad div#carbonads a.carbon-img {
            display: inline-block;
            float: left;
        }
        .home-ad div#carbonads .carbon-wrap:after {
            content: "";
            display: block;
            clear: both;
        }
        .home-ad div#carbonads .carbon-text {
            font-size: 12px;
            text-align: left;
            color: #333;
            display: block;
            padding-top: 10px;
            padding-bottom: 10px;
            clear: both;
        }
        .home-ad div#carbonads .carbon-text:hover {
            text-decoration: none;
        }
        .home-ad div#carbonads .carbon-poweredby {
            font-size: 75%;
            color: #bebebe;
        }
    </style>
    <script id="_carbonads_projs" type="text/javascript" src="https://srv.carbonads.net/ads/CKYIK2JE.json?segment=placement:scoopthemes&amp;callback=_carbonads_go"></script></head>

<body style="">

<div class="cloud floating">
    <img src="/gug/img/cloud.png" alt="RED">
</div>

<div class="cloud pos1 fliped floating">
    <img src="/gug/img/cloud.png" alt="RED">
</div>

<div class="cloud pos2 floating">
    <img src="/gug/img/cloud.png" alt="RED">
</div>

<div class="cloud pos3 fliped floating">
    <img src="/gug/img/cloud.png" alt="RED">
</div>


<div id="wrapper" style="height: 505px;">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <img src="/gug/img/logo.png" alt="RED Logo">
                <br>
                <br>
                <h2 class="subtitle">Ми працюємо над чимось надзвичайним для вас. Скоро повернемось.</h2>
                <br>
                <p>management@red.ua</p>
                <br>
            </div>
            <div class="col-sm-12 align-center">
                <ul class="social-network social-circle">
                    <li><a href="https://uk-ua.facebook.com/lifestyle.red.ua" class="icoFacebook" title="Facebook"><i class="fa fa-facebook"></i></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3">
                <p class="copyright">Всі права © 2021 - <a href="https://www.red.ua/">red.ua</a>
                </p>
            </div>
        </div>
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="http://downloads.mailchimp.com/js/jquery.form-n-validate.js"></script>
<script>

    $(document).ready( function () {
        $('#wrapper').height($(document).height());
        // I only have one form on the page but you can be more specific if need be.
        var $form = $('form');

        if ( $form.length > 0 ) {
            $('form input[type="submit"]').bind('click', function ( event ) {
                if ( event ) event.preventDefault();
                // validate_input() is a validation function I wrote, you'll have to substitute this with your own.
                if ( $form.validate() ) { register($form); }
            });
        }

        $('.home-ad .close').on('click', function(){
            $(this).parent().toggle('fast');
        });
    });

    function appendResult(userText , className, iconClass){
        var resultHTML = "<div class='stretchLeft result "+ className + "'>" + userText + " <span class='fa fa-" + iconClass + "'></span>" + "</div>";
        $('body').append(resultHTML);
        $('.result').delay(10000).fadeOut('1000');
    }


    function register($form) {
        $.ajax({
            type: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize(),
            cache       : false,
            dataType    : 'json',
            contentType: "application/json; charset=utf-8",
            error       : function(err) { alert("Could not connect to the registration server. Please try again later."); },
            success     : function(data) {
                if (data.result != "success") {
                    appendResult('Wrong Email Or You Are Already Registered, Try Again', 'error', 'exclamation');
                } else {
                    // It worked, carry on...
                    appendResult('Successful, Check Your Email For Confirmation ', 'success', 'check');
                }
            }
        });
    }
</script>




</body></html>