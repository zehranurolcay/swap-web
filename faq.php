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
    <title>Sıkça Sorulan Sorular</title>
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
        			<h1 class="page-title">Sıkça Sorulan Sorular</h1>
        		</div><!-- End .container -->
        	</div><!-- End .page-header -->
            <nav aria-label="breadcrumb" class="breadcrumb-nav">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Anasayfa</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Sıkça Sorulan Sorular</li>
                    </ol>
                </div><!-- End .container -->
            </nav><!-- End .breadcrumb-nav -->

            <div class="page-content">
                <div class="container">
                	<h2 class="title text-center mb-3">Genel Sorular</h2>
        			<div class="accordion accordion-rounded" id="accordion-1">
					    <div class="card card-box card-sm bg-light">
					        <div class="card-header" id="heading-1">
					            <h2 class="card-title">
					                <a  class="collapsed" role="button" data-toggle="collapse" href="#collapse-1" aria-expanded="true" aria-controls="collapse-1">
                                        Takas sitesi nasıl çalışır?
					                </a>
					            </h2>
					        </div><!-- End .card-header -->
					        <div id="collapse-1" class="collapse" aria-labelledby="heading-1" data-parent="#accordion-1">
					            <div class="card-body">
                                Takas sitemiz, kullanıcıların sahip oldukları eşyaları başkalarıyla değiştirmelerine olanak tanır. Ürünlerinizi ekleyerek teklif alabilir veya diğer kullanıcıların ilanlarına teklif verebilirsiniz.					            </div><!-- End .card-body -->
					        </div><!-- End .collapse -->
					    </div><!-- End .card -->

					    <div class="card card-box card-sm bg-light">
					        <div class="card-header" id="heading-2">
					            <h2 class="card-title">
					                <a class="collapsed" role="button" data-toggle="collapse" href="#collapse-2" aria-expanded="false" aria-controls="collapse-2">
                                        Üye olmadan takas yapabilir miyim?
					                </a>
					            </h2>
					        </div><!-- End .card-header -->
					        <div id="collapse-2" class="collapse" aria-labelledby="heading-2" data-parent="#accordion-1">
					            <div class="card-body">
                                    Hayır, güvenliği sağlamak ve dolandırıcılığı önlemek için üye olmanız gerekmektedir.					            
                                </div><!-- End .card-body -->
					        </div><!-- End .collapse -->
					    </div><!-- End .card -->

					    <div class="card card-box card-sm bg-light">
					        <div class="card-header" id="heading-3">
					            <h2 class="card-title">
					                <a class="collapsed" role="button" data-toggle="collapse" href="#collapse-3" aria-expanded="false" aria-controls="collapse-3">
                                    Hangi ürünler takas edilebilir?
					                </a>
					            </h2>
					        </div><!-- End .card-header -->
					        <div id="collapse-3" class="collapse" aria-labelledby="heading-3" data-parent="#accordion-1">
					            <div class="card-body">
                                Elektronik, kıyafet, kitap, koleksiyon ürünleri, hobi malzemeleri ve daha fazlası takas edilebilir. Ancak, yasaklı ürünler (ilaç, silah, alkol vb.) platformumuzda takas edilemez.					            </div><!-- End .card-body -->
					        </div><!-- End .collapse -->
					    </div><!-- End .card -->

					    <div class="card card-box card-sm bg-light">
					        <div class="card-header" id="heading-4">
					            <h2 class="card-title">
					                <a class="collapsed" role="button" data-toggle="collapse" href="#collapse-4" aria-expanded="false" aria-controls="collapse-4">
                                        Takas için bir ürün eklemek nasıl yapılır?
					                </a>
					            </h2>
					        </div><!-- End .card-header -->
					        <div id="collapse-4" class="collapse" aria-labelledby="heading-4" data-parent="#accordion-1">
					            <div class="card-body">
                                Hesabınıza giriş yaparak "Ürün Ekle" butonuna tıklayın, ürününüzle ilgili detayları ve fotoğrafları ekleyin, ardından ilanınızı yayınlayın.					            </div><!-- End .card-body -->
					        </div><!-- End .collapse -->
					    </div><!-- End .card -->
					</div><!-- End .accordion -->

                	<h2 class="title text-center mb-3">Güvenlik ve Kargo</h2><!-- End .title -->
        			<div class="accordion accordion-rounded" id="accordion-2">
					    <div class="card card-box card-sm bg-light">
					        <div class="card-header" id="heading2-1">
					            <h2 class="card-title">
					                <a class="collapsed" role="button" data-toggle="collapse" href="#collapse2-1" aria-expanded="false" aria-controls="collapse2-1">
                                    Takas işlemleri güvenli mi?
					                </a>
					            </h2>
					        </div><!-- End .card-header -->
					        <div id="collapse2-1" class="collapse" aria-labelledby="heading2-1" data-parent="#accordion-2">
					            <div class="card-body">
                                Platformumuz, kullanıcı puanları ve yorumlar gibi özellikler ile güvenliği artırmaya çalışır. Ancak, takas işlemleri kullanıcıların kendi sorumluluğundadır.					            </div><!-- End .card-body -->
					        </div><!-- End .collapse -->
					    </div><!-- End .card -->

					    <div class="card card-box card-sm bg-light">
					        <div class="card-header" id="heading2-2">
					            <h2 class="card-title">
					                <a class="collapsed" role="button" data-toggle="collapse" href="#collapse2-2" aria-expanded="false" aria-controls="collapse2-2">
                                     Ürünü nasıl teslim edeceğim?					                
                                    </a>
					            </h2>
					        </div><!-- End .card-header -->
					        <div id="collapse2-2" class="collapse" aria-labelledby="heading2-2" data-parent="#accordion-2">
					            <div class="card-body">
                                Ürünü elden teslim edebilir veya anlaşmalı bir kargo firması ile gönderebilirsiniz. Kullanıcılar arasında teslimat şartları anlaşmaya bağlıdır.					            </div><!-- End .card-body -->
					        </div><!-- End .collapse -->
					    </div><!-- End .card -->

					    <div class="card card-box card-sm bg-light">
					        <div class="card-header" id="heading2-3">
					            <h2 class="card-title">
					                <a class="collapsed" role="button" data-toggle="collapse" href="#collapse2-3" aria-expanded="false" aria-controls="collapse2-3">
                                        Dolandırıcılığa karşı nasıl önlem alabilirim?					                
                                    </a>
					            </h2>
					        </div><!-- End .card-header -->
					        <div id="collapse2-3" class="collapse" aria-labelledby="heading2-3" data-parent="#accordion-2">
					            <div class="card-body">
                                    Güvenilir ve yüksek puanlı kullanıcılarla takas yapın.
                                    Ürünü görmeden veya emin olmadan takas yapmayın.
                                    Ödemeyi veya teslimatı belgeleyin.					            
                                </div><!-- End .card-body -->
					        </div><!-- End .collapse -->
					    </div><!-- End .card -->
					</div><!-- End .accordion -->

                	<h2 class="title text-center mb-3">Hesap ve Kullanım</h2>
                	<div class="accordion accordion-rounded" id="accordion-3">
					    <div class="card card-box card-sm bg-light">
					        <div class="card-header" id="heading3-1">
					            <h2 class="card-title">
					                <a class="collapsed" role="button" data-toggle="collapse" href="#collapse3-1" aria-expanded="false" aria-controls="collapse3-1">
                                        Hesabımı nasıl silebilirim?
					                </a>
					            </h2>
					        </div><!-- End .card-header -->
					        <div id="collapse3-1" class="collapse" aria-labelledby="heading3-1" data-parent="#accordion-3">
					            <div class="card-body">
                                    Ayarlar bölümünden "Hesabımı Sil" seçeneğini kullanarak hesabınızı kapatabilirsiniz.
                                </div><!-- End .card-body -->
					        </div><!-- End .collapse -->
					    </div><!-- End .card -->

					    <div class="card card-box card-sm bg-light">
					        <div class="card-header" id="heading3-2">
					            <h2 class="card-title">
					                <a class="collapsed" role="button" data-toggle="collapse" href="#collapse3-2" aria-expanded="false" aria-controls="collapse3-2">
                                        Unuttuğum şifreyi nasıl sıfırlarım?
					                </a>
					            </h2>
					        </div><!-- End .card-header -->
					        <div id="collapse3-2" class="collapse" aria-labelledby="heading3-2" data-parent="#accordion-3">
					            <div class="card-body">
                                    Giriş sayfasındaki "Şifremi Unuttum" bağlantısına tıklayarak yeni şifre oluşturabilirsiniz.
                                </div><!-- End .card-body -->
					        </div><!-- End .collapse -->
					    </div><!-- End .card -->

					    <div class="card card-box card-sm bg-light">
					        <div class="card-header" id="heading3-3">
					            <h2 class="card-title">
					                <a class="collapsed" role="button" data-toggle="collapse" href="#collapse3-3" aria-expanded="false" aria-controls="collapse3-3">
                                        Bir kullanıcıyı nasıl şikayet edebilirim?
                                	</a>
					            </h2>
					        </div><!-- End .card-header -->
					        <div id="collapse3-3" class="collapse" aria-labelledby="heading3-3" data-parent="#accordion-3">
					            <div class="card-body">
                                    Kötü niyetli veya kurallara uymayan kullanıcıları profillerinde bulunan "Şikayet Et" butonu ile bildirebilirsiniz.
                                </div><!-- End .card-body -->
					        </div><!-- End .collapse -->
					    </div><!-- End .card -->

					</div><!-- End .accordion -->
                </div><!-- End .container -->
            </div><!-- End .page-content -->

            <div class="cta cta-display bg-image pt-4 pb-4" style="background-image: url(assets/images/backgrounds/cta/bg-7.jpg);">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-10 col-lg-9 col-xl-7">
                            <div class="row no-gutters flex-column flex-sm-row align-items-sm-center">
                                <div class="col">
                                    <h3 class="cta-title text-white">Daha Fazla Sorularınız Varsa</h3><!-- End .cta-title -->
                                </div><!-- End .col -->

                                <div class="col-auto">
                                    <a href="contact.html" class="btn btn-outline-white"><span>BİZE ULAŞIN</span><i class="icon-long-arrow-right"></i></a>
                                </div><!-- End .col-auto -->
                            </div><!-- End .row no-gutters -->
                        </div><!-- End .col-md-10 col-lg-9 -->
                    </div><!-- End .row -->
                </div><!-- End .container -->
            </div><!-- End .cta -->

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