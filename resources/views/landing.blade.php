<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Acculance | POS, Inventory, Accounting Application</title>
    <meta name="description"
        content="Acculance is an all-in-one management system that enables you to manage expenses, purchases, sales, payments, accounting, loans, assets, payroll, and many more." />
    <meta name="author" content="CODESHAPER">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('landing-assets/images/favicon.svg') }}" />
    <!-- ========================= CSS here ========================= -->
    <link rel="stylesheet" href="{{ asset('landing-assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing-assets/css/LineIcons.2.0.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing-assets/css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing-assets/css/tiny-slider.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing-assets/css/glightbox.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing-assets/css/main.css') }}" />
    <script type="text/javascript">
        window.$crisp = [];
        window.CRISP_WEBSITE_ID = "ee891f0f-3dd8-4bcb-bb49-948a93ad1fa8";
        (function() {
            d = document;
            s = d.createElement("script");
            s.src = "https://client.crisp.chat/l.js";
            s.async = 1;
            d.getElementsByTagName("head")[0].appendChild(s);
        })();
    </script>

</head>

<body>
    <!--[if lte IE 9]>
    <p class="browserupgrade">
        You are using an <strong>outdated</strong> browser. Please
        <a href="https://browsehappy.com/">upgrade your browser</a> to improve
        your experience and security.
    </p>
    <![endif]-->

    <!-- Preloader -->
    <div class="preloader">
        <div class="preloader-inner">
            <div class="preloader-icon">
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    <!-- /End Preloader -->

    <!-- Start Header Area -->
    <header class="header navbar-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="nav-inner">
                        <!-- Start Navbar -->
                        <nav class="navbar navbar-expand-lg">
                            <a class="navbar-brand" href="{{ url('/') }}">
                                <img src="{{ asset('landing-assets/images/logo/white-logo.svg') }}" alt="Logo">
                            </a>
                            <button class="navbar-toggler mobile-menu-btn" type="button" data-bs-toggle="collapse"
                                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                aria-expanded="false" aria-label="Toggle navigation">
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
                                <ul id="nav" class="navbar-nav ms-auto">
                                    <li class="nav-item">
                                        <a href="#home" class="page-scroll active"
                                            aria-label="Toggle navigation">Home</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#features" class="page-scroll"
                                            aria-label="Toggle navigation">Features</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#pricing" class="page-scroll"
                                            aria-label="Toggle navigation">Pricing</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="https://codeshaper.net/contact-us" target="_blank"
                                            aria-label="Toggle navigation">Support</a>
                                    </li>
                                </ul>
                            </div> <!-- navbar collapse -->
                            <div class="button add-list-button">
                                <a href="https://1.envato.market/y2jezW" target="_blank" class="btn">Buy now</a>
                            </div>
                        </nav>
                        <!-- End Navbar -->
                    </div>
                </div>
            </div> <!-- row -->
        </div> <!-- container -->
    </header>
    <!-- End Header Area -->

    <!-- Start Hero Area -->
    <section id="home" class="hero-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 col-md-12 col-12">
                    <div class="hero-content">
                        <h1 class="wow fadeInLeft" data-wow-delay=".4s">Acculance.</h1>
                        <p class="wow fadeInLeft" data-wow-delay=".6s">Acculance is a comprehensive web application
                            designed for Point of Sale (POS), Inventory management, and Accounting. Built using Laravel
                            and Vue.js, it addresses the requirements of small and medium-sized businesses and
                            organizations.</p>
                        <p>With the newly released version v4.0.0, we have undergone significant enhancements to the
                            application, rendering it even more versatile and suitable for a diverse spectrum of
                            businesses.</p>
                        <ul class="header-features">
                            <li class="wow fadeInLeft" data-wow-delay=".6s">
                                <a href="#">Individual client ledger.</a>
                            </li>
                            <li class="wow fadeInLeft" data-wow-delay=".7s">
                                <a href="#">Individual Supplier ledger.</a>
                            </li>
                            <li class="wow fadeInLeft" data-wow-delay=".7s">
                                <a href="#">Individual Account ledger.</a>
                            </li>
                            <li class="wow fadeInLeft" data-wow-delay=".7s">
                                <a href="#">Email & SMS Notification.</a>
                            </li>
                            <li class="wow fadeInLeft" data-wow-delay=".7s">
                                <a href="#">Realtime Insights & Versatile Reports.</a>
                            </li>
                            <li class="wow fadeInLeft" data-wow-delay=".9s">
                                <a href="#">Responsive, Fast, Secure and Multilingual.</a>
                            </li>
                        </ul>
                        <div class="button wow fadeInLeft" data-wow-delay="1s">
                            <a href="/login" target="_blank" class="btn">Try Demo</a>
                            <a href="http://docs.codeshaper.tech/acculance/" target="_blank" class="btn btn-alt">
                                Documentation
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 col-md-12 col-12">
                    <div class="hero-image wow fadeInRight" data-wow-delay=".4s">
                        <img src="{{ asset('landing-assets/images/hero/laptop.png') }}" alt="#">
                    </div>
                    <div class="language-used">
                        <ul>
                            <li>
                                <a href="https://www.php.net/" target="_blank">
                                    <img src="{{ asset('landing-assets/images/php.svg') }}">
                                </a>
                            </li>
                            <li>
                                <a href="https://laravel.com" target="_blank">
                                    <img src="{{ asset('landing-assets/images/laravel.svg') }}">
                                </a>
                            </li>
                            <li>
                                <a href="https://vuejs.org/" target="_blank">
                                    <img src="{{ asset('landing-assets/images/vuejs.svg') }}">
                                </a>
                            </li>
                            <li>
                                <a href="https://vuejs.org/" target="_blank">
                                    <img src="{{ asset('landing-assets/images/bootstrap.svg') }}">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Hero Area -->

    <!-- Start Features Area -->
    <section id="features" class="features section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h3 class="wow zoomIn" data-wow-delay=".2s">Main Features</h3>
                        <h2 class="wow fadeInUp" data-wow-delay=".4s">Unlock the Power of Seamless Business Management
                            with Acculance
                        </h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay=".2s">
                        <i class="lni lni-calculator"></i>
                        <h3>Expense Management</h3>
                        <p>The Expense module empowers efficient company expense management. Categorize and
                            subcategorize all expenses, ensuring meticulous accounting for every expenditure.</p>
                    </div>
                    <!-- End Single Feature -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay=".2s">
                        <i class="lni lni-shopping-basket"></i>
                        <h3>Purchase Management</h3>
                        <p>The Purchase module facilitates product procurement and returns. Creating purchases boosts
                            product stock, while deleting or returning purchases reduces stock levels.</p>
                    </div>
                    <!-- End Single Feature -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay=".4s">
                        <i class="lni lni-cart"></i>
                        <h3>Sales Management</h3>
                        <p>The Sales module streamlines sales quotations, product transactions, and returns. Creating
                            sales reduces stock quantities, and deleting sales increases them.</p>
                    </div>
                    <!-- End Single Feature -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay=".6s">
                        <i class="lni lni-book"></i>
                        <h3>Accounting Management</h3>
                        <p>The Accounting module oversees transactions and accounts, with each transaction linked to
                            cash or bank accounts. Credit transactions bolster balances, while debit transactions
                            diminish them.</p>
                    </div>
                    <!-- End Single Feature -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay=".2s">
                        <i class="lni lni-customer"></i>
                        <h3>Loan Management</h3>
                        <p>The Loan module simplifies loan administration, including authorities, loans, and payments.
                            Acculance accommodates both CC loans and term loans, with funds seamlessly integrated into
                            accounts.</p>
                    </div>
                    <!-- End Single Feature -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay=".4s">
                        <i class="lni lni-hammer"></i>
                        <h3>Asset Management</h3>
                        <p>The Asset module effectively administers company assets and their depreciation, employing a
                            straight-line depreciation method. Asset creation options include assets without
                            depreciation.</p>
                    </div>
                    <!-- End Single Feature -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay=".6s">
                        <i class="lni lni-wallet"></i>
                        <h3>Payroll Management</h3>
                        <p>The Payroll module streamlines monthly salary management. Generate employee salaries,
                            ensuring meticulous accounting, and integration with accounts. It also generate the payslip.
                        </p>
                    </div>
                    <!-- End Single Feature -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay=".6s">
                        <i class="lni lni-restaurant"></i>
                        <h3>Inventory Management</h3>
                        <p>The Inventory module provides precise control over product stocks, enabling stock
                            adjustments. It also offers insights into average purchase prices
                            and comprehensive inventory history.</p>
                    </div>
                    <!-- End Single Feature -->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Feature -->
                    <div class="single-feature wow fadeInUp" data-wow-delay=".6s">
                        <i class="lni lni-bar-chart"></i>
                        <h3>Versatile Reports</h3>
                        <p>Acculance offers a range of critical reports for tracking business performance. Easily access
                            balance sheets, summary reports, profit/loss statements, expense, & inventory
                            insights.</p>
                    </div>
                    <!-- End Single Feature -->
                </div>
            </div>
        </div>
    </section>
    <!-- End Features Area -->

    <!-- Start Achievement Area -->
    <section class="our-achievement section">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1 col-md-12 col-12">
                    <div class="title">
                        <h2>More Awesome Features</h2>
                        <p>Acculance comes equipped with a plethora of valuable features, some of which are highlighted
                            below.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-6">
                            <div class="single-mfeature wow fadeInUp" data-wow-delay=".2s">
                                <i class="lni lni-users"></i>
                                <p>Customer</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-6">
                            <div class="single-mfeature wow fadeInUp" data-wow-delay=".4s">
                                <i class="lni lni-user"></i>
                                <p>Supplier</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-6">
                            <div class="single-mfeature wow fadeInUp" data-wow-delay=".6s">
                                <i class="lni lni-unlock"></i>
                                <p>Role-based Permissions</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-6">
                            <div class="single-mfeature wow fadeInUp" data-wow-delay=".8s">
                                <i class="lni lni-book"></i>
                                <p>Non-invoice Transactions</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-6">
                            <div class="single-mfeature wow fadeInUp" data-wow-delay=".2s">
                                <i class="lni lni-book"></i>
                                <p>Non-purchase Transactions</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-6">
                            <div class="single-mfeature wow fadeInUp" data-wow-delay=".4s">
                                <i class="lni lni-dropbox"></i>
                                <p>Inventory Adjustment</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-6">
                            <div class="single-mfeature wow fadeInUp" data-wow-delay=".6s">
                                <i class="lni lni-codepen"></i>
                                <p>Barcode Generator</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-6">
                            <div class="single-mfeature wow fadeInUp" data-wow-delay=".8s">
                                <i class="lni lni-cloud-download"></i>
                                <p>PDF Export</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-6">
                            <div class="single-mfeature wow fadeInUp" data-wow-delay=".2s">
                                <i class="lni lni-keyword-research"></i>
                                <p>Dynamic Search</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-6">
                            <div class="single-mfeature wow fadeInUp" data-wow-delay=".4s">
                                <i class="lni lni-angellist"></i>
                                <p>Multilingual Support</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-6">
                            <div class="single-mfeature wow fadeInUp" data-wow-delay=".6s">
                                <i class="lni lni-database"></i>
                                <p>Database Backup</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-6">
                            <div class="single-mfeature wow fadeInUp" data-wow-delay=".8s">
                                <i class="lni lni-power-switch"></i>
                                <p>Dark mode & Style customizer</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Achievement Area -->

    <!-- Start Pricing Table Area -->
    <section id="pricing" class="pricing-table section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h3 class="wow zoomIn" data-wow-delay=".2s">pricing</h3>
                        <h2 class="wow fadeInUp" data-wow-delay=".4s">Pricing Plan</h2>
                        <p class="wow fadeInUp" data-wow-delay=".6s">We are offering two types of license for our
                            software such as Regular license and Extended license</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Single Table -->
                    <div class="single-table wow fadeInUp" data-wow-delay=".2s">
                        <!-- Table Head -->
                        <div class="table-head">
                            <h4 class="title">Regular License</h4>
                            <p>All the basics for your business. Permitted for one domain for personal use only.</p>
                            <div class="price">
                                <h2 class="amount">$39 <del>$49</del></h2>
                            </div>
                            <div class="button">
                                <a target="_blank" href="https://1.envato.market/ZQYj6R"
                                    class="btn">Buy Regular License</a>
                            </div>
                        </div>
                        <!-- End Table Head -->
                        <!-- Start Table Content -->
                        <div class="table-content">
                            <h4 class="middle-title">What's Included</h4>
                            <!-- Table List -->
                            <ul class="table-list">
                                <li><i class="lni lni-checkmark-circle"></i> Quality checked by Envato.</li>
                                <li><i class="lni lni-checkmark-circle"></i> Life time update.</li>
                                <li><i class="lni lni-checkmark-circle"></i> 6 Month support <a
                                        href="https://codecanyon.net/page/item_support_policy?_ga=2.184920874.260634607.1652244325-1968967188.1652244325"
                                        traget="__blank">(Please check here what included in support)</a></li>
                                <li><i class="lni lni-checkmark-circle"></i> For one domain for personal use only.</li>
                                <li><i class="lni lni-checkmark-circle"></i> For Personal Project only.</li>
                                <li><i class="lni lni-checkmark-circle"></i> Support (2 Business day).</li>
                                <li>
                                    <i class="lni lni-cross-circle"></i>
                                    Full Source Code.
                                </li>
                                <li><i class="lni lni-cross-circle" style="color: red;"></i> Skype Support</li>
                                <li><i class="lni lni-cross-circle" style="color: red;"></i> Anydesk/Teamviewer
                                    Support</li>
                                <li><i class="lni lni-cross-circle" style="color: red;"></i> Free installation</li>
                            </ul>
                            <!-- End Table List -->
                        </div>
                        <!-- End Table Content -->
                    </div>
                    <!-- End Single Table-->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Single Table -->
                    <div class="single-table wow fadeInUp" data-wow-delay=".4s">
                        <!-- Table Head -->
                        <div class="table-head">
                            <h4 class="title">Extended License</h4>
                            <p>All the basics for your business. Permitted for multiple domains for commercial use only.
                            </p>
                            <div class="price">
                                <h2 class="amount">$499</h2>
                            </div>
                            <div class="button">
                                <a target="_blank" href="https://1.envato.market/21V0aD"
                                    class="btn">Extended License</a>
                            </div>
                        </div>
                        <!-- End Table Head -->
                        <!-- Start Table Content -->
                        <div class="table-content">
                            <h4 class="middle-title">What's Included</h4>
                            <!-- Table List -->
                            <ul class="table-list">
                                <li><i class="lni lni-checkmark-circle"></i> Quality checked by Envato.</li>
                                <li><i class="lni lni-checkmark-circle"></i> Life time update.</li>
                                <li><i class="lni lni-checkmark-circle"></i> 6 Month support <a
                                        href="https://codecanyon.net/page/item_support_policy?_ga=2.184920874.260634607.1652244325-1968967188.1652244325"
                                        traget="__blank">(Please check here what included in support)</a></li>
                                <li><i class="lni lni-checkmark-circle"></i> For multiple domains for commercial uses.
                                </li>
                                <li><i class="lni lni-checkmark-circle"></i> For Personal & Commercial Project.</li>
                                <li><i class="lni lni-checkmark-circle"></i> Support (1 Business day).</li>
                                <li>
                                    <i class="lni lni-cross-circle"></i>
                                    Full Source Code
                                </li>
                                <li><i class="lni lni-checkmark-circle"></i> Skype Support</li>
                                <li><i class="lni lni-checkmark-circle"></i> Anydesk/Teamviewer Support</li>
                                <li><i class="lni lni-checkmark-circle"></i> Free Installation</li>
                                <li><i class="lni lni-checkmark-circle"></i> 4 Hours Free Customization Work</li>
                            </ul>
                            <!-- End Table List -->
                        </div>
                        <!-- End Table Content -->
                    </div>
                    <!-- End Single Table-->
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Single Table -->
                    <div class="single-table wow fadeInUp" data-wow-delay=".6s">
                        <!-- Table Head -->
                        <div class="table-head">
                            <h4 class="title">For any custom work</h4>
                            <p>We will be happy if we can assist you with any of your custom work. We love hearing from
                                you! Feel free to leave us a message. Use the contact form to get in touch with us.
                                We'll reply back within the next 24 hour.</p>
                            <div class="button mt-5">
                                <a href="https://codeshaper.net/contact-us" target="_blank" class="btn">Contact
                                    Us</a>
                            </div>
                        </div>
                        <!-- End Table Head -->
                        <!-- Start Table Content -->
                        <div class="table-content">
                            <h4 class="middle-title">WE WOULD LOVE TO ASSIST YOU VIA</h4>
                            <!-- Table List -->
                            <ul class="table-list">
                                <li><i class="lni lni-checkmark-circle"></i> UI & UX Design</li>
                                <li><i class="lni lni-checkmark-circle"></i> Ecommerce Solutions</li>
                                <li><i class="lni lni-checkmark-circle"></i> Web Development</li>
                                <li><i class="lni lni-checkmark-circle"></i> WordPress Development</li>
                                <li><i class="lni lni-checkmark-circle"></i> Mobile Apps Development</li>
                                <li><i class="lni lni-checkmark-circle"></i> Digital Marketing</li>
                            </ul>
                            <!-- End Table List -->
                        </div>
                        <!-- End Table Content -->
                    </div>
                    <!-- End Single Table-->
                </div>
            </div>
        </div>
    </section>
    <!--/ End Pricing Table Area -->

    <!-- Start Call To Action Area -->
    <section class="section call-action">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-12 col-12">
                    <div class="cta-content">
                        <h2 class="wow fadeInUp" data-wow-delay=".2s">View live demo to explore all the amazing
                            features of Acculance
                        </h2>
                        <div class="button wow fadeInUp" data-wow-delay=".6s">
                            <a href="/login" class="btn">View Demo</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Call To Action Area -->

    <!-- Start Footer Area -->
    <footer class="footer">
        <!-- Start Footer Top -->
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 text-center col-12">
                        <!-- Single Widget -->
                        <div class="single-footer f-about">
                            <div class="logo">
                                <a href="{{ url('/') }}">
                                    <img src="{{ asset('landing-assets/images/logo/white-logo.svg') }}"
                                        alt="#">
                                </a>
                            </div>
                            <p>Ultimate Sales, Inventory, Accounting Management System</p>
                            <ul class="social">
                                <li><a href="https://codeshaper.net" target="_blank"><i
                                            class="lni lni-website"></i></a></li>
                                <li><a href="https://www.facebook.com/Codeshaperbd" target="_blank"><i
                                            class="lni lni-facebook-filled"></i></a></li>
                                <li><a href="https://twitter.com/codeshaperbd" target="_blank"><i
                                            class="lni lni-twitter-original"></i></a></li>
                                <li><a href="https://www.instagram.com/codeshaper_int/" target="_blank"><i
                                            class="lni lni-instagram"></i></a></li>
                                <li><a href="https://www.linkedin.com/in/code-shaper-7b8a0513b" target="_blank"><i
                                            class="lni lni-linkedin-original"></i></a></li>
                                <li><a href="https://www.pinterest.com/codeshaperbd/" target="_blank"><i
                                            class="lni lni-pinterest"></i></a></li>
                            </ul>
                            <p class="copyright-text">Copyright &copy; By <a href="https://codeshaper.net/"
                                    rel="nofollow" target="_blank">Codeshaper</a>
                            </p>
                        </div>
                        <!-- End Single Widget -->
                    </div>
                </div>
            </div>
        </div>
        <!--/ End Footer Top -->
    </footer>
    <!--/ End Footer Area -->

    <!-- ========================= scroll-top ========================= -->
    <a href="#" class="scroll-top">
        <i class="lni lni-chevron-up"></i>
    </a>

    <!-- ========================= JS here ========================= -->
    <script src="{{ asset('landing-assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('landing-assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('landing-assets/js/tiny-slider.js') }}"></script>
    <script src="{{ asset('landing-assets/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('landing-assets/js/count-up.min.js') }}"></script>
    <script src="{{ asset('landing-assets/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
        //====== counter up
        var cu = new counterUp({
            start: 0,
            duration: 2000,
            intvalues: true,
            interval: 100,
            append: " ",
        });
        cu.start();

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
</body>

</html>
