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
            $baseUrl = "http://localhost/api/items";
            $params = [
                'user_id' => $_SESSION["user"]["user_id"]
            ];
            
            $url = $baseUrl . '?' . http_build_query($params);
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            
            $responseData = json_decode($response, true); 
            $temp_items = $responseData['items'];

            //// ÜRÜN FOTOĞRAFLARI ///
            $baseUrl = "http://localhost/api/item-img";
            
            $url = $baseUrl;
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            
            $responseData = json_decode($response, true); 
            $item_images = $responseData['item_images'];

             //// TAKAS LİSTESİ ///
             $baseUrl = "http://localhost/api/offers";
            
             $url = $baseUrl;
             
             $ch = curl_init($url);
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
             $response = curl_exec($ch);
             curl_close($ch);
             
             $responseData = json_decode($response, true); 
             $offers = isset($responseData['offers']) && !empty($responseData['offers']) ? $responseData['offers'] : [];
             

            //// BÜTÜN - ÜRÜNLER ///
            $baseUrl = "http://localhost/api/all-items";
     
            $url = $baseUrl;
             
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
             
            $responseData = json_decode($response, true); 
            $all_items = $responseData['items'];


            // TAKAS TEKLİF KABUL EDİLEN ÜRÜNLERİN FİLTRELENMESİ //
            $acceptedItemIds = array_merge(
                array_column(array_filter($offers, fn($offer) => $offer['status'] === "Kabul Edildi"), 'item_id'),
                array_column(array_filter($offers, fn($offer) => $offer['status'] === "Kabul Edildi"), 'offered_item_id')
            );
            
            if (is_array($temp_items)) {
                $temp_items = array_filter($temp_items, fn($item) => !in_array($item['item_id'], $acceptedItemIds));
            } 
            
            if (is_array($temp_items)) {
                $temp_items = array_values($temp_items);
            } 
            
            
            $items = $temp_items;
            
        } 
        else
        {
            header("Location: /404");
            exit;
        }
    }
    else
    {
        header("Location: /404");
        exit;
    }

    function kacGunOnce($tarih) {
        $bugun = new DateTime(); 
        $verilenTarih = new DateTime($tarih); 
        $fark = $bugun->diff($verilenTarih); 
        return $fark->days;
    }
?>
<!DOCTYPE html>
<html lang="en">


<!-- molla/dashboard.html  22 Nov 2019 10:03:13 GMT -->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Hesabım</title>
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
            margin: 0 20px; /* Sağ ve sol kenar boşluğu */
        }

        .dropdown-cart-action a {
            display: block; /* Tüm boşluk alanını tıklanabilir yapar */
            text-align: center; /* Metin ortalar */
            padding: 8px 16px; /* İç kenar boşluğu */
        }

        .dropdown-menu {
            padding: 15px; /* Dropdown içindeki boşluk */
        }
        .name-section span {
            font-size: 18px; /* Yazı boyutu */
            display: block; /* Blok düzeni */
            text-align: center; /* Ortalayıcı */
            margin-bottom: 5px; /* Alt boşluk */
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
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

                        <a href="/wishlist" class="wishlist-link">
                            <i class="icon-heart-o"></i>
                        </a>

                      
                    </div><!-- End .header-right -->
                </div><!-- End .container -->
            </div><!-- End .header-middle -->
        </header><!-- End .header -->

        <main class="main">
        	<div class="page-header text-center" style="background-image: url('assets/images/page-header-bg.jpg')">
        		<div class="container">
        			<h1 class="page-title">Hesabım</h1>
        		</div><!-- End .container -->
        	</div><!-- End .page-header -->
            <nav aria-label="breadcrumb" class="breadcrumb-nav mb-3">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Anasayfa</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Hesabım</li>
                    </ol>
                </div><!-- End .container -->
            </nav><!-- End .breadcrumb-nav -->

            <div class="page-content">
            	<div class="dashboard">
	                <div class="container">
	                	<div class="row">
	                		<aside class="col-md-4 col-lg-3">
	                			<ul class="nav nav-dashboard flex-column mb-3 mb-md-0" role="tablist">
								    <li class="nav-item">
								        <a class="nav-link active" id="tab-account-link" data-toggle="tab" href="#tab-account" role="tab" aria-controls="tab-account" aria-selected="true">Hesap Detayları</a>
								    </li>
								    <li class="nav-item">
								        <a class="nav-link" id="tab-add-item-link" data-toggle="tab" href="#tab-add-item" role="tab" aria-controls="tab-add-item" aria-selected="false">Ürün Ekle</a>
								    </li>
								    <li class="nav-item">
								        <a class="nav-link" id="tab-my-item-link" data-toggle="tab" href="#tab-my-item" role="tab" aria-controls="tab-my-item" aria-selected="false">Ürünlerim</a>
								    </li>
                                    <li class="nav-item">
								        <a class="nav-link" id="tab-message-link" data-toggle="tab" href="#tab-message" role="tab" aria-controls="tab-message" aria-selected="false">Mesajlar</a>
								    </li>
								    <li class="nav-item">
								        <a class="nav-link" id="tab-offer-link" data-toggle="tab" href="#tab-offer" role="tab" aria-controls="tab-offer" aria-selected="false">Takas Tekliflerim</a>
								    </li>
								    <li class="nav-item">
								        <a class="nav-link" id="tab-offer-history-link" data-toggle="tab" href="#tab-offer-history" role="tab" aria-controls="tab-offer-history" aria-selected="false">Takas Geçmişi</a>
								    </li>
								    <li class="nav-item">
								        <a class="nav-link" href="/db/exit-login">Çıkış Yap</a>
								    </li>
								</ul>
	                		</aside><!-- End .col-lg-3 -->

	                		<div class="col-md-8 col-lg-9">
	                			<div class="tab-content">
								    <div class="tab-pane fade show active" id="tab-account" role="tabpanel" aria-labelledby="tab-account-link">
                                    
                                        
                                            <h3 class="card-title">Hesap Detayları</h3>
                                            <br>
		            						<label>Adı Soyadı *</label>
		            						<input type="text" class="form-control" value="<?php echo $_SESSION["user"]["name"];?>" disabled>

		                					<label>Email Adresi *</label>
		        							<input type="email" class="form-control" value="<?php echo preg_replace('/(?<=.).(?=.*@)/', '*', $_SESSION["user"]["email"]);?>" disabled>

                                            <br>
                                            <h3 class="card-title">Şifreni Değiştir</h3>

                                            <?php
                                                if (isset($_SESSION['message'])) 
                                                {
                                                    $message = $_SESSION['message'];
                                                    $messageType = $_SESSION['message_type']; 
                                                
                                                    $messageClass = $messageType === "success" ? "alert-success" : "alert-danger";
                                                    echo '<br>';
                                                    echo "<div class='alert {$messageClass}'>{$message}</div>";
                                                
                                                    unset($_SESSION['message']);
                                                    unset($_SESSION['message_type']);
                                                }
                                            ?>

                                            <form action="/db/change-password" method="POST" onsubmit="return validatePassword()">
                                                <br>
                                                <input type="hidden" class="form-control" name="user_id" value="<?php echo $_SESSION['user']['user_id']; ?>">

                                                <label>Mevcut Şifre</label>
                                                <input type="password" class="form-control" name="current_password" required>

                                                <label>Yeni Şifre</label>
                                                <input type="password" class="form-control" id="new_password" name="new_password" required>

                                                <label>Yeni Şifre Tekrar</label>
                                                <input type="password" class="form-control mb-2" id="c_new_password" name="c_new_password" required>

                                                <p id="error-message" style="color: red; display: none;">Yeni Şifreler Eşleşmiyor!</p>

                                                <button type="submit" class="btn btn-outline-primary-2">
                                                    <span>Kaydet</span>
                                                    <i class="icon-long-arrow-right"></i>
                                                </button>
                                            </form>


							
								    </div><!-- .End .tab-pane -->

								    <div class="tab-pane fade" id="tab-add-item" role="tabpanel" aria-labelledby="tab-add-item-link">
                                        
                                        <?php
                                            if (isset($_SESSION['message_add_item'])) 
                                            {
                                                $message = $_SESSION['message_add_item'];
                                                $messageType = $_SESSION['message_type_add_item']; 
                                            
                                                $messageClass = $messageType === "success" ? "alert-success" : "alert-danger";
                                                echo '<br>';
                                                echo "<div class='alert {$messageClass}'>{$message}</div>";
                                            
                                                unset($_SESSION['message_add_item']);
                                                unset($_SESSION['message_type_add_item']);
                                            }
                                        ?>

                                        <h3 class="card-title">Ürün Ekle</h3>

                                        <form action="/db/add-item" method="POST" enctype="multipart/form-data">
                                            <br>
                                            <input type="hidden" class="form-control" name="user_id" value="<?php echo $_SESSION['user']['user_id']; ?>">

                                            <label>Ürün Başlığı</label>

                                            <input type="text" class="form-control" name="title" required>

                                            <label>Kategori</label>
                                            <div class="select-custom">
                                                <select id="category_name" class="form-control" name="category_name">
                                                    <option value="Elektronik" selected="selected">Elektronik</option>
                                                    <option value="Ev Ürünleri">Ev Ürünleri</option>
                                                    <option value="Kitap ve Dergi">Kitap ve Dergi</option>
                                                    <option value="Oyuncaklar">Oyuncaklar</option>
                                                    <option value="Kıyafet">Kıyafet</option>
                                                    <option value="Diğer">Diğer</option>
                                                </select>
                                            </div>

                                            <label>Açıklama</label>
                                            <textarea id="description" class="form-control" name="description" rows="4" cols="50"></textarea>

                                            <label>Ürün Durumu</label>
                                            <div class="select-custom">
                                                <select id="status" class="form-control" name="status">
                                                    <option value="yeni" selected="selected">Yeni</option>
                                                    <option value="kullanılmış">Kullanılmış</option>
                                                </select>
                                            </div>

                                            <label>Konum</label>
                                            <div class="select-custom">
                                                <select id="location" class="form-control" name="location"></select>
                                            </div>


                                            <label>Fotoğraf Yükle</label>
                                            <input type="file" class="form-control" name="photo" accept="image/*" required>

                                            <button type="submit" class="btn btn-outline-primary-2">
                                                <span>Ekle</span>
                                                <i class="icon-long-arrow-right"></i>
                                            </button>
                                        </form>


								    </div><!-- .End .tab-pane -->

								    <div class="tab-pane fade" id="tab-my-item" role="tabpanel" aria-labelledby="tab-my-item-link">
								    	
                                    <?php 

                                        if($_SESSION['edit-item'] != true)
                                        {
                                            echo '
                                            <h3 class="card-title">Ürünlerim</h3>
                                            <table class="table table-wishlist table-mobile">
                                                <thead>
                                                    <tr>
                                                        <th>Ürün</th>
                                                        <th>Kategori</th>
                                                        <th>Durum</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
    
                                                <tbody>
                                                    ';
                                                        foreach ($items as $item) 
                                                        {
                                                            echo '<tr>
                                                                <td class="product-col">
                                                                    <div class="product">
                                                                        <figure class="product-media">
                                                                            <a href="/product-details?pid='.$item["item_id"].'">
                                                                                <img src="/img/'.$item["photo"].'" alt="Product image" class="product-image">
                                                                            </a>
                                                                        </figure>
    
                                                                        <h3 class="product-title">
                                                                            <a href="/product-details?pid='.$item["item_id"].'">'.$item["title"].'</a>
                                                                        </h3>
                                                                    </div>
                                                                </td>
                                                                <td class="price-col">'.$item["category_name"].'</td>
                                                                <td class="stock-col">'.$item["status"].'</td>
                                                                <td class="action-col">
                                                                    <div class="dropdown">
                                                                    <button class="btn btn-block btn-outline-primary-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        İşlem Seçin
                                                                    </button>
    
                                                                    <div class="dropdown-menu">
                                                                        <a class="dropdown-item edit-btn" href="/edit-item?temp=false&item_id='.$item['item_id'].'">Ürünü Düzenle</a>
                                                                        <a class="dropdown-item" href="#" onclick="confirmDelete(event, \'/db/delete-item?user_id='.$_SESSION['user']['user_id'].'&item_id='.$item['item_id'].'\')">Ürünü Sil</a>
                                                                    </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            ';
                                                        }
                                                        
    
                                            echo '  
                                                </tbody>
                                            </table>
                                            ';
                                        }
                                        else
                                        {
                                            echo '
                                                <a href="/edit-item?temp=true" class="btn btn-outline-primary btn-round"><i class="icon-long-arrow-left"></i> Ürünlerime Dön</a>

                                                
                                                <h3 class="card-title" style="margin-top: 20px; margin-bottom: 20px;">Ürünü Düzenle</h3>
                                                ';

                                                foreach ($items as $item) 
                                                {
                                                    if($item['item_id'] == $_SESSION['edit-item-id'] && $item['user_id'] == $_SESSION['user']['user_id'])
                                                    {
                                                        echo '
                                                            <a href="#">
                                                                <img src="/img/'.$item["photo"].'" alt="Product image" style="width: 50px;">
                                                            </a>
                                                        <form action="/db/edit-item" method="POST" enctype="multipart/form-data">
                                                            <br>
                                                            <input type="hidden" class="form-control" name="user_id" value="'.$_SESSION['user']['user_id'].'">
                                                            <input type="hidden" class="form-control" name="item_id" value="'.$item['item_id'].'">

                                                            <label>Ürün Başlığı</label>
    
                                                            <input type="text" class="form-control" name="title"  value="'.$item['title'].'" required>
    
                                                            <label>Kategori</label>
                                                            <div class="select-custom">
                                                                <select id="category_name" class="form-control" name="category_name">
                                                                    <option value="Elektronik" ' . ($item['category_name'] == 'electronic' ? 'selected' : '') . '>Elektronik</option>
                                                                    <option value="Ev Ürünleri" ' . ($item['category_name'] == 'household-goods' ? 'selected' : '') . '>Ev Ürünleri</option>
                                                                    <option value="Kitap ve Dergi" ' . ($item['category_name'] == 'books' ? 'selected' : '') . '>Kitap ve Dergi</option>
                                                                    <option value="Oyuncaklar" ' . ($item['category_name'] == 'toys' ? 'selected' : '') . '>Oyuncaklar</option>
                                                                    <option value="Kıyafet" ' . ($item['category_name'] == 'clothes' ? 'selected' : '') . '>Kıyafet</option>
                                                                    <option value="Diğer" ' . ($item['category_name'] == 'other' ? 'selected' : '') . '>Diğer</option>
                                                                </select>
                                                            </div>
    
                                                            <label>Açıklama</label>
                                                            <textarea id="description" class="form-control" name="description" rows="4" cols="50">'.$item['description'].'</textarea>
    
                                                            <label>Ürün Durumu</label>
                                                            <div class="select-custom">
                                                                <select id="status" class="form-control" name="status">
                                                                    <option value="yeni" ' . ($item['status'] == 'Yeni' ? 'selected' : '') . '>Yeni</option>
                                                                    <option value="kullanılmış" ' . ($item['status'] == 'Kullanılmış' ? 'selected' : '') . '>Kullanılmış</option>
                                                                </select>
                                                            </div>
    
                                                            <button type="submit" class="btn btn-outline-primary-2">
                                                                <span>Güncelle</span>
                                                                <i class="icon-long-arrow-right"></i>
                                                            </button>
                                                        </form>
                                                        ';
                                                        /// FOTOĞRAF EKLEME
                                                        echo'
                                                        <h3 class="card-title" style="margin-top: 30px;">Ürün Fotoğrafları</h3>
                                                        ';
                                                            if (isset($_SESSION['message_add_item_img'])) 
                                                            {
                                                                $message = $_SESSION['message_add_item_img'];
                                                                $messageType = $_SESSION['message_type_add_item_img']; 
                                                            
                                                                $messageClass = $messageType === "success" ? "alert-success" : "alert-danger";
                                                            
                                                                echo "<div class='alert {$messageClass}'>{$message}</div>";
                                                            
                                                                unset($_SESSION['message_add_item_img']);
                                                                unset($_SESSION['message_type_add_item_img']);
                                                            }
                                                        echo '
                                                        <form action="/db/add-item-img" method="POST" enctype="multipart/form-data">
                                                            <br>
                                                            <input type="hidden" class="form-control" name="item_id" value="'.$item['item_id'].'">

                                                            <label>Fotoğraf Yükle</label>
                                                            <input type="file" class="form-control" name="photo" accept="image/*" required>

                                                            <button type="submit" class="btn btn-outline-primary-2">
                                                                <span>Ekle</span>
                                                                <i class="icon-long-arrow-right"></i>
                                                            </button>
                                                        </form>

                                                        <table class="table table-wishlist table-mobile" style="margin-top: 20px;">
                                                            <thead>
                                                                <tr>
                                                                    <th>ID</th>
                                                                    <th>Ürün Fotoğrafı</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                
                                                            <tbody>
                                                                ';
                                                                    $img_count = 0;
                                                                    foreach ($item_images as $item_image) 
                                                                    {
                                                                        if($item_image["item_id"] == $item["item_id"])
                                                                        {
                                                                            $img_count++;
                                                                            echo '<tr>
                                                                                <td class="price-col">'.$img_count.'</td>
                                                                                <td class="product-col">
                                                                                    <div class="product">
                                                                                            <a href="#">
                                                                                                <img src="/img/product_img/'.$item_image["image_url"].'" alt="Product image" class="product-image">
                                                                                            </a>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="action-col">
                                                                                    <div class="dropdown">
                                                                                    <button class="btn btn-block btn-outline-primary-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                        İşlem Seçin
                                                                                    </button>
                    
                                                                                    <div class="dropdown-menu">
                                                                                        <a class="dropdown-item" href="#" onclick="confirmDelete(event, \'/db/delete-item-img?image_id='.$item_image["image_id"].'\')">Ürünü Sil</a>
                                                                                    </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            ';
                                                                        }
                                                                        
                                                                    } 
                
                                                            echo '  
                                                            </tbody>
                                                        </table>
                                                        ';
                                                    }
                                                    
                                                }
                                        }
                                        
                                    ?>
								    </div><!-- .End .tab-pane -->



								    <div class="tab-pane fade" id="tab-offer" role="tabpanel" aria-labelledby="tab-offer-link">
                                        <h3 class="card-title">Tekliflerim</h3>
                                            <table class="table table-wishlist table-mobile" style="margin-top: 20px;">
                                                <thead>
                                                    <tr>
                                                        <th>Benim Ürünüm</th>
                                                        <th>Alınacak Ürün</th>
                                                        <th>Durum</th>
                                                        <th>Tarih</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <?php
                                                    echo'
                                                    <tbody>';
                                                        foreach($offers as $offer)
                                                        {
                                                            if($_SESSION["user"]["user_id"] == $offer["offered_by"] && $offer["status"] == "Bekliyor")
                                                            {
                                                                echo '
                                                                <tr>
                                                                    ';
                                                                    foreach($all_items as $item)
                                                                    {
                                                                        if($item["item_id"] == $offer["offered_item_id"])
                                                                        {
                                                                            echo '
                                                                            <td class="action-col">
                                                                                <a href="/product-details?pid='.$item["item_id"].'" style="font-size: 15px;">'.$item["title"].'</a>
                                                                            </td>';
                                                                        }  
                                                                    }
                                                                    foreach($all_items as $item)
                                                                    {
                                                                        if($item["item_id"] == $offer["item_id"])
                                                                        {
                                                                            echo '
                                                                            <td class="action-col">
                                                                                <a href="/product-details?pid='.$item["item_id"].'" style="font-size: 15px;">'.$item["title"].'</a>
                                                                            </td>';
                                                                        }  
                                                                    }
                                                                    
                                                                    echo '
                                                                    <td class="action-col">
                                                                        '.$offer["status"].'
                                                                    </td>
                                                                    <td class="action-col">';
                                                                      
                                                                        $eskiTarih = $offer['created_at'];
                                                                        echo kacGunOnce($eskiTarih) . " gün önce teklif verildi";
                                                                        echo '

                                                                    </td>
                                                                    <td class="product-col">
                                                                        <div class="dropdown">
                                                                        <button class="btn btn-block btn-outline-primary-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            İşlem Seçin
                                                                        </button>

                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="#" onclick="updateOfferStatus(' . $offer['offer_id'] . ', \'Reddedildi\')">Takası İptal Et</a>
                                                                        </div>
                                                                        </div>
                                                                    </td>
                                                                </tr> 
                                                                ';
                                                            }
                                                            
                                                        }
                                                        
                                                    echo '
                                                    </tbody>';
                                                ?>
                                            </table>
                                        <h3 class="card-title">Gelen Teklifler</h3>
                                            <table class="table table-wishlist table-mobile" style="margin-top: 20px;">
                                                <thead>
                                                    <tr>
                                                        <th>Benim Ürünüm</th>
                                                        <th>Alınacak Ürün</th>
                                                        <th>Durum</th>
                                                        <th>Tarih</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <?php
                                                    echo'
                                                    <tbody>';
                                                        foreach($offers as $offer)
                                                        {
                                                            foreach($all_items as $product)
                                                            {
                                                                if($product["item_id"] == $offer["item_id"])
                                                                {
                                                                   if($product["user_id"] == $_SESSION["user"]["user_id"] && $offer["status"] == "Bekliyor") 
                                                                   {
                                                                        echo '
                                                                        <tr>
                                                                            ';
                                                                            foreach($all_items as $item)
                                                                            {
                                                                                if($item["item_id"] == $offer["item_id"])
                                                                                {
                                                                                    echo '
                                                                                    <td class="action-col">
                                                                                        <a href="/product-details?pid='.$item["item_id"].'" style="font-size: 15px;">'.$item["title"].'</a>
                                                                                    </td>';
                                                                                }  
                                                                            }

                                                                            foreach($all_items as $item)
                                                                            {
                                                                                if($item["item_id"] == $offer["offered_item_id"])
                                                                                {
                                                                                    echo '
                                                                                    <td class="action-col">
                                                                                        <a href="/product-details?pid='.$item["item_id"].'" style="font-size: 15px;">'.$item["title"].'</a>
                                                                                    </td>';
                                                                                }   
                                                                            }
                                                                           
                                                                            echo '
                                                                            <td class="action-col">
                                                                                '.$offer["status"].'
                                                                            </td>
                                                                            <td class="action-col">';
                                                                                
                                                                                $eskiTarih = $offer['created_at'];
                                                                                echo kacGunOnce($eskiTarih) . " gün önce teklif verildi";

                                                                                echo '

                                                                            </td>
                                                                            <td class="product-col">
                                                                                <div class="dropdown">
                                                                                <button class="btn btn-block btn-outline-primary-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                    İşlem Seçin
                                                                                </button>

                                                                                <div class="dropdown-menu">
                                                                                    <a class="dropdown-item" href="#" onclick="updateOfferStatus(' . $offer['offer_id'] . ', \'Kabul Edildi\')">Takası Kabul Et</a>
                                                                                    <a class="dropdown-item" href="#" onclick="updateOfferStatus(' . $offer['offer_id'] . ', \'Reddedildi\')">Takası İptal Et</a>
                                                                                </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr> 
                                                                        ';
                                                                   }
                                                                }
                                                                
                                                            }
                                                        }
                                                        
                                                    echo '
                                                    </tbody>';
                                                ?>
                                            </table>

								    </div><!-- .End .tab-pane -->



								    <div class="tab-pane fade" id="tab-offer-history" role="tabpanel" aria-labelledby="tab-offer-history-link">
                                        <h3 class="card-title">Takas Geçmişim</h3>
                                            <table class="table table-wishlist table-mobile" style="margin-top: 20px;">
                                                <thead>
                                                    <tr>
                                                        <th>Benim Ürünüm</th>
                                                        <th>Alınacak Ürün</th>
                                                        <th>Durum</th>
                                                        <th>Tarih</th>
                                                    </tr>
                                                </thead>
                                                <?php
                                                    echo'
                                                    <tbody>';
                                                        foreach($offers as $offer)
                                                        {
                                                            foreach($all_items as $product)
                                                            {
                                                                if($product["item_id"] == $offer["item_id"])
                                                                {
                                                                   if(( $product["user_id"] == $_SESSION["user"]["user_id"] && $offer["status"] != "Bekliyor" )) 
                                                                   {
                                                                        echo '
                                                                        <tr>
                                                                            ';
                                                                            foreach($all_items as $item)
                                                                            {
                                                                                if($item["item_id"] == $offer["item_id"])
                                                                                {
                                                                                    echo '
                                                                                    <td class="action-col">
                                                                                        <a style="font-size: 15px;">'.$item["title"].'</a>
                                                                                    </td>';
                                                                                }  
                                                                            }

                                                                            foreach($all_items as $item)
                                                                            {
                                                                                if($item["item_id"] == $offer["offered_item_id"])
                                                                                {
                                                                                    echo '
                                                                                    <td class="action-col">
                                                                                        <a style="font-size: 15px;">'.$item["title"].'</a>
                                                                                    </td>';
                                                                                }   
                                                                            }
                                                                           
                                                                            echo '
                                                                            <td class="action-col">
                                                                                '.$offer["status"].'
                                                                            </td>
                                                                            <td class="action-col">';
                                                                             
                                                                                $eskiTarih = $offer['created_at'];
                                                                                echo kacGunOnce($eskiTarih) . " gün önce teklif verildi";
                                                                                echo '

                                                                            </td>
                                                                        </tr> 
                                                                        ';
                                                                   }
                                                                   else if($_SESSION["user"]["user_id"] == $offer["offered_by"] && $offer["status"] != "Bekliyor")
                                                                   {
                                                                        echo '
                                                                        <tr>
                                                                            ';
                                                                            foreach($all_items as $item)
                                                                            {
                                                                                if($item["item_id"] == $offer["offered_item_id"])
                                                                                {
                                                                                    echo '
                                                                                    <td class="action-col">
                                                                                        <a style="font-size: 15px;">'.$item["title"].'</a>
                                                                                    </td>';
                                                                                }  
                                                                            }

                                                                            foreach($all_items as $item)
                                                                            {
                                                                                if($item["item_id"] == $offer["item_id"])
                                                                                {
                                                                                    echo '
                                                                                    <td class="action-col">
                                                                                        <a style="font-size: 15px;">'.$item["title"].'</a>
                                                                                    </td>';
                                                                                }   
                                                                            }
                                                                        
                                                                            echo '
                                                                            <td class="action-col">
                                                                                '.$offer["status"].'
                                                                            </td>
                                                                            <td class="action-col">';
                                                                            
                                                                                $eskiTarih = $offer['created_at'];
                                                                                echo kacGunOnce($eskiTarih) . " gün önce teklif verildi";
                                                                                echo '

                                                                            </td>
                                                                        </tr> 
                                                                        ';
                                                                   }
                                                                }
                                                                
                                                            }
                                                        }
                                                        
                                                    echo '
                                                    </tbody>';
                                                ?>
                                            </table>

								    </div><!-- .End .tab-pane -->

                                    <div class="tab-pane fade" id="tab-message" role="tabpanel" aria-labelledby="tab-message-link">
                                        <?php include 'message.php'; ?>


								    </div><!-- .End .tab-pane -->

								</div>
	                		</div><!-- End .col-lg-9 -->
	                	</div><!-- End .row -->
	                </div><!-- End .container -->
                </div><!-- End .dashboard -->
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
    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>
</body>


</html>


<script>
    function confirmDelete(event, deleteUrl) {
        event.preventDefault(); 
        if (confirm("Silmek istediğinizden emin misiniz?")) {
            window.location.href = deleteUrl; 
        }
    }

    function validatePassword() {
        let newPassword = document.getElementById("new_password").value;
        let confirmPassword = document.getElementById("c_new_password").value;
        let errorMessage = document.getElementById("error-message");

        if (newPassword !== confirmPassword) {
            errorMessage.style.display = "block";
            return false; 
        } else {
            errorMessage.style.display = "none";
            return true; 
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        let hash = window.location.hash;
        if (hash) {
            let activeTab = document.querySelector(`.nav-link[href="${hash}"]`);
            if (activeTab) {
                document.querySelector('.nav-link.active')?.classList.remove('active');
                activeTab.classList.add('active');
                document.querySelector('.tab-pane.active')?.classList.remove('active', 'show');
                document.querySelector(hash)?.classList.add('active', 'show');
            }
        }
        
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function () {
                history.pushState(null, null, this.getAttribute('href'));
            });
        });
    });

    const cities = [
        "Adana", "Adıyaman", "Afyonkarahisar", "Ağrı", "Aksaray", "Amasya", 
        "Ankara", "Antalya", "Ardahan", "Artvin", "Aydın", "Balıkesir", 
        "Bartın", "Batman", "Bayburt", "Bilecik", "Bingöl", "Bitlis", 
        "Bolu", "Burdur", "Bursa", "Çanakkale", "Çankırı", "Çorum", "Denizli",
        "Diyarbakır", "Düzce", "Edirne", "Elazığ", "Erzincan", "Erzurum", 
        "Eskişehir", "Gaziantep", "Giresun", "Gümüşhane", "Hakkari", "Hatay", 
        "Iğdır", "Isparta", "İstanbul", "İzmir", "Kahramanmaraş", "Karabük", 
        "Karaman", "Kars", "Kastamonu", "Kayseri", "Kırıkkale", "Kırklareli", 
        "Kırşehir", "Kilis", "Kocaeli", "Konya", "Kütahya", "Malatya", 
        "Manisa", "Mardin", "Mersin", "Muğla", "Muş", "Nevşehir", 
        "Niğde", "Ordu", "Osmaniye", "Rize", "Sakarya", "Samsun", 
        "Siirt", "Sinop", "Sivas", "Şanlıurfa", "Şırnak", "Tekirdağ", 
        "Tokat", "Trabzon", "Tunceli", "Uşak", "Van", "Yalova", 
        "Yozgat", "Zonguldak"
    ];


    const locationSelect = document.getElementById("location");

    cities.forEach(city => {
        let option = document.createElement("option");
        option.value = city.toLowerCase();
        option.textContent = city;
        locationSelect.appendChild(option);
    });

    locationSelect.value = "ankara";


    function updateOfferStatus(offerId, status) {
        fetch(`/api/offer-status?offer_id=${offerId}&status=${status}`)
            .then(response => response.json())
            .then(data => {
                location.reload();
                alert(`Teklif ${status.toLowerCase()}!`); 
            })
            .catch(error => console.error('Hata:', error));
    }

</script>
