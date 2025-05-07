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

    <button class="btn btn-info" id="process"><i class="fa-regular fa-plus me-2"></i>Process</button>

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
                                    <th>CLEAN TEXT</th>
                                    <th>TOKENIZED</th>
                                    <th>FEATURE VECTOR</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($processes as $process)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $process->Tweet->text }}</td>
                                        <td>{{ $process->clean_text }}</td>
                                        <td>{{ $process->tokenized }}</td>
                                        <td>{{ $process->feature_vector }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
            $("#process").click(function() {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Mohon tunggu, sedang melakukan preprocessing data.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "http://127.0.0.1:5000/preprocess", // API Flask
                    type: "GET",
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Preprocessing selesai!',
                        }).then(() => {
                            location
                        .reload(); // Refresh halaman setelah preprocessing selesai
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal melakukan preprocessing. Cek kembali server Flask!',
                        });
                    }
                });
            });
        });
    </script>
@endsection
