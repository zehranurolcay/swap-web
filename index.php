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


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Anasayfa</title>
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
    <link rel="stylesheet" href="assets/vendor/line-awesome/line-awesome/line-awesome/css/line-awesome.min.css">
    <!-- Plugins CSS File -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/plugins/owl-carousel/owl.carousel.css">
    <link rel="stylesheet" href="assets/css/plugins/magnific-popup/magnific-popup.css">
    <!-- Main CSS File -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/plugins/nouislider/nouislider.css">
    <link rel="stylesheet" href="assets/css/demos/demo-11.css">
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
        <header class="header header-7">
            <div class="header-middle sticky-header">
                <div class="container">
                    <div class="header-left">
                        <button class="mobile-menu-toggler">
                            <span class="sr-only">Toggle mobile menu</span>
                            <i class="icon-bars"></i>
                        </button>
                        
                        <a href="/" class="logo">
                            <img src="assets/images/demos/demo-11/logo.png" alt="Molla Logo" width="82" height="25">
                        </a>
                    </div><!-- End .header-left -->

                    <div class="header-right">

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
            <?php
                // items dizisinden ilk 3 öğeyi alıyoruz
                $sliderItems = array_slice($items, 0, 3);
            ?>

            <div class="intro-slider-container mb-4">
                <div class="intro-slider owl-carousel owl-simple owl-nav-inside" 
                    data-toggle="owl" 
                    data-owl-options='{
                        "nav": false, 
                        "dots": true,
                        "responsive": {
                            "992": {
                                "nav": true,
                                "dots": false
                            }
                        }
                    }'>
                    
                    <?php foreach($sliderItems as $slideItem): ?>
                        <div class="intro-slide" 
                            style="background-image: url('/img/<?= $slideItem["photo"] ?>');">
                            
                            <div class="container intro-content">
                                <!-- İsterseniz burada item'dan gelen başlık vb. alanları da kullanabilirsiniz -->
                                <h3 class="intro-subtitle text-primary">Tavsiye Edilen Ürün</h3>
                                <h1 class="intro-title">
                                    <?= str_replace(' ', '<br>', $slideItem["title"]) ?>
                                </h1>
                                <a href="/product-details?pid=<?= $slideItem["item_id"] ?>" class="btn btn-outline-primary-2">
                                    <span>DETAYLAR İÇİN</span>
                                    <i class="icon-long-arrow-right"></i>
                                </a>
                            </div><!-- End .intro-content -->
                        </div><!-- End .intro-slide -->
                    <?php endforeach; ?>

                </div><!-- End .intro-slider -->
                <span class="slider-loader"></span><!-- End .slider-loader -->
            </div><!-- End .intro-slider-container -->

            <div class="container">
                <div class="toolbox toolbox-filter">
                    <div class="toolbox-right">
                        <ul class="nav-filter product-filter">
                            <li class="active"><a href="#" data-filter="*">Hepsi</a></li>
                            <li><a href="#" data-filter=".Elektronik">Elektronik</a></li>
                            <li><a href="#" data-filter=".Ev_Ürünleri">Ev Ürünleri</a></li>
                            <li><a href="#" data-filter=".Kitap_ve_Dergi">Kitap ve Dergi</a></li>
                            <li><a href="#" data-filter=".Oyuncaklar">Oyuncaklar</a></li>
                            <li><a href="#" data-filter=".Kıyafet">Kıyafet</a></li>
                            <li><a href="#" data-filter=".Diğer">Diğer</a></li>
                        </ul>
                    </div><!-- End .toolbox-right -->
                </div><!-- End .filter-toolbox -->

                <div class="products-container row" data-layout="fitRows">
                    <?php 
                        foreach ($items as $item)
                        {
                            $categoryClass = str_replace(' ', '_', $item["category_name"]);
                            echo '
                            <div class="product-item '.$categoryClass.' col-6 col-md-4 col-lg-3">
                                <div class="product product-4">
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
                                            <a href="/product-details?pid='.$item["item_id"].'" class="btn-product btn-quickview" title="Quick view"><span>Ürün Detayları</span></a>
                                        </div>
                                    </figure>

                                    <div class="product-body" style="text-align: center;">
                                        <div class="product-cat">
                                            <a href="#">'.$item["category_name"].'</a>
                                        </div>
                                        <h3 class="product-title" style="font-weight: bold;"><a href="/product-details?pid='.$item["item_id"].'">'.$item["title"].'</a></h3>
                                    </div>
                                </div>
                            </div>';
                        }
                    ?>
                </div>

            </div><!-- End .container -->

        </main><!-- End .main -->

        <footer class="footer footer-2">
            <div class="footer-bottom">
                <div class="container">
                    <p class="footer-copyright">Copyright © 2025. All Rights Reserved.</p><!-- End .footer-copyright -->
                    <ul class="footer-menu">
                        <li><a href="#">Terms Of Use</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul><!-- End .footer-menu -->

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
    <script src="assets/js/imagesloaded.pkgd.min.js"></script>
    <script src="assets/js/isotope.pkgd.min.js"></script>
    <script src="assets/js/wNumb.js"></script>
    <script src="assets/js/nouislider.min.js"></script>
    <script src="assets/js/bootstrap-input-spinner.js"></script>
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    
    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/demos/demo-11.js"></script>
</body>

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