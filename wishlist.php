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
        
        if ($responseData['success'] == true) 
        {
            //// ÜRÜNLER ///
            $baseUrl = "http://localhost/api/all-items";
            $params = [
                'user_id' => $_SESSION["user"]["user_id"]
            ];
            
            $url = $baseUrl . '?' . http_build_query($params);
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            
            $responseData = json_decode($response, true); 
            $items = $responseData['items'];

            //// FAVORİLERİM ///
            $baseUrl = "http://localhost/api/wishlist-list";
            $params = [
                'user_id' => $_SESSION["user"]["user_id"]
            ];
            
            $url = $baseUrl . '?' . http_build_query($params);
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            
            $responseData = json_decode($response, true); 
            $wishlist = $responseData['wishlist'];


            $jwt_security = true;
        } 
        else
        {
            $jwt_security = false;
            header("Location: /404");
            exit;
        }
    }
    else
    {
        $jwt_security = false;
        header("Location: /404");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">


<!-- molla/wishlist.html  22 Nov 2019 09:55:05 GMT -->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Favorilerim</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


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
        .product-image {
            width: 50%;    /* Kapsayıcısının genişliğinin %50'si kadar genişlik */
            height: 50%;   /* Kapsayıcısının yüksekliğinin %50'si kadar yükseklik */
            object-fit: cover; /* Resmin orantılı olarak kırpılmasını sağlar */
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
        			<h1 class="page-title">Favorilerim</h1>
        		</div><!-- End .container -->
        	</div><!-- End .page-header -->
            <nav aria-label="breadcrumb" class="breadcrumb-nav">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Anasayfa</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Favorilerim</li>
                    </ol>
                </div><!-- End .container -->
            </nav><!-- End .breadcrumb-nav -->

            <div class="page-content">
            	<div class="container">
                <?php 
                        echo '
                        <h3 class="card-title">Ürünlerim</h3>
                        <table class="table table-wishlist table-mobile">
                            <thead>
                                <tr>
                                    <th>Ürün</th>
                                    <th>Kategori</th>
                                    <th>Durum</th>
                                    <th>Şehir</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                ';

                            foreach ($wishlist as $list) 
                            {
                                foreach ($items as $item) 
                                {
                                    if($list["item_id"] == $item["item_id"])
                                    {
                                        echo '<tr>
                                            <td class="product-col">
                                                <div class="product">
                                                    <figure class="product-media">
                                                        <a href="/product-details?pid='.$item["item_id"].'">
                                                            <img src="/img/'.$item["photo"].'" alt="Product image" class="product-image" style="width: 50px;">
                                                        </a>
                                                    </figure>

                                                    <h3 class="product-title">
                                                        <a href="/product-details?pid='.$item["item_id"].'">'.$item["title"].'</a>
                                                    </h3>
                                                </div>
                                            </td>
                                            <td class="price-col">'.$item["category_name"].'</td>
                                            <td class="stock-col">'.$item["status"].'</td>
                                            <td class="stock-col">'.$item["location"].'</td>
                                            <td class="action-col">
                                                <span class="icon-box-icon">
                                                    <a class="dropdown-item delete-btn" href="/db/wishlist?item_id='.$item['item_id'].'&user_id='.$_SESSION["user"]["user_id"].'" onclick="return confirm(\'Bu öğeyi favorilerinden kaldırmak istediğinizden emin misiniz?\');">
                                                        <i class="fa fa-times"></i>
                                                    </a>
                                                </span>
                                            </td>
                                        </tr>
                                        ';
                                    }
                                }
                            }
                            

                        echo '  
                            </tbody>
                        </table>
                        ';
                    ?>
            	</div>
            </div>
        </main>

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
    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>
</body>


<!-- molla/wishlist.html  22 Nov 2019 09:55:06 GMT -->
</html>