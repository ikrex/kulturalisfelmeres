@extends('admin.layout')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Statisztikák</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Statisztikák</li>
    </ol>

    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h2>{{ $totalSurveys }}</h2>
                    <p>Összes kérdőív</p>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.surveys') }}">Részletek</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <h2>{{ $totalResponses }}</h2>
                    <p>Összes kitöltés</p>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.responses') }}">Részletek</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <h2>{{ $totalUsers }}</h2>
                    <p>Regisztrált felhasználók</p>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.users') }}">Részletek</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <h2>{{ $averageCompletionTime }}</h2>
                    <p>Átlagos kitöltési idő (perc)</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Kitöltések az elmúlt 30 napban
                </div>
                <div class="card-body">
                    <canvas id="responsesChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Kérdőívek kitöltési aránya
                </div>
                <div class="card-body">
                    <canvas id="surveysChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Kérdőívek statisztikái
        </div>
        <div class="card-body">
            <table id="surveysTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Kérdőív neve</th>
                        <th>Létrehozva</th>
                        <th>Kitöltések száma</th>
                        <th>Átlagos kitöltési idő (perc)</th>
                        <th>Befejezési arány (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($surveyStats as $stat)
                    <tr>
                        <td>{{ $stat->name }}</td>
                        <td>{{ $stat->created_at->format('Y-m-d') }}</td>
                        <td>{{ $stat->responses_count }}</td>
                        <td>{{ round($stat->average_time / 60, 1) }}</td>
                        <td>{{ $stat->completion_rate }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Responses per day chart
        var responsesCtx = document.getElementById("responsesChart");
        var responsesChart = new Chart(responsesCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($responseChartLabels) !!},
                datasets: [{
                    label: "Kitöltések",
                    lineTension: 0.3,
                    backgroundColor: "rgba(2,117,216,0.2)",
                    borderColor: "rgba(2,117,216,1)",
                    pointRadius: 5,
                    pointBackgroundColor: "rgba(2,117,216,1)",
                    pointBorderColor: "rgba(255,255,255,0.8)",
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(2,117,216,1)",
                    pointHitRadius: 50,
                    pointBorderWidth: 2,
                    data: {!! json_encode($responseChartData) !!},
                }],
            },
            options: {
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'date'
                        },
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            min: 0,
                            maxTicksLimit: 5
                        },
                        gridLines: {
                            color: "rgba(0, 0, 0, .125)",
                        }
                    }],
                },
                legend: {
                    display: false
                }
            }
        });

        // Surveys completion rate chart
        var surveysCtx = document.getElementById("surveysChart");
        var surveysChart = new Chart(surveysCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($surveyChartLabels) !!},
                datasets: [{
                    data: {!! json_encode($surveyChartData) !!},
                    backgroundColor: {!! json_encode($surveyChartColors) !!},
                }],
            },
        });
    });
</script>
@endsection
