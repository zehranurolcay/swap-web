<?php
    ini_set('display_errors', 0);
    error_reporting(0);

    session_start();
    if(isset($_SESSION["jwt"]))
    {
        $baseUrl = "http://localhost/api/jwt";
        $params = [
            'jwt' => $_SESSION["jwt"]
        ];
        
        $url = $baseUrl . '?' . http_build_query($params);
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        
        $responseData = json_decode($response, true); 
        
        if ($responseData['success'] === true) 
        {
            $jwt_security = true;
        } 
        else 
        {
            $jwt_security = false;
        }
    }
    else
    {
        $jwt_security = false;
    }
  
?>
<!DOCTYPE html>
<html lang="en">


<!-- molla/about-2.html  22 Nov 2019 10:03:54 GMT -->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Hakkımızda</title>
    <meta name="keywords" content="HTML5 Template">
    <meta name="description" content="Molla - Bootstrap eCommerce Template">
    <meta name="author" content="p-themes">
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/icons/favicon-16x16.png">
    <link rel="manifest" href="assets/images/icons/site.html">
    <link rel="mask-icon" href="assets/images/icons/safari-pinned-tab.svg" color="#666666">
    <link rel="shortcut icon" href="assets/images/icons/favicon.ico">
    <meta name="apple-mobile-web-app-title" content="Molla">
    <meta name="application-name" content="Molla">
    <meta name="msapplication-TileColor" content="#cc9966">
    <meta name="msapplication-config" content="assets/images/icons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <!-- Plugins CSS File -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Main CSS File -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .dropdown-cart-action {
            margin: 0 20px;
        }

        .dropdown-cart-action a {
            display: block; 
            text-align: center; 
            padding: 8px 16px; 
        }

        .dropdown-menu {
            padding: 15px; 
        }
        .name-section span {
            font-size: 18px;
            display: block;
            text-align: center; 
            margin-bottom: 5px; 
        }
        .product-image-container {
            width: 280px;  /* İstediğiniz genişlik */
            height: 280px; /* İstediğiniz yükseklik */
        }

        .product-image {
            width: 100%;
            height: 100%;
        }
        .intro-slide {
            min-height: 100%; 
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
        }
        .btn-product-icon {
            display: inline-flex;
            align-items: center;
            padding: 10px;
            text-decoration: none;
            font-size: 14px;
            color: #333;
            border: none;
            transition: color 0.3s;
        }

        .btn-product-icon.favorited {
            color: red; /* Favoriye eklenince kırmızı olacak */
        }

    </style>
</head>

<body>
    <div class="page-wrapper">
        <header class="header">

            <div class="header-middle sticky-header">
                <div class="container">
                    <div class="header-left">
                        <button class="mobile-menu-toggler">
                            <span class="sr-only">Toggle mobile menu</span>
                            <i class="icon-bars"></i>
                        </button>
                        <a href="/" class="logo">
                            <img src="assets/images/logo.png" alt="Molla Logo" width="105" height="25">
                        </a>

                        <nav class="main-nav">
                            <ul class="menu sf-arrows">
                                <li class="megamenu-container active">
                                    <a href="/" class="sf-with-ul">Anasayfa</a>
                                </li>
                                <li>
                                    <a href="/product-category?cn=Elektronik" class="sf-with-ul">Kategoriler</a>

                                    <div class="megamenu megamenu-sm">
                                        <div class="row no-gutters">
                                            <div class="col-md-6">
                                                <div class="menu-col">
                                                    <div class="menu-title">Kategori Detayları</div><!-- End .menu-title -->
                                                    <ul>
                                                        <li><a href="/product-category?cn=Elektronik">Elektronik</a></li>
                                                        <li><a href="/product-category?cn=Ev Ürünleri">Ev Ürünleri</a></li>
                                                        <li><a href="/product-category?cn=Kitap ve Dergi">Kitap ve Dergi</a></li>
                                                        <li><a href="/product-category?cn=Oyuncaklar">Oyuncaklar</a></li>
                                                        <li><a href="/product-category?cn=Kıyafet">Kıyafet</a></li>
                                                        <li><a href="/product-category?cn=Diğer">Diğer</a></li>
                                                    </ul>
                                                </div><!-- End .menu-col -->
                                            </div><!-- End .col-md-6 -->
                                        </div><!-- End .row -->
                                    </div><!-- End .megamenu megamenu-sm -->
                                </li>
                                <li>
                                    <a href="/about" class="sf-with-ul">Hakkında</a>
                                </li>
                                <li>
                                    <a href="/faq" class="sf-with-ul">SSS</a>
                                </li>
                                <li>
                                    <a href="/contact" class="sf-with-ul">İletişim</a>
                                </li>
                            </ul><!-- End .menu -->
                        </nav><!-- End .main-nav -->
                    </div><!-- End .header-left -->

                    <div class="header-right">

                        <div class="header-search">
                            <a href="#" class="search-toggle" role="button"><i class="icon-search"></i></a>
                            <form action="#" method="get">
                                <div class="header-search-wrapper">
                                    <label for="q" class="sr-only">Arama</label>
                                    <input type="search" class="form-control" name="q" id="q" placeholder="Search in..." required>
                                </div><!-- End .header-search-wrapper -->
                            </form>
                        </div><!-- End .header-search -->
                        

                        <?php
                            if($jwt_security == true)
                            {
                                echo '
                                    <a href="/wishlist" class="wishlist-link">
                                        <i class="icon-heart-o"></i>
                                    </a>
                                    <div class="dropdown wishlist-link">
                                        <a href="user" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                                            <i class="icon-user"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">

                                        <div class="dropdown-cart-action name-section">
                                            <span> '.ucwords(strtolower($_SESSION["user"]["name"])).' </span>
                                        </div>
                                            <div class="dropdown-cart-action">
                                                <a href="/my-account" class="btn btn-primary">Hesabım</a>
                                            </div>

                                            <div class="dropdown-cart-action">
                                                <a href="/db/exit-login" class="btn btn-outline-primary-2"><span>Çıkış Yap</span><i class="icon-long-arrow-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                ';
                            }
                            else
                            {
                                echo '
                                    <a href="/login" style="margin-left: 20px;" >Giriş Yap / Kayıt Ol</a>
                                ';
                            }
                            
                        ?>

                     
                    </div><!-- End .header-right -->
                </div><!-- End .container -->
            </div><!-- End .header-middle -->
        </header><!-- End .header -->

        <main class="main">
            <div class="page-header text-center" style="background-image: url('assets/images/page-header-bg.jpg')">
        		<div class="container">
        			<h1 class="page-title">Hakkımızda</h1>
        		</div><!-- End .container -->
        	</div><!-- End .page-header -->
            <nav aria-label="breadcrumb" class="breadcrumb-nav">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Anasayfa</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Hakkımızda</li>
                    </ol>
                </div><!-- End .container -->
            </nav><!-- End .breadcrumb-nav -->

            <div class="page-content pb-3">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-10 offset-lg-1">
                            <div class="about-text text-center mt-3">
                                <h2 class="title text-center mb-2">BİZ KİMİZ</h2><!-- End .title text-center mb-2 -->
                                <p>Sed pretium, ligula sollicitudin laoreet viverra, tortor libero sodales leo, eget blandit nunc tortor eu nibh. Suspendisse potenti. Sed egestas, ante et vulputate volutpat, uctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, commodo quis, gravida id, est. Sed lectus. Praesent elementum hendrerit tortor. Sed semper lorem at felis. </p>
                                <img src="assets/images/about/about-2/signature.png" alt="signature" class="mx-auto mb-5">

                                <img src="assets/images/about/about-2/img-1.jpg" alt="image" class="mx-auto mb-6">
                            </div><!-- End .about-text -->
                        </div><!-- End .col-lg-10 offset-1 -->
                    </div><!-- End .row -->
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-sm-6">
                            <div class="icon-box icon-box-sm text-center">
                                <span class="icon-box-icon">
                                    <i class="icon-puzzle-piece"></i>
                                </span>
                                <div class="icon-box-content">
                                    <h3 class="icon-box-title">Tasarım Kalitesi</h3><!-- End .icon-box-title -->
                                    <p>Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero <br>eu augue.</p>
                                </div><!-- End .icon-box-content -->
                            </div><!-- End .icon-box -->
                        </div><!-- End .col-lg-4 col-sm-6 -->

                        <div class="col-lg-4 col-sm-6">
                            <div class="icon-box icon-box-sm text-center">
                                <span class="icon-box-icon">
                                    <i class="icon-life-ring"></i>
                                </span>
                                <div class="icon-box-content">
                                    <h3 class="icon-box-title">Profesyonel Destek</h3><!-- End .icon-box-title -->
                                    <p>Praesent dapibus, neque id cursus faucibus, <br>tortor neque egestas augue, eu vulputate <br>magna eros eu erat. </p>
                                </div><!-- End .icon-box-content -->
                            </div><!-- End .icon-box -->
                        </div><!-- End .col-lg-4 col-sm-6 -->

                        <div class="col-lg-4 col-sm-6">
                            <div class="icon-box icon-box-sm text-center">
                                <span class="icon-box-icon">
                                    <i class="icon-heart-o"></i>
                                </span>
                                <div class="icon-box-content">
                                    <h3 class="icon-box-title">Güvenli Alışveriş</h3><!-- End .icon-box-title -->
                                    <p>Pellentesque a diam sit amet mi ullamcorper <br>vehicula. Nullam quis massa sit amet <br>nibh viverra malesuada.</p> 
                                </div><!-- End .icon-box-content -->
                            </div><!-- End .icon-box -->
                        </div><!-- End .col-lg-4 col-sm-6 -->
                    </div><!-- End .row -->
                </div><!-- End .container -->

                
            </div><!-- End .page-content -->
        </main><!-- End .main -->

        <footer class="footer">
        	<div class="footer-middle">
	            <div class="container">
	            	<div class="row">

	            		<div class="col-sm-6 col-lg-3">
	            			<div class="widget">
	            				<h4 class="widget-title">Faydalı Bağlantılar</h4><!-- End .widget-title -->

	            				<ul class="widget-list">
	            					<li><a href="/about">Hakkımızda</a></li>
	            					<li><a href="/faq">Sıkça Sorulan Sorular</a></li>
	            					<li><a href="/contact">İletişim</a></li>
	            				</ul><!-- End .widget-list -->
	            			</div><!-- End .widget -->
	            		</div><!-- End .col-sm-6 col-lg-3 -->


	            	</div><!-- End .row -->
	            </div><!-- End .container -->
	        </div><!-- End .footer-middle -->

	        <div class="footer-bottom">
	        	<div class="container">
	        		<p class="footer-copyright">Copyright © 2025. All Rights Reserved.</p><!-- End .footer-copyright -->
	        	</div><!-- End .container -->
	        </div><!-- End .footer-bottom -->
        </footer><!-- End .footer -->
    </div><!-- End .page-wrapper -->
    <button id="scroll-top" title="Back to Top"><i class="icon-arrow-up"></i></button>


    <!-- Plugins JS File -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.hoverIntent.min.js"></script>
    <script src="assets/js/jquery.waypoints.min.js"></script>
    <script src="assets/js/superfish.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/jquery.countTo.js"></script>
    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>
</body>


<!-- molla/about-2.html  22 Nov 2019 10:04:01 GMT -->
</html>