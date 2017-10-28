<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>{{config('app.name')}} Installer</title>
        <link rel="icon" type="image/png" href="{{ asset('installer/favicon-16x16.png') }}" sizes="16x16"/>
        <link rel="icon" type="image/png" href="{{ asset('installer/favicon-32x32.png') }}" sizes="32x32"/>
        <link rel="icon" type="image/png" href="{{ asset('installer/favicon-96x96.png') }}" sizes="96x96"/>        
        <link href="{{ asset('installer/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <script src="{{ asset('installer/bootstrap.min.js') }}" type="text/javascript"></script>
        <style type="text/css">
            html,body{height:100%;color:#fff;}
            body{padding-bottom:0rem;}

            .bg-primary{background-color:#0072c6!important;}
            .bg-transparent{background-color:transparent!important;}

            .full-width{width:100%;}
            .full-height{height:100%;}

            .btn-outline{border-color:rgba(255,255,255,.25);}
            .btn-outline:hover,.btn-outline:focus,.btn-outline:active{background:rgba(255,255,255,.15);}

            .jumbotron{border-radius:0;}
            .jumbotron h1{font-size:6rem;margin-bottom:1rem;font-weight:400;line-height:1.5;}
            .jumbotron p{font-size:1.25rem;margin-bottom:1rem;}            

            .header{padding:0;border-bottom:solid 1px rgba(255,255,255,0.1);}
            .main{overflow-x: auto;}
            .footer{padding:1.5rem;border-top:solid 1px rgba(255,255,255,0.1);}
            .footer .btn{padding:0.5rem 1.5rem;font-size:1rem;line-height:1.5;}
    
            /*css3 animation*/
            @keyframes wave{0%{transform:translate(-90px,0);}100%{transform:translate(85px,0);}}

            /* wave */
            .animation-wave{position:absolute;right:0;bottom:0;left:0;display:block;width:100%;height:3em;z-index:-1000;}
            .animation-wave .parallax>use{animation:wave 12s linear infinite;}
            .animation-wave .parallax>use:nth-child(1){animation-delay:-2s;}
            .animation-wave .parallax>use:nth-child(2){animation-delay:-2s;animation-duration:5s;}
            .animation-wave .parallax>use:nth-child(3){animation-delay:-4s;animation-duration:3s;}

        </style>
    </head>
    <body class="d-flex flex-column bg-primary">
        
        <header class="header">
            <nav  class="navbar navbar-expand-lg navbar-dark bg-transparent">
                <a class="navbar-brand" href="#">
                    <img src="{{ asset('installer/logo.png') }}" width="30" height="30" class="d-inline-block align-top mr-1" alt="">
                    {{config('app.name')}} Installer
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        
        <section class="main d-flex" style="flex:1">
                
            <div class="jumbotron bg-transparent full-width align-self-center text-center">           
                
                <h1>Laravel CMS</h1>
                <p>基于Laravel5和bootstrap4，模块化开发，打造更简洁、更易用的内容管理系统</p>
                
                <div class="p-3">
                    <a href="http://www.zotop.com" class="btn btn-outline text-white" target="_blank">
                        <i class="fa fa-globe fa-fw"></i> Homepage
                    </a>
                    &nbsp;
                    <a href="javascript:;" class="btn btn-outline text-white" target="_blank">
                        <i class="fa fa-circle-o fa-fw"></i> 1.2.0728
                    </a>
                    &nbsp;
                    <a href="javascript:;" class="btn btn-outline text-white" target="_blank">
                        <i class="fa fa-github fa-fw"></i> Github
                    </a>
                    &nbsp;
                    <a href="javascript:;" class="btn btn-outline text-white" target="_blank">
                        <i class="fa fa-book fa-fw"></i> Help
                    </a>
                </div>
          
            </div>

        </section>

        <footer class="footer d-flex">

            <a href="javascript:;" class="btn btn-outline text-white prev d-inline-block mr-auto">
                <i class="fa fa-book fa-fw"></i> 上一步
            </a>

            <a href="javascript:;" class="btn btn-lg btn-success">
                <i class="fa fa-book fa-fw"></i> 下一步
            </a>            
        </footer>

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
