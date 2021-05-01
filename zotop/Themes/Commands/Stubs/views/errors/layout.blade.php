<!doctype html>
<html lang="en">
    <head>
        <title>@yield('title')</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Styles -->
        <style>
        html {background:#0072c6;color:#fff;height:100%;line-height:1.2;font-size:16px;}
        body {margin:0;height:100%;display:flex;align-items:center;}
        .container{width:60%;align-self:center;padding:0 12rem;}
        .code{font-weight:900;font-size:8rem;}
        .title{font-size:2rem;font-weight:500;margin:.5rem 0;}
        .message{font-size:1.5rem;font-weight:300;margin:.5rem 0;line-height:1.5;}
        .btns{margin-top:2.5rem;}
        .btn{text-decoration:none;color:#fff;border:solid 1px rgba(255,255,255,.3);background:rgba(255,255,255,.1);padding:.5rem 1rem;border-radius:.5rem;text-transform: uppercase;font-size:.875rem;letter-spacing:1px;}
        .btn:hover{background:rgba(255,255,255,.3);}

        @media (max-width: 992px) {
            html{font-size:14px;}
            .container{width:80%;padding:0 4rem;}
        }

        @media (max-width: 768px) {
            html{font-size:12px;}
            .container{width:100%;padding:0 2rem;}
        }

        /*css3 animation*/
        @keyframes wave{0%{transform:translate(-90px,0);}100%{transform:translate(85px,0);}}

        /* wave */
        .animation-wave{position:fixed;right:0;bottom:0;left:0;display:block;width:100%;height:4rem;}
        .animation-wave .parallax>use{animation:wave 12s linear infinite;}
        .animation-wave .parallax>use:nth-child(1){animation-delay:-2s;}
        .animation-wave .parallax>use:nth-child(2){animation-delay:-2s;animation-duration:5s;}
        .animation-wave .parallax>use:nth-child(3){animation-delay:-4s;animation-duration:3s;}        
        </style>
    </head>
    <body>
        <div class="container">
            <div class="code">
                @yield('code', __('Oh no'))
            </div>
            <h1 class="title">
                @yield('title')
            </h1>
            <p class="message">
                @yield('message')
            </p>
            <div class="btns">
                <a href="{{url('/')}}" class="btn">
                    {{ __('Go Home') }}
                </a>
            </div>
        </div>
        <svg class="animation-wave" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28" preserveAspectRatio="none">
            <defs>
                <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"></path>
            </defs>
            <g class="parallax">
                <use xlink:href="#gentle-wave" x="50" y="0" fill="rgba(255,255,255,.3)"></use>
                <use xlink:href="#gentle-wave" x="50" y="3" fill="rgba(255,255,255,.3)"></use>
                <use xlink:href="#gentle-wave" x="50" y="6" fill="rgba(255,255,255,.3)"></use>
            </g>
        </svg>
    </body>
</html>
