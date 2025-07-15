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

    <button class="btn btn-info" id="analize"><i class="fa-regular fa-plus me-2"></i>Analize</button>

    <div class="card mt-3">
        <div class="card-body">
            <p><b>Accuracy:</b> <span id="accuracy">-</span></p>
            <div class="row">
                <div class="col">
                    <p><b>Confusion Matrix:</b></p>
                    <div id="confusion-matrix"></div>
                </div>
                <div class="col">
                    <p><b>Classification Report:</b></p>
                    <div id="classification-report"></div>
                </div>
            </div>
        </div>
    </div>


    <div class="row ">
        <div class="col-12">
            <div class="card mt-2">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="myTable" class="table w-100 table-borderless table-striped border-top border-bottom">
                            <thead class="align-middle border-bottom">
                                <tr>
                                    <th>NO</th>
                                    <th>TEXT TWITTER</th>
                                    <th>TEXT PREPROCESSING</th>
                                    <th>SENTIMENT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sentiments as $sentiment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $sentiment->Tweet->text }}</td>
                                        <td>{{ $sentiment->Tweet->Preprocessing->clean_text }}</td>
                                        <td>
                                            @if ($sentiment->sentiment == 'positif')
                                                <span class="badge bg-success">Positif</span>
                                            @elseif ($sentiment->sentiment == 'negatif')
                                                <span class="badge bg-danger">Negatif</span>
                                            @elseif($sentiment->sentiment == 'netral')
                                                <span class="badge bg-warning">Netral</span>
                                            @else
                                                <span class="badge bg-secondary">Tidak Diketahui</span>
                                            @endif
                                        </td>
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

            $("#analize").click(function() {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Mohon tunggu, sedang melakukan analisis data sentimen.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "http://127.0.0.1:5000/analyze-sentiment", // API Flask
                    type: "GET",
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Analisis sentimen selesai!',
                        });

                        // Update Accuracy & F1 Score
                        $("#accuracy").text((response.evaluation.accuracy * 100).toFixed(2) +
                            "%");

                        // Generate Confusion Matrix Table
                        let labels = ['Positif', 'Netral',
                            'Negatif'
                        ]; // urut sesuai label_encoder.classes_
                        let confMatrixHtml = "<table class='table table-bordered table-sm'>";
                        confMatrixHtml += "<thead><tr><th></th>";

                        labels.forEach(label => {
                            confMatrixHtml += `<th>Pred: ${label}</th>`;
                        });
                        confMatrixHtml += "</tr></thead><tbody>";

                        response.evaluation.confusion_matrix.forEach((row, i) => {
                            confMatrixHtml += `<tr><th>Actual: ${labels[i]}</th>`;
                            row.forEach(cell => {
                                confMatrixHtml += `<td>${cell}</td>`;
                            });
                            confMatrixHtml += "</tr>";
                        });

                        confMatrixHtml += "</tbody></table>";

                        // Masukkan ke dalam div confusion-matrix
                        $("#confusion-matrix").html(confMatrixHtml);
                        let reportHtml = `
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>Label</th>
                <th>Precision</th>
                <th>Recall</th>
                <th>F1-Score</th>

            </tr>
        </thead>
        <tbody>
`;

                        let report = response.evaluation.report;
                        for (let label in report) {
                            if (['accuracy', 'macro avg', 'weighted avg'].includes(label))
                                continue; // Skip summary

                            reportHtml += `
        <tr>
            <td>${label}</td>
            <td>${(report[label].precision * 100).toFixed(2)}%</td>
<td>${(report[label].recall * 100).toFixed(2)}%</td>
<td>${(report[label]["f1-score"] * 100).toFixed(2)}%</td>

        </tr>
    `;
                        }

                        reportHtml += `</tbody></table>`;
                        $("#classification-report").html(reportHtml);
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal melakukan analisis. Cek kembali server Flask!',
                        });
                    }
                });
            });
        });
    </script>
@endsection
