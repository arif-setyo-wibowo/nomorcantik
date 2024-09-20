<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>MultiShop - Online Shop Website Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">  

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="assets/lib/animate/animate.min.css" rel="stylesheet">
    <link href="assets/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<style>
    .table {
        display: table;
        width: 100%;
    }

    .table .row {
        display: table-row;
    }

    .table .cell {
        display: table-cell;
        padding: 5px;
        border: 1px solid #000; /* Optional for border like a table */
        text-align: center; /* Center content */
        vertical-align: middle; /* Center vertically */
    }

    .cell img {
        max-width: 30%; /* Ensure image doesn't exceed its container width */
        height: auto; /* Maintain aspect ratio */
        object-fit: contain; /* Prevent distortion */
    }

    /* Make sure the image adjusts properly for smaller screens */
    @media (max-width: 600px) {
        .cell img {
            width: 50px; /* Adjust image width for smaller screens */
        }
    }

      /* By default, hide the search form */
      .search-mobile {
        display: none;
    }

    /* Show the search form only on screens smaller than 768px */
    @media (max-width: 767px) {
        .search-mobile {
            display: block;
        }
    }
</style>
<body>
     <!-- Topbar Start -->
     <div class="container-fluid">
        <div class="row align-items-center bg-light py-3 px-xl-5 d-none d-lg-flex">
            <div class="col-lg-4">
                <a href="" class="text-decoration-none">
                    <span class="h1 text-uppercase text-primary bg-light px-2">Nomor</span>
                    <span class="h1 text-uppercase text-light bg-primary px-2 ml-n1">cantik</span>
                </a>
            </div>
            <div class="col-lg-4 col-6 text-left">
                <form action="">
                    <div class="input-group">
                        <select class="custom-select" id="search-category" style="max-width: 150px;">
                            <option value="all" selected>Operator</option>
                            <option value="electronics">Electronics</option>
                            <option value="fashion">Fashion</option>
                            <option value="books">Books</option>
                            <option value="furniture">Furniture</option>
                            <!-- Add more categories as needed -->
                        </select>
                        <input type="text" class="form-control" placeholder="Search for products">
                        <div class="input-group-append">
                            <span class="input-group-text bg-transparent text-primary">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-lg-4 col-6 text-right">
                <p class="m-0">Customer Service</p>
                <h5 class="m-0"><a href="https://api.whatsapp.com/send?phone=&amp;text=Hallo Mau pesan nomer"><img src="assets/img/wa-button.png" style=" max-width: 20%;"></a></h5>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <div class="container-fluid bg-light mb-15">
        <div class="row px-xl-5">
            <div class="col-lg-12">
                <nav class="navbar navbar-expand-lg bg-ligt navbar-dark py-3 py-lg-0 px-0">
                    <a href="" class="text-decoration-none d-block d-lg-none">
                        <span class="h1 text-uppercase text-dark bg-light px-2">Nomor</span>
                        <span class="h1 text-uppercase text-light bg-primary px-2 ml-n1">Cantik</span>
                    </a>
                </nav>
            </div>
        </div>
    </div>
    <div class="row search-mobile mt-0 mb-1">
        <div class="container-fluid">
            <div class="row px-xl-5">
                <div class="col-12 p-4">
                    <form action="">
                        <div class="input-group">
                            <select class="custom-select" id="search-category" style="max-width: 150px;">
                                <option value="all" selected>Operator</option>
                                <option value="electronics">Electronics</option>
                                <option value="fashion">Fashion</option>
                                <option value="books">Books</option>
                                <option value="furniture">Furniture</option>
                                <!-- Add more categories as needed -->
                            </select>
                            <input type="text" class="form-control" placeholder="Search for products">
                            <div class="input-group-append">
                                <span class="input-group-text bg-transparent text-primary">
                                    <i class="fa fa-search"></i>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Start -->
    <div class="container-fluid mt-4">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" style="text-decoration:none;" href="index.php"><button class="btn btn-secondary">Halaman Utama</button></a>
                </nav>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->


    <!-- Shop Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <!-- Shop Sidebar Start -->
            <div class="col-lg-3 col-md-4">
                <!-- Price Start -->
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Filter dari harga</span></h5>
                <div class="bg-light p-4 mb-30">
                    <form>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" checked id="price-all">
                            <label class="custom-control-label" for="price-all">All Price</label>
                            <span class="badge border font-weight-normal">1000</span>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="price-1">
                            <label class="custom-control-label" for="price-1">$0 - $100</label>
                            <span class="badge border font-weight-normal">150</span>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="price-2">
                            <label class="custom-control-label" for="price-2">$100 - $200</label>
                            <span class="badge border font-weight-normal">295</span>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="price-3">
                            <label class="custom-control-label" for="price-3">$200 - $300</label>
                            <span class="badge border font-weight-normal">246</span>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="price-4">
                            <label class="custom-control-label" for="price-4">$300 - $400</label>
                            <span class="badge border font-weight-normal">145</span>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between">
                            <input type="checkbox" class="custom-control-input" id="price-5">
                            <label class="custom-control-label" for="price-5">$400 - $500</label>
                            <span class="badge border font-weight-normal">168</span>
                        </div>
                    </form>
                </div>
                <!-- Price End -->

                <!-- Color Start -->
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Filter dari operator</span></h5>
                <div class="bg-light p-4 mb-30">
                    <form>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" checked id="color-all">
                            <label class="custom-control-label" for="color-all">All Color</label>
                            <span class="badge border font-weight-normal">1000</span>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="color-1">
                            <label class="custom-control-label" for="color-1">Black</label>
                            <span class="badge border font-weight-normal">150</span>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="color-2">
                            <label class="custom-control-label" for="color-2">White</label>
                            <span class="badge border font-weight-normal">295</span>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="color-3">
                            <label class="custom-control-label" for="color-3">Red</label>
                            <span class="badge border font-weight-normal">246</span>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="color-4">
                            <label class="custom-control-label" for="color-4">Blue</label>
                            <span class="badge border font-weight-normal">145</span>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between">
                            <input type="checkbox" class="custom-control-input" id="color-5">
                            <label class="custom-control-label" for="color-5">Green</label>
                            <span class="badge border font-weight-normal">168</span>
                        </div>
                    </form>
                </div>
                <!-- Color End -->

            </div>
            <!-- Shop Sidebar End -->


            <!-- Shop Product Start -->
            <div class="col-lg-6 col-md-8">
                <div class="row pb-3">
                    <div class="col-12 pb-1">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="ml-2">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-sm-6 pb-1 bg-light">
                        <img class="img-fluid m-5 text-center" src="assets/img/coba.png" alt="">
                        <div class="product-item bg-light mb-4">
                        

                            <div class="table">
                                <div class="row">
                                    <div class="cell">1</div>
                                    <div class="cell"><h3 class="text-danger m-0">01289312319</h3></div>
                                    <div class="cell"><h5 class="text-success m-0">7jt</h5></div>
                                    <div class="cell"><a href=""><img src="assets/img/wa.png" alt="WhatsApp"></a></div>
                                </div>
                                <div class="row">
                                    <div class="cell">2</div>
                                    <div class="cell"><h3 class="text-danger m-0">081810540412</h3></div>
                                    <div class="cell"><h5 class="text-success m-0">7jt</h5></div>
                                    <div class="cell"><a href=""><img src="assets/img/wa.png" alt="WhatsApp"></a></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="col-md-6 col-sm-6 pb-1 bg-light">
                        <img class="img-fluid m-5 text-center" src="assets/img/coba.png" alt="">
                        <div class="product-item bg-light mb-4">
                            <div class="table">
                                <div class="row">
                                    <div class="cell">1</div>
                                    <div class="cell"><h3 class="text-danger m-0">01289312319</h3></div>
                                    <div class="cell"><h5 class="text-success m-0">7jt</h5></div>
                                    <div class="cell"><a href=""><img src="assets/img/wa.png" alt="WhatsApp"></a></div>
                                </div>
                                <div class="row">
                                    <div class="cell">2</div>
                                    <div class="cell"><h3 class="text-danger m-0">081810540412</h3></div>
                                    <div class="cell"><h5 class="text-success m-0">7jt</h5></div>
                                    <div class="cell"><a href=""><img src="assets/img/wa.png" alt="WhatsApp"></a></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Shop Product End -->

            <div class="col-lg-3 col-md-4">
                <!-- Price Start -->
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Informasi</span></h5>
                <div class="bg-light p-4 mb-30">

                </div>
                <!-- Price End -->

                <!-- Color Start -->
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">cara pembayaran</span></h5>
                <div class="bg-light p-4 mb-30">
                <!-- Color End -->

            </div>
            <!-- Shop Sidebar End -->
        </div>
    </div>
    <!-- Shop End -->


    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-secondary mt-5 pt-5">
        <div class="row px-xl-5 pt-5">
            <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
                <h5 class="text-secondary text-uppercase mb-4">Get In Touch</h5>
                <p class="mb-4">No dolore ipsum accusam no lorem. Invidunt sed clita kasd clita et et dolor sed dolor. Rebum tempor no vero est magna amet no</p>
                <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>123 Street, New York, USA</p>
                <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>info@example.com</p>
                <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i><a href="https://api.whatsapp.com/send?phone=6288210001000&amp;text=www.nomorcantik.com"><img src="assets/img/wa-button.png" style=" max-width: 20%;"></a></p>
            </div>
        </div>
        <div class="row border-top mx-xl-5 py-4" style="border-color: rgba(256, 256, 256, .1) !important;">
            <div class="col-md-6 px-xl-0">
                <p class="mb-md-0 text-center text-md-left text-secondary">
                    &copy; <a class="text-primary" href="#">ITBOY</a>. All Rights Reserved. 
                </p>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="assets/lib/easing/easing.min.js"></script>
    <script src="assets/lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Contact Javascript File -->
    <script src="assets/mail/jqBootstrapValidation.min.js"></script>
    <script src="assets/mail/contact.js"></script>

    <!-- Template Javascript -->
    <script src="assets/js/main.js"></script>
</body>

</html>