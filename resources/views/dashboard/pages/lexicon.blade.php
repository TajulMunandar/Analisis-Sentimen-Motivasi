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

    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#importModal">
        <i class="fa-regular fa-download me-2"></i>Import Data
    </button>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card mt-2">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="myTable" class="table w-100 table-borderless table-striped border-top border-bottom">
                            <thead class="align-middle border-bottom">
                                <tr>
                                    <th>NO</th>
                                    <th>WORD</th>
                                    <th>POLARITY</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lexicons as $lexicon)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $lexicon->word }}</td>
                                        <td>{{ $lexicon->polarity }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Import Data -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Lexicon Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('lexicon.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tsv_file" class="form-label">Upload TSV File</label>
                            <input type="file" class="form-control" name="tsv_file" accept=".tsv,.txt" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload & Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
@endsection
