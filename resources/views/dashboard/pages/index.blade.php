@extends('dashboard.layout.main')

@section('content')
    <!--  Row 1 -->
    <div class="row">
        <div class="col-lg-6 d-flex ">
            <div class="row w-100 d-flex">
                <div class="col-sm-12 col-xl-12">
                    <div class="card overflow-hidden rounded-2">
                        <div class="card-body pt-3 p-4">
                            <h6 class="fw-semibold fs-4">Total Data Tweets</h6>
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="fw-semibold fs-4 mb-0">{{ $tweets }} </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-xl-12">
                    <div class="card overflow-hidden rounded-2">
                        <div class="card-body pt-3 p-4">
                            <h6 class="fw-semibold fs-4">Total Data Lexicon</h6>
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="fw-semibold fs-4 mb-0">{{ $lexicon }} </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Yearly Breakup -->
                    <div class="card overflow-hidden">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-2 fw-semibold">Sentiment</h5>
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h2 class="fw-semibold mb-3">36</h2>
                                    <div class="d-flex align-items-center">
                                        <div class="me-4">
                                            <span class="round-8 bg-success rounded-circle me-2 d-inline-block"></span>
                                            <span class="fs-2">positive</span>
                                        </div>
                                        <div class="me-4">
                                            <span class="round-8 bg-warning rounded-circle me-2 d-inline-block"></span>
                                            <span class="fs-2">neutral</span>
                                        </div>
                                        <div>
                                            <span class="round-8 bg-danger rounded-circle me-2 d-inline-block"></span>
                                            <span class="fs-2">negative</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="d-flex justify-content-center">
                                        <div id="breakup"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
