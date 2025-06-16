@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Email küldés intézményeknek</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Email küldés</li>
    </ol>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    @if(session('warning'))
    <div class="alert alert-warning">
        {{ session('warning') }}
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-envelope me-1"></i>
                Tömeges email küldés
            </div>
        </div>
        <div class="card-body">
            <p>Az alábbi gomb segítségével egyszerre küldhetsz emailt minden olyan intézménynek, amely még nem töltötte ki a felmérést.</p>
            <p>Jelenleg <strong>{{ $notCompletedCount }}</strong> intézmény van, amely még nem töltötte ki a felmérést.</p>

            <form method="POST" action="{{ route('admin.emails.send-to-not-completed') }}" onsubmit="return confirm('Biztosan szeretnél emailt küldeni minden olyan intézménynek, amely még nem töltötte ki a felmérést?');">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-1"></i>
                    Email küldése az összes intézménynek, amely még nem töltötte ki a felmérést
                </button>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Intézmények listája
        </div>
        <div class="card-body">
            <table id="institutionsTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Intézmény neve</th>
                        <th>Email cím</th>
                        <th>Követőkód</th>
                        <th>Felmérés kitöltve</th>
                        <th>Fogadhat emailt</th>
                        <th>Email elküldve</th>
                        <th>Email megnyitások</th>
                        <th>Megjegyzés</th>
                        <th>Műveletek</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($institutions as $institution)
                    <tr>
                        <td>{{ $institution->name }}</td>
                        <td>{{ $institution->email }}</td>
                        <td>
                            @if($institution->tracking_code)
                                <span class="badge bg-info">{{ $institution->tracking_code }}</span>
                            @else
                                <span class="badge bg-warning">Nincs</span>
                            @endif
                        </td>
                        <td>
                            @if($institution->survey_completed)
                                <span class="badge bg-success">Igen</span>
                            @else
                                <span class="badge bg-danger">Nem</span>
                            @endif
                        </td>
                        <td>
                            @if($institution->can_receive_emails)
                                <span class="badge bg-success">Igen</span>
                            @else
                                <span class="badge bg-danger">Nem</span>
                                @if($institution->email_opt_out_reason)
                                <br><small class="text-muted">{{ Str::limit($institution->email_opt_out_reason, 30) }}</small>
                                @endif
                            @endif
                        </td>
                        <td>
                            @if($institution->email_sent)
                                <span class="badge bg-success">Igen ({{ $institution->last_email_sent_at ? $institution->last_email_sent_at->format('Y.m.d. H:i') : 'ismeretlen időpont' }})</span>
                            @else
                                <span class="badge bg-warning">Nem</span>
                            @endif
                        </td>
                        <td>
                            @if($institution->email_opens && count($institution->email_opens) > 0)
                                @php
                                    $opens = $institution->email_opens;
                                    $lastOpen = $opens[count($opens) - 1];
                                @endphp
                                <span class="badge bg-success">
                                    {{ count($institution->email_opens) }}x
                                    (utolsó: {{ \Carbon\Carbon::parse($lastOpen)->format('Y.m.d. H:i') }})
                                </span>
                            @else
                                <span class="badge bg-warning">Soha</span>
                            @endif
                        </td>
                        <td>
                            @if($institution->admin_notes)
                                <small>{{ Str::limit($institution->admin_notes, 50) }}</small>
                            @else
                                <small class="text-muted">-</small>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.emails.send-to-one', $institution->id) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm"
                                    {{ $institution->survey_completed || !$institution->can_receive_emails ? 'disabled' : '' }}
                                    title="{{ !$institution->can_receive_emails ? 'Ez az intézmény nem fogadhat emailt' : '' }}">
                                    <i class="fas fa-paper-plane"></i> Email küldése
                                </button>
                            </form>
                            <a href="{{ route('admin.institutions.edit', $institution->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-edit"></i> Szerkesztés
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#institutionsTable').DataTable({
            responsive: true,
            order: [[3, 'asc'], [0, 'asc']] // Rendezés kitöltés alapján, majd intézmény név szerint
        });
    });
</script>
@endsection
