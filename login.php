<?php
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
            header("Location: /");
            exit;
        } 
    }
?>
<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Giriş Yap</title>
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
                                <li class="megamenu-container">
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

                </div><!-- End .container -->
            </div><!-- End .header-middle -->
        </header><!-- End .header -->

        <main class="main">
            <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Anasayfa</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Giriş Yap</li>
                    </ol>
                </div><!-- End .container -->
            </nav><!-- End .breadcrumb-nav -->

            <div class="login-page bg-image pt-8 pb-8 pt-md-12 pb-md-12 pt-lg-17 pb-lg-17" style="background-image: url('assets/images/backgrounds/login-bg.jpg')">
            	<div class="container">
            		<div class="form-box">
            			<div class="form-tab">
	            			<ul class="nav nav-pills nav-fill" role="tablist">
							    <li class="nav-item">
							        <a class="nav-link active" id="signin-tab-2" data-toggle="tab" href="#signin-2" role="tab" aria-controls="signin-2" aria-selected="false">Giriş Yap</a>
							    </li>
							    <li class="nav-item">
							        <a class="nav-link" id="register-tab-2" data-toggle="tab" href="#register-2" role="tab" aria-controls="register-2" aria-selected="true">Kayıt Ol</a>
							    </li>
							</ul>
							<div class="tab-content">
							    <div class="tab-pane fade show active" id="signin-2" role="tabpanel" aria-labelledby="signin-tab-2">
                                    <form action="/db/login" method="POST">
							    		<div class="form-group">
							    			<label for="singin-email-2">Email Adresi *</label>
							    			<input type="text" class="form-control" id="singin-email-2" name="singin-email" required>
							    		</div><!-- End .form-group -->

							    		<div class="form-group">
							    			<label for="singin-password-2">Şifre *</label>
							    			<input type="password" class="form-control" id="singin-password-2" name="singin-password" required>
							    		</div><!-- End .form-group -->

							    		<div class="form-footer">
							    			<button type="submit" class="btn btn-outline-primary-2">
			                					<span>Giriş Yap</span>
			            						<i class="icon-long-arrow-right"></i>
			                				</button>

			                				<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="signin-remember-2">
											</div><!-- End .custom-checkbox -->

											<a href="#" class="forgot-link">Şifremi Unuttum?</a>
							    		</div><!-- End .form-footer -->
							    	</form>
                                     <?php
                                       if (isset($_SESSION['message'])) 
                                        {
                                            $message = $_SESSION['message'];
                                            $messageType = $_SESSION['message_type']; 
                                        
                                            $messageClass = $messageType === "success" ? "alert-success" : "alert-danger";
                                        
                                            echo "<div class='alert {$messageClass}'>{$message}</div>";
                                        
                                            unset($_SESSION['message']);
                                            unset($_SESSION['message_type']);
                                        }
                                    ?>
							    </div><!-- .End .tab-pane -->
							    <div class="tab-pane fade" id="register-2" role="tabpanel" aria-labelledby="register-tab-2">
                                
							    	<form action="/db/register" method="POST">

                                        <div class="form-group">
							    			<label for="register-name-2">Ad ve Soyad *</label>
							    			<input type="text" class="form-control" id="register-name-2" name="register-name" required>
							    		</div><!-- End .form-group -->

							    		<div class="form-group">
							    			<label for="register-email-2">Email Adresi *</label>
							    			<input type="email" class="form-control" id="register-email-2" name="register-email" required>
							    		</div><!-- End .form-group -->

							    		<div class="form-group">
							    			<label for="register-password-2">Şifre *</label>
							    			<input type="password" class="form-control" id="register-password-2" name="register-password" required>
							    		</div><!-- End .form-group -->

							    		<div class="form-footer">
							    			<button type="submit" class="btn btn-outline-primary-2">
			                					<span>Kayıt Ol</span>
			            						<i class="icon-long-arrow-right"></i>
			                				</button>

			                				<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="register-policy-2" required>
												<label class="custom-control-label" for="register-policy-2">I agree to the <a href="#">privacy policy</a> *</label>
											</div><!-- End .custom-checkbox -->
							    		</div><!-- End .form-footer -->
							    	</form>
                                    <?php
                                       if (isset($_SESSION['message'])) 
                                        {
                                            $message = $_SESSION['message'];
                                            $messageType = $_SESSION['message_type']; 
                                        
                                            $messageClass = $messageType === "success" ? "alert-success" : "alert-danger";
                                        
                                            echo "<div class='alert {$messageClass}'>{$message}</div>";
                                        
                                            unset($_SESSION['message']);
                                            unset($_SESSION['message_type']);
                                        }
                                    ?>
							    </div><!-- .End .tab-pane -->
							</div><!-- End .tab-content -->
						</div><!-- End .form-tab -->
            		</div><!-- End .form-box -->
            	</div><!-- End .container -->
            </div><!-- End .login-page section-bg -->
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
	            					<li><a href="/login">Giriş Yap</a></li>
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