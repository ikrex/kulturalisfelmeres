@extends('layouts.admin')

@section('title', 'Statisztikák')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h2 class="m-0 font-weight-bold">Felmérési Statisztikák</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Statisztikai kártyák -->
    <div class="row mb-4">
        <!-- Befejezett kérdőívek -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Befejezett kérdőívek</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completedSurveysCount'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Folyamatban lévő kérdőívek -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Folyamatban lévő kérdőívek</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['inProgressSurveysCount'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Intézmények száma -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Összes intézmény</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['institutionsCount'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kitöltési arány -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Kitöltési arány</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $stats['completionPercentage'] }}%</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $stats['completionPercentage'] }}%"
                                            aria-valuenow="{{ $stats['completionPercentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 small">
                                {{ $stats['completedInstitutionsCount'] }} / {{ $stats['institutionsCount'] }} intézmény
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kérdőív kitöltési grafikon -->
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Kérdőív kitöltések az elmúlt 12 hónapban</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="monthlySurveysChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Körgrafikon - Segítségkérés -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Segítségkérési igény</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="helpRequestPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Segítséget kér ({{ $stats['wantHelpCount'] }})
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-warning"></i> Bizonytalan ({{ $stats['probalyHelpCount'] }})
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-danger"></i> Nem kér segítséget ({{ $stats['completedSurveysCount'] - $stats['wantHelpCount'] - $stats['probalyHelpCount'] }})
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top rendezvény szoftverek és problémák -->
    <div class="row">
        <!-- Rendezvény szoftverek -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Legnépszerűbb rendezvény szoftverek</h6>
                </div>
                <div class="card-body">
                    @if(count($stats['eventSoftwareStats']) > 0)
                        @foreach($stats['eventSoftwareStats'] as $software => $count)
                            <h4 class="small font-weight-bold">{{ $software }}
                                <span class="float-right">{{ $count }} intézmény</span>
                            </h4>
                            <div class="progress mb-4">
                                <div class="progress-bar" role="progressbar" style="width: {{ ($count / $stats['completedSurveysCount']) * 100 }}%"
                                    aria-valuenow="{{ $count }}" aria-valuemin="0" aria-valuemax="{{ $stats['completedSurveysCount'] }}"></div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-center">Nincs elegendő adat a megjelenítéshez</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Információáramlás problémák -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Leggyakoribb információáramlás problémák</h6>
                </div>
                <div class="card-body">
                    @if(count($stats['infoFlowIssuesStats']) > 0)
                        @foreach($stats['infoFlowIssuesStats'] as $issue => $count)
                            <h4 class="small font-weight-bold">{{ $issue }}
                                <span class="float-right">{{ $count }} intézmény</span>
                            </h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ ($count / $stats['completedSurveysCount']) * 100 }}%"
                                    aria-valuenow="{{ $count }}" aria-valuemin="0" aria-valuemax="{{ $stats['completedSurveysCount'] }}"></div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-center">Nincs elegendő adat a megjelenítéshez</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Export gomb -->
    <div class="row">
        <div class="col-12 mb-4 text-center">
            <a href="{{ route('admin.surveys.export') }}" class="btn btn-success btn-lg">
                <i class="fas fa-file-excel"></i> Kérdőív adatok exportálása Excel formátumban
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Kérdőív kitöltések az elmúlt 12 hónapban - Vonaldiagram
    var ctx = document.getElementById("monthlySurveysChart");
    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($stats['chartLabels']) !!},
            datasets: [{
                label: "Kitöltések",
                lineTension: 0.3,
                backgroundColor: "rgba(78, 115, 223, 0.05)",
                borderColor: "rgba(78, 115, 223, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "rgba(78, 115, 223, 1)",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                data: {!! json_encode($stats['chartData']) !!},
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                xAxes: [{
                    time: {
                        unit: 'date'
                    },
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 12
                    }
                }],
                yAxes: [{
                    ticks: {
                        maxTicksLimit: 5,
                        padding: 10,
                        beginAtZero: true,
                        callback: function(value, index, values) {
                            return value;
                        }
                    },
                    gridLines: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }],
            },
            legend: {
                display: false
            },
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 10
            }
        }
    });

    // Segítségkérés körgrafikon
    var ctx2 = document.getElementById("helpRequestPieChart");
    var myPieChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ["Segítséget kér", "Bizonytalan", "Nem kér segítséget"],
            datasets: [{
                data: [
                    {{ $stats['wantHelpCount'] }},
                    {{ $stats['probalyHelpCount'] }},
                    {{ $stats['completedSurveysCount'] - $stats['wantHelpCount'] - $stats['probalyHelpCount'] }}
                ],
                backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b'],
                hoverBackgroundColor: ['#17a673', '#dda20a', '#e02d1b'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: true,
                position: 'bottom'
            },
            cutoutPercentage: 80,
        },
    });
</script>
@endsection
