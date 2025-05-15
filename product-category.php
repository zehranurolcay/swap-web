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

    //// ÜRÜNLER ///
    $baseUrl = "http://localhost/api/all-items";
    
    $url = $baseUrl;
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $responseData = json_decode($response, true); 
    $temp_items = $responseData['items'];

    //// TAKAS LİSTESİ ///
    $baseUrl = "http://localhost/api/offers";

    $ch = curl_init($baseUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $responseData = json_decode($response, true);

    // Eğer offers boşsa veya hiç yoksa işlemi atla
    if (!isset($responseData['offers']) || empty($responseData['offers'])) {
        $items = $temp_items; // Hiçbir filtreleme yapmadan devam et
    } else {
        $offers = $responseData['offers'];

        // TAKAS TEKLİF KABUL EDİLEN ÜRÜNLERİN FİLTRELENMESİ //
        $acceptedItemIds = array_merge(
            array_column(array_filter($offers, fn($offer) => $offer['status'] === "Kabul Edildi"), 'item_id'),
            array_column(array_filter($offers, fn($offer) => $offer['status'] === "Kabul Edildi"), 'offered_item_id')
        );

        $temp_items = array_filter($temp_items, fn($item) => !in_array($item['item_id'], $acceptedItemIds));

        $items = array_values($temp_items);
    }
  
?>
<!DOCTYPE html>
<html lang="en">


<!-- molla/category-4cols.html  22 Nov 2019 10:02:52 GMT -->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Kategoriler</title>
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
    <link rel="stylesheet" href="assets/css/plugins/owl-carousel/owl.carousel.css">
    <link rel="stylesheet" href="assets/css/plugins/magnific-popup/magnific-popup.css">
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
        			<h1 class="page-title"><?php echo ucwords($_GET["cn"]); ?>
                    </h1>
        		</div><!-- End .container -->
        	</div><!-- End .page-header -->
            <nav aria-label="breadcrumb" class="breadcrumb-nav">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Anasayfa</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Kategoriler</li>
                    </ol>
                </div><!-- End .container -->
            </nav><!-- End .breadcrumb-nav -->

            <div class="page-content">
                <div class="container">
                	<div class="row">
                		<div class="col-lg-9">

                            <div class="products mb-3">
                                <div class="row justify-content-center">

                                <?php 
                                    foreach ($items as $item)
                                    {
                                        if($item["category_name"] == $_GET["cn"])
                                        {
                                            echo '
                                            <div class="col-6 col-md-4 col-lg-4 col-xl-3">
                                                <div class="product product-7 text-center">
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
                                                        </div><!-- End .product-action-vertical -->
    
                                                        <div class="product-action">
                                                            <a href="/product-details?pid='.$item["item_id"].'" class="btn-product btn-cart"><span>Ürün Detayları</span></a>
                                                        </div><!-- End .product-action -->
                                                    </figure><!-- End .product-media -->
    
                                                    <div class="product-body">
                                                        <div class="product-cat">
                                                            <a href="#">'.$item["category_name"].'</a>
                                                        </div><!-- End .product-cat -->
                                                        <h3 class="product-title"><a href="/product-details?pid='.$item["item_id"].'">'.$item["title"].'</a></h3><!-- End .product-title -->
    
                                                    </div><!-- End .product-body -->
                                                </div><!-- End .product -->
                                            </div><!-- End .col-sm-6 col-lg-4 col-xl-3 -->
                                            ';
                                        }

                                        if($item["category_name"] == "Elektronik")
                                            $elektronik++;
                                        else if($item["category_name"] == "Ev Ürünleri")
                                            $ev_urunleri++;
                                        else if($item["category_name"] == "Kitap ve Dergi")
                                            $kitap_ve_dergi++;
                                        else if($item["category_name"] == "Oyuncaklar")
                                            $oyuncaklar++;
                                        else if($item["category_name"] == "Kıyafet")
                                            $kıyafet++;
                                        else if($item["category_name"] == "Diğer")
                                            $diğer++;
                                   
                                    }
                                ?>
                                 
                                </div><!-- End .row -->
                            </div><!-- End .products -->


                		</div><!-- End .col-lg-9 -->
                		<aside class="col-lg-3 order-lg-first">
                			<div class="sidebar sidebar-shop">

                				<div class="widget widget-collapsible">
    								<h3 class="widget-title">
									    <a data-toggle="collapse" href="#widget-1" role="button" aria-expanded="true" aria-controls="widget-1">
									        Category
									    </a>
									</h3><!-- End .widget-title -->

									<div class="collapse show" id="widget-1">
                                        <div class="widget-body">
                                            <div class="filter-items filter-items-count">
                                                <div class="filter-item">
                                                    <div class="custom-control custom-checkbox">
                                                        <a href="/product-category?cn=Elektronik" class="custom-control-label">Elektronik</a>
                                                    </div><!-- End .custom-checkbox -->
                                                    <span class="item-count"><?php echo $elektronik = isset($elektronik) ? $elektronik : 0; ?></span>
                                                </div><!-- End .filter-item -->

                                                <div class="filter-item">
                                                    <div class="custom-control custom-checkbox">
                                                        <a href="/product-category?cn=Ev Ürünleri" class="custom-control-label">Ev Ürünleri</a>
                                                    </div><!-- End .custom-checkbox -->
                                                    <span class="item-count"><?php echo $ev_urunleri = isset($ev_urunleri) ? $ev_urunleri : 0; ?></span>
                                                </div><!-- End .filter-item -->

                                                <div class="filter-item">
                                                    <div class="custom-control custom-checkbox">
                                                        <a href="/product-category?cn=Kitap ve Dergi" class="custom-control-label">Kitap ve Dergi</a>
                                                    </div><!-- End .custom-checkbox -->
                                                    <span class="item-count"><?php echo $kitap_ve_dergi = isset($kitap_ve_dergi) ? $kitap_ve_dergi : 0; ?></span>
                                                </div><!-- End .filter-item -->

                                                <div class="filter-item">
                                                    <div class="custom-control custom-checkbox">
                                                        <a href="/product-category?cn=Oyuncaklar" class="custom-control-label">Oyuncaklar</a>
                                                    </div><!-- End .custom-checkbox -->
                                                    <span class="item-count"><?php echo $oyuncaklar = isset($oyuncaklar) ? $oyuncaklar : 0; ?></span>
                                                </div><!-- End .filter-item -->

                                                <div class="filter-item">
                                                    <div class="custom-control custom-checkbox">
                                                        <a href="/product-category?cn=Kıyafet" class="custom-control-label">Kıyafet</a>
                                                    </div><!-- End .custom-checkbox -->
                                                    <span class="item-count"><?php echo $kıyafet = isset($kıyafet) ? $kıyafet : 0; ?></span>
                                                </div><!-- End .filter-item -->

                                                <div class="filter-item">
                                                    <div class="custom-control custom-checkbox">
                                                        <a href="/product-category?cn=Diğer" class="custom-control-label">Diğer</a>
                                                    </div><!-- End .custom-checkbox -->
                                                    <span class="item-count"><?php echo $diğer = isset($diğer) ? $diğer : 0; ?></span>
                                                </div><!-- End .filter-item -->

                                            </div><!-- End .filter-items -->
                                        </div><!-- End .widget-body -->
                                    </div><!-- End .collapse -->


        						</div><!-- End .widget -->

        						
                			</div><!-- End .sidebar sidebar-shop -->
                		</aside><!-- End .col-lg-3 -->
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
    <script src="assets/js/wNumb.js"></script>
    <script src="assets/js/bootstrap-input-spinner.js"></script>
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <script src="assets/js/nouislider.min.js"></script>
    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>
</body>


<!-- molla/category-4cols.html  22 Nov 2019 10:02:55 GMT -->
</html>

<script>
    $(window).on('load', function() {
        var $grid = $('.products-container').isotope({
            itemSelector: '.product-item',
            layoutMode: 'fitRows'
        });

        $('.nav-filter a').on('click', function(e) {
            e.preventDefault(); 
            var filterValue = $(this).attr('data-filter');
            $grid.isotope({ filter: filterValue });
            $('.nav-filter li').removeClass('active');
            $(this).parent().addClass('active');
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