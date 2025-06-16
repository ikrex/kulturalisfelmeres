@extends('layouts.admin')

@section('title', 'Folyamatban lévő kitöltés megtekintése')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                    <h2 class="m-0 font-weight-bold">Folyamatban lévő kitöltés #{{ $survey->id }}</h2>
                    <div>
                        <a href="{{ route('admin.temporary-surveys') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Vissza a listához
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Általános adatok</div>
                                            <div class="mb-0">
                                                <p><strong>Intézmény neve:</strong> {{ $survey->institution_name ?? 'Még nincs megadva' }}</p>
                                                <p><strong>Rendezvény szoftver:</strong> {{ $survey->event_software ?? 'Még nincs megadva' }}</p>
                                                <p><strong>Kapcsolat:</strong> {{ $survey->contact ?? 'Még nincs megadva' }}</p>
                                                <p><strong>Segítséget szeretne:</strong> {{ $survey->want_help ?? 'Még nincs megadva' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-building fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Kitöltés adatai</div>
                                            <div class="mb-0">
                                                <p><strong>Azonosító:</strong> #{{ $survey->id }}</p>
                                                <p><strong>UUID:</strong> {{ $survey->uuid }}</p>
                                                <p><strong>Elkezdve:</strong> {{ $survey->created_at->format('Y.m.d. H:i') }}</p>
                                                <p><strong>Utolsó aktivitás:</strong> {{ $survey->updated_at->format('Y.m.d. H:i') }}</p>
                                                <p><strong>IP cím:</strong> {{ $survey->ip_address }}</p>
                                                <p><strong>Státusz:</strong> <span class="badge bg-warning">Folyamatban</span></p>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Eddigi válaszok</h6>
                            <div class="alert alert-info mb-0 py-1">
                                <i class="fas fa-info-circle"></i> A kitöltés még folyamatban van, az adatok nem véglegesek.
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="font-weight-bold">Problémák és nehézségek</h5>
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th width="30%">Statisztikai problémák</th>
                                            <td>{{ $survey->statistics_issues ?? 'Még nincs megadva' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Kommunikációs problémák</th>
                                            <td>{{ $survey->communication_issues ?? 'Még nincs megadva' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Rendezvény átláthatóság</th>
                                            <td>{{ $survey->event_transparency ?? 'Még nincs megadva' }}</td>
                                        </tr>
                                        @if(isset($survey->info_flow_issues))
                                        <tr>
                                            <th>Információáramlás problémák</th>
                                            <td>
                                                {{ $survey->info_flow_issues ?? 'Még nincs megadva' }}
                                                @if($survey->info_flow_issues_other_text)
                                                    <br><strong>Egyéb magyarázat:</strong> {{ $survey->info_flow_issues_other_text }}
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <h5 class="font-weight-bold">Előnyök és lehetőségek</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        @if(isset($survey->event_tracking_benefits))
                                        <tr>
                                            <th width="30%">Rendezvény követés előnyei</th>
                                            <td>
                                                {{ $survey->event_tracking_benefits ?? 'Még nincs megadva' }}
                                                @if($survey->event_tracking_benefits_other_text)
                                                    <br><strong>Egyéb magyarázat:</strong> {{ $survey->event_tracking_benefits_other_text }}
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                        @if(isset($survey->stats_benefits))
                                        <tr>
                                            <th>Statisztikai előnyök</th>
                                            <td>
                                                {{ $survey->stats_benefits ?? 'Még nincs megadva' }}
                                                @if($survey->stats_benefits_other_text)
                                                    <br><strong>Egyéb magyarázat:</strong> {{ $survey->stats_benefits_other_text }}
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
