<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Analisis Sentimen SVM</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Custom Google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@100;200;300;400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="{{ asset('landing/css/styles.css') }}" rel="stylesheet" />
</head>

<body class="d-flex flex-column h-100">
    <main class="flex-shrink-0">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
            <div class="container px-5">
                <a class="navbar-brand" href="/"><span class="fw-bolder text-primary">Analisis Sentimen
                        SVM</span></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation"><span
                        class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 small fw-bolder">
                        <li class="nav-item"><a class="nav-link btn btn-outline-primary " href="/login">login</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Header-->
        <header class="py-5">
            <div class="container px-5 pb-5">
                <div class="row gx-5 align-items-center">
                    <div class="col-xxl-5">
                        <!-- Header text content-->
                        <div class="text-center text-xxl-start">
                            <div class="badge bg-gradient-primary-to-secondary text-white mb-4">
                                <div class="text-uppercase">#Motivasi at X Platform</div>
                            </div>
                            <div class="fs-5 fw-light text-muted">Here's a quick insight of public sentiment analysis
                                results from Twitter data</div>
                            <h1 class="display-6 fw-bolder mb-5"><span class="text-gradient d-inline">Sentiment Analysis
                                    Summary</span></h1>
                            <div class="row gx-5 justify-content-center">
                                <div class="col-lg-4 mb-5">
                                    <div class="card h-100 shadow border-0 text-center">
                                        <div class="card-body p-4">
                                            <h5 class="card-title text-success fs-6">Positive</h5>
                                            <p class="card-text display-6 fw-bold">{{ $positive }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 mb-5">
                                    <div class="card h-100 shadow border-0 text-center">
                                        <div class="card-body p-4">
                                            <h5 class="card-title text-danger fs-6">Negative</h5>
                                            <p class="card-text display-6 fw-bold">{{ $negative }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 mb-5">
                                    <div class="card h-100 shadow border-0 text-center">
                                        <div class="card-body p-4">
                                            <h5 class="card-title text-secondary fs-6">Neutral</h5>
                                            <p class="card-text display-6 fw-bold">{{ $neutral }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <p class="text-muted">Total Tweets Analyzed: <strong>{{ $total }}</strong></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-7">
                        <!-- Header profile picture-->
                        <div class="d-flex justify-content-center mt-5 mt-xxl-0">
                            <div class="profile bg-gradient-primary-to-secondary">
                                <!-- TIP: For best results, use a photo with a transparent background like the demo example below-->
                                <!-- Watch a tutorial on how to do this on YouTube (link)-->
                                <img class="profile-img" src="{{ asset('landing/assets/profile.png') }}"
                                    alt="..." />
                                <div class="dots-1">

                                </div>
                                <div class="dots-2">

                                </div>
                                <div class="dots-3">

                                </div>
                                <div class="dots-4">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

    </main>

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="{{ asset('landing/js/scripts.js') }}"></script>
</body>

</html>
