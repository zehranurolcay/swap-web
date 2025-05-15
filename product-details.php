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

     //// ÜRÜN DETAYI ///
     $baseUrl = "http://localhost/api/product-details?item_id=".$_GET["pid"];
     
     $url = $baseUrl;
     
     $ch = curl_init($url);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     $response = curl_exec($ch);
     curl_close($ch);
     
     $responseData = json_decode($response, true); 
     $product_details = $responseData['items'];

     //// ÜRÜN FOTOĞRAFLARI ///
     $baseUrl = "http://localhost/api/item-img";
     
     $url = $baseUrl;
     
     $ch = curl_init($url);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     $response = curl_exec($ch);
     curl_close($ch);
     
     $responseData = json_decode($response, true); 
     $product_img = $responseData['item_images'];

     //// ÜRÜNLER ///
     $baseUrl = "http://localhost/api/all-items";
     
     $url = $baseUrl;
     
     $ch = curl_init($url);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     $response = curl_exec($ch);
     curl_close($ch);
     
     $responseData = json_decode($response, true); 
     $temp_items = $responseData['items'];

     //// SELLER NAME ///
     $baseUrl = "http://localhost/api/seller-name?user_id=".$product_details[0]["user_id"];
     
     $url = $baseUrl;
     
     $ch = curl_init($url);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     $response = curl_exec($ch);
     curl_close($ch);
     
     $responseData = json_decode($response, true); 
     $user = $responseData['user'];

      //// OFFER CHECK ///
      $baseUrl = "http://localhost/api/offer-checked?user_id=".$_SESSION["user"]["user_id"]."&item_id=".$_GET["pid"];
     
      $url = $baseUrl;
      
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($ch);
      curl_close($ch);
      
      $responseData = json_decode($response, true); 
      $offer_checked = $responseData['success'];

    //// TAKAS LİSTESİ ///
    $baseUrl = "http://localhost/api/offers";
        
    $url = $baseUrl;
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $responseData = json_decode($response, true); 
    $offers = isset($responseData['offers']) && !empty($responseData['offers']) ? $responseData['offers'] : [];


    // TAKAS TEKLİF KABUL EDİLEN ÜRÜNLERİN FİLTRELENMESİ //
    $acceptedItemIds = array_merge(
        array_column(array_filter($offers, fn($offer) => $offer['status'] === "Kabul Edildi"), 'item_id'),
        array_column(array_filter($offers, fn($offer) => $offer['status'] === "Kabul Edildi"), 'offered_item_id')
    );
    
    $temp_items = array_filter($temp_items, fn($item) => !in_array($item['item_id'], $acceptedItemIds));
    
    $temp_items = array_values($temp_items);
        
    $items = $temp_items;

?>
<!DOCTYPE html>
<html lang="en">


<!-- molla/product-extended.html  22 Nov 2019 10:03:20 GMT -->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Molla - Bootstrap eCommerce Template</title>
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
    <link rel="stylesheet" href="assets/css/plugins/owl-carousel/owl.carousel.css">
    <link rel="stylesheet" href="assets/css/plugins/magnific-popup/magnific-popup.css">
    <!-- Main CSS File -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/plugins/nouislider/nouislider.css">
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
        .product-main-image img {
            width: 500px;   /* Sabit genişlik */
            height: 500px;  /* Sabit yükseklik */
            object-fit: contain; /* Resmi kırpmadan tamamen göster */
            display: block;
            margin: auto;
            background-color: white; /* Arka plan rengi, isteğe bağlı */
        }

        .product-image-gallery img {
            width: 100px;  /* Küçük resimler için sabit genişlik */
            height: 100px; /* Küçük resimler için sabit yükseklik */
            object-fit: contain; /* Resmi kırpmadan tamamen gösterir */
            display: block;
            margin: auto;
            background-color: white; /* Boşlukların daha şık görünmesi için */
            padding: 5px; /* Görselin kutuya tam oturması için içeride boşluk bırakır */
            border-radius: 5px; /* Hafif yuvarlatma */
        }

        .popup-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        /* Popup içeriği */
        .popup-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            text-align: center;
            position: relative;
        }

        /* Kapatma butonu */
        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 20px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }


    </style>
        <style>
        .header-search {
            position: relative;
            max-width: 400px;
            margin: 0 auto;
        }

        #search-box {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 25px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        #search-box:focus {
            outline: none;
            border-color:rgb(255, 132, 0);
            box-shadow: 0 0 8px rgba(0,123,255,0.2);
        }

        #search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 10px 10px;
            z-index: 1000;
            width: 100%;
            max-height: 300px;
            overflow-y: auto;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        #search-results a {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #f1f1f1;
            text-decoration: none;
            color: #333;
            transition: background 0.2s;
        }

        #search-results a:hover {
            background: #f8f9fa;
        }

        #search-results img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 10px;
        }

        #search-results div {
            font-size: 15px;
            font-weight: 500;
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
                            <input type="search" class="form-control" id="search-box" placeholder="Ürün ara..." autocomplete="off">
                            <div id="search-results"></div>
                        </div>

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
            <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
                <div class="container d-flex align-items-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Anasayfa</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ürün Detayları</li>
                    </ol>
                </div><!-- End .container -->
            </nav><!-- End .breadcrumb-nav -->

            <div class="page-content">
                <div class="container">
                    <div class="product-details-top mb-2">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="product-gallery">
                                    <?php 
                                        foreach ($product_details as $item)
                                        {
                                        echo'
                                            <figure class="product-main-image">
                                                <img src="/img/'.$item["photo"].'" alt="product image">

                                            </figure><!-- End .product-main-image -->
                                        ';
                                        }
                                        echo'
                                        <div id="product-zoom-gallery" class="product-image-gallery">

                                        <a class="product-gallery-item" href="#" data-image="/img/'.$item["photo"].'">
                                            <img src="/img/'.$item["photo"].'" alt="product side">
                                        </a>';

                                        foreach ($product_img as $item)
                                        {
                                            if($item["item_id"] == $_GET["pid"])
                                            {
                                                echo'
                                                 <a class="product-gallery-item" href="#" data-image="/img/product_img/'.$item["image_url"].'">
                                                    <img src="/img/product_img/'.$item["image_url"].'" alt="product side">
                                                </a>
                                            ';
                                            }
                                        }

                                    ?>
                                    

                                    
                                       
                                     </div><!-- End .product-image-gallery -->


                                </div><!-- End .product-gallery -->

                                
                            </div><!-- End .col-md-6 -->

                            <div class="col-md-6">
                                <?php 
                                    foreach ($product_details as $item)
                                    {
                                        $category_type= $item['category_name'];
                                        echo '
                                            <div class="product-details">
                                                <h1 class="product-title">'.$item['title'].'</h1>
                                                <div class="product-price">
                                                    '.$item['category_name'].'
                                                </div><!-- End .product-price -->

                                                <div class="product-content">
                                                    <p>'.$item['description'].'</p>
                                                </div><!-- End .product-content -->


                                                <div class="details-filter-row details-row-size">
                                                    <label>Satıcı:</label>

                                                    <div class="product-nav product-nav-dots">
                                                        '.$user[0]["name"].'
                                                    </div><!-- End .product-nav -->
                                                </div>

                                                <div class="details-filter-row details-row-size">
                                                    <label>Ürün Durumu:</label>

                                                    <div class="product-nav product-nav-dots">
                                                        '.$item['status'].'
                                                    </div><!-- End .product-nav -->
                                                </div>

                                                <div class="details-filter-row details-row-size">
                                                    <label for="size">Konum: </label>

                                                    '.$item['location'].'

                                                </div>

                                                <div class="details-filter-row details-row-size">
                                                    <label for="qty">YÜkleme Tarihi:</label>
                                                    <div class="product-details-quantity">
                                                ';
                                                    function kacGunOnce($tarih) {
                                                        $bugun = new DateTime(); 
                                                        $verilenTarih = new DateTime($tarih); 
                                                        $fark = $bugun->diff($verilenTarih); 
                                                        return $fark->days;
                                                    }

                                                    $eskiTarih = $item['created_at'];
                                                    echo kacGunOnce($eskiTarih) . " gün önce oluşturuldu.";

                                                    echo '
                                                    </div>
                                                </div>
                                                    ';

                                                    if(isset($_SESSION["user"]["user_id"]))
                                                    {
                                                        if($offer_checked == true)
                                                        {
                                                            echo'
                                                                <div class="product-details-action">
                                                                    <a href="#" class="btn-product btn-cart" onclick="showErrorMessage_offer(event)"><span>Takas Teklifi Ver</span></a>
                                                                </div>
                                                                ';
                                                        }
                                                        else
                                                        {
                                                            if($item["user_id"] == $_SESSION["user"]["user_id"])
                                                            {
                                                                echo'
                                                                <div class="product-details-action">
                                                                    <a href="#" class="btn-product btn-cart" onclick="showErrorMessage(event)"><span>Takas Teklifi Ver</span></a>
                                                                </div>
                                                                ';
                                                            }
                                                            else
                                                            {
                                                                echo'
                                                                <div class="product-details-action">
                                                                    <a href="#" class="btn-product btn-cart" onclick="openPopup()"><span>Takas Teklifi Ver</span></a>
                                                                </div>
                                                                ';
                                                            }
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo'
                                                            <div class="product-details-action">
                                                                <a href="#" class="btn-product btn-cart" onclick="showErrorMessage_login(event)"><span>Takas Teklifi Ver</span></a>
                                                            </div>
                                                            ';
                                                    }
                                                  
                                                    echo '
                                            </div><!-- End .product-details -->
                                        
                                        ';
                                    }
                                   

                                        
                                ?>
                            </div><!-- End .col-md-6 -->
                        </div><!-- End .row -->
                    </div><!-- End .product-details-top -->
                </div><!-- End .container -->


                <div class="container">
                    <h2 class="title text-center mb-4">Şunları da Beğenebilirsiniz</h2><!-- End .title text-center -->
                    <div class="owl-carousel owl-simple carousel-equal-height carousel-with-shadow" data-toggle="owl" 
                        data-owl-options='{
                            "nav": false, 
                            "dots": true,
                            "margin": 20,
                            "loop": false,
                            "responsive": {
                                "0": {
                                    "items":1
                                },
                                "480": {
                                    "items":2
                                },
                                "768": {
                                    "items":3
                                },
                                "992": {
                                    "items":4
                                },
                                "1200": {
                                    "items":4,
                                    "nav": true,
                                    "dots": false
                                }
                            }
                        }'>


                        <?php 
                            foreach ($items as $item)
                            {
                                if($item['category_name'] == $category_type)
                                {
                                    echo '
                                    <div class="product product-7">
                                        <figure class="product-media">
                                            <a href="/product-details?pid='.$item["item_id"].'">
                                                <div class="product-image-container">
                                                    <img src="/img/'.$item["photo"].'" alt="Product image" class="product-image">
                                                </div>
                                            </a>

                                        <div class="product-action-vertical">
                                                <a href="#" class="btn-product-icon btn-wishlist btn-expandable favoriteLink" 
                                                data-id="'.$item["item_id"].'"
                                                data-user="'.$_SESSION['user']['user_id'].'">
                                                    <span>Favorilere Ekle</span>
                                                </a>
                                            </div>


                                            <div class="product-action">
                                                <a href="/product-details?pid='.$item["item_id"].'" class="btn-product"><span>Ürün Detayları</span></a>
                                            </div>
                                        </figure>

                                        <div class="product-body" style="text-align: center;">
                                            <div class="product-cat">
                                                <a href="#">'.$item["category_name"].'</a>
                                            </div>
                                            <h3 class="product-title" style="font-weight: bold;"><a href="/product-details?pid='.$item["item_id"].'">'.$item["title"].'</a></h3>
                                        </div>
                                    </div>
                                    ';
                                }
                                
                            }
                        ?>
                   

                    </div><!-- End .owl-carousel -->
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
    <script src="assets/js/bootstrap-input-spinner.js"></script>
    <script src="assets/js/jquery.elevateZoom.min.js"></script>
    <script src="assets/js/bootstrap-input-spinner.js"></script>
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>
</body>


<!-- molla/product-extended.html  22 Nov 2019 10:03:27 GMT -->
</html>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const thumbnails = document.querySelectorAll(".product-gallery-item");
    const mainImage = document.querySelector(".product-main-image img");

    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener("click", function (event) {
            event.preventDefault(); 

            const newImage = this.getAttribute("data-image");
            mainImage.src = newImage;
        });
    });
});

document.addEventListener("DOMContentLoaded", function() {
           
           document.querySelectorAll(".favoriteLink").forEach(link => {
               let itemId = link.getAttribute("data-id");
               let userId = link.getAttribute("data-user");
               let span = link.querySelector("span");
               let linkElement = link;

               fetch(`/api/wishlist-status?user_id=${userId}&item_id=${itemId}`)
                   .then(response => response.json())
                   .then(data => {
                       if (data.success) {
                           if (data.action === "added") {
                               linkElement.style.backgroundColor = "orange";
                               span.innerText = "Favorilerden Çıkar";
                               link.classList.add("favorited");
                           } else if (data.action === "removed") {
                               linkElement.style.backgroundColor = "";
                               span.innerText = "Favorilere Ekle";
                               link.classList.remove("favorited");
                           }
                       }
                   })
                   .catch(error => {
                       console.error(error);
                   });

              
               link.addEventListener("click", function(event) {
                   event.preventDefault();

                   fetch(`/api/wishlist?user_id=${userId}&item_id=${itemId}`)
                       .then(response => response.json())
                       .then(data => {
                           if (data.success) {
                               if (data.action === "added") {
                                   linkElement.style.backgroundColor = "orange";
                                   link.classList.add("favorited");
                                   span.innerText = "Favorilerden Çıkar";
                               } else if (data.action === "removed") {
                                   linkElement.style.backgroundColor = "";
                                   link.classList.remove("favorited");
                                   span.innerText = "Favorilere Ekle";
                               }
                           } else {
                               alert(data.message);
                           }
                       })
                       .catch(error => {
                           console.error("Favori işlemi hatası:", error);
                       });
               });
           });
       });
</script>

    <!-- Popup -->
    <div class="popup-overlay" id="popup">
        <div class="popup-content">
            <span class="close-btn" onclick="closePopup()">×</span>
            <h2>Takas Yapacağın Ürünü Seç</h2>
            <table>
                <tr>
                    <th>Ürün</th>
                    <th>Seç</th>
                </tr>
                <?php
                    foreach($items as $item)
                    {
                        if($_SESSION["user"]["user_id"] == $item["user_id"])
                        {
                            echo'
                            <tr>
                                <td>'.$item["title"].'</td>
                                <td><input type="radio" name="product" value="'.$item["item_id"].'"></td>
                            </tr>
                            ';
                        }
                       
                    }
                    
                ?>
            </table>
            <button class="btn" onclick="submitOffer()">Teklif Ver</button>
        </div>
    </div>

    <script>
        // Popup açma fonksiyonu
        function openPopup() {
            document.getElementById("popup").style.display = "flex";
        }

        // Popup kapatma fonksiyonu
        function closePopup() {
            document.getElementById("popup").style.display = "none";
        }

        function submitOffer() {
            let selectedProduct = document.querySelector('input[name="product"]:checked');
            let user_id = <?php echo isset($_SESSION["user"]["user_id"]) ? $_SESSION["user"]["user_id"] : 'null'; ?>;
            let item_id = <?php echo $_GET["pid"]; ?>;

            if (selectedProduct) {
                fetch("api/add-offer?offered_item_id=" + encodeURIComponent(selectedProduct.value) + "&user_id=" + encodeURIComponent(user_id) + "&item_id=" + encodeURIComponent(item_id))
                    .then(response => {
                        if (response.ok) {
                            return response.json(); // Sunucu JSON yanıt döndürüyorsa
                        } else {
                            throw new Error("Teklif gönderilirken hata oluştu.");
                        }
                    })
                    .then(data => {
                        alert("Teklif gönderildi.");
                        closePopup();
                        location.reload();
                    })
                    .catch(error => {
                        console.error("Hata:", error);
                        alert("Teklif gönderilirken hata oluştu.");
                    });
            } else {
                alert("Lütfen bir ürün seçin!");
            }
        }

        function showErrorMessage(event) {
            event.preventDefault(); 
            alert("Kendi ürününe teklif veremezsin!");
        }

        function showErrorMessage_offer(event) {
            event.preventDefault(); 
            alert("Bu ürüne zaten teklif verdin!");
        }

        function showErrorMessage_login(event) {
            event.preventDefault(); 
            alert("Teklif vermek için giriş yapmanız gerekiyor!");
        }

           ///ARAMA KISMI
        $(document).ready(function() {
            $('#search-box').on('input', function() {
                var query = $(this).val();
                if (query.length > 1) {
                    $.ajax({
                        url: '/search-handler',
                        method: 'GET',
                        data: { q: query },
                        success: function(response) {
                            $('#search-results').html(response);
                        }
                    });
                } else {
                    $('#search-results').html('');
                }
            });
        });
    </script>