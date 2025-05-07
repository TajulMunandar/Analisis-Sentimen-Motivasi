@extends('dashboard.layout.main')
@section('content')
    <div class="row">
        <div class="col-12">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @elseif (session()->has('failed'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('failed') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col col-lg-2">
            <div>
                <label for="keyword" class="form-label text-dark">Keyword</label>
                <input type="name" class="form-control @error('keyword') is-invalid @enderror" id="keyword"
                    name="keyword" autofocus required>
            </div>
        </div>
        <div class="col mt-4 pt-1">
            <button class="btn btn-info" id="scrape"><i class="fa-regular fa-plus me-2"></i>Scrape</button>
            <a class="btn btn-success float-end" href="{{ route('exportcsv') }}"><i
                    class="fa-regular fa-download me-2"></i>Export
                Data</a>
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-12">
            <div class="card mt-2">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="myTable" class="table w-100 table-borderless table-striped border-top border-bottom">
                            <thead class="align-middle border-bottom">
                                <tr>
                                    <th>NO</th>
                                    <th>TWEET ID</th>
                                    <th>USER ID</th>
                                    <th>USERNAME</th>
                                    <th>TEXT</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @foreach ($tweets as $tweet)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $tweet->tweet_id }}</td>
                                        <td>{{ $tweet->user_id }}</td>
                                        <td>{{ $tweet->username }}</td>
                                        <td>{{ $tweet->text }}</td>
                                    </tr>
                                @endforeach --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


{{-- @section('script')
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();

            $("#scrape").click(function() {
                let button = $(this);
                button.prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> Scraping...');

                $.ajax({
                    url: "http://127.0.0.1:5000/scrapping", // Ganti dengan URL Flask jika berbeda
                    type: "GET",
                    success: function(response) {
                        alert("Scraping berhasil!");
                        location.reload(); // Reload halaman untuk menampilkan data terbaru
                    },
                    error: function(xhr, status, error) {
                        alert("Scraping gagal! Cek server Flask.");
                        console.error(error);
                    },
                    complete: function() {
                        button.prop("disabled", false).html(
                            '<i class="fa-regular fa-plus me-2"></i>Scrape');
                    }
                });
            });
        });
    </script>
@endsection --}}
