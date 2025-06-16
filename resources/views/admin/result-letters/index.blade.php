{{-- resources/views/admin/result-letters/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Eredménylevelek küldése')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-envelope"></i>
                        Eredménylevelek küldése
                    </h3>
                </div>

                <div class="card-body">
                    <!-- Statisztikák -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h4>{{ $totalSurveys }}</h4>
                                    <p>Összes kitöltés</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h4>{{ $surveysWithValidEmail }}</h4>
                                    <p>Érvényes email címmel</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h4>{{ $surveysWithoutEmail }}</h4>
                                    <p>Email cím nélkül</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h4>{{ round(($surveysWithValidEmail / $totalSurveys) * 100, 1) }}%</h4>
                                    <p>Küldési arány</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tömeges küldés gomb -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <button type="button" class="btn btn-success btn-lg" id="sendAllBtn">
                                <i class="fas fa-paper-plane"></i>
                                Összes eredménylevél küldése ({{ $surveysWithValidEmail }} db)
                            </button>
                            <button type="button" class="btn btn-info ml-2" data-toggle="modal" data-target="#previewModal">
                                <i class="fas fa-eye"></i>
                                Levél előnézet
                            </button>
                        </div>
                    </div>

                    <!-- Intézmények listája -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Intézmény neve</th>
                                    <th>Email cím</th>
                                    <th>Kitöltés dátuma</th>
                                    <th>Segítséget kér</th>
                                    <th>Műveletek</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($validSurveys as $survey)
                                <tr id="survey-row-{{ $survey->id }}">
                                    <td>{{ $survey->id }}</td>
                                    <td>{{ $survey->institution_name }}</td>
                                    <td>
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i>
                                            {{ $survey->contact }}
                                        </span>
                                    </td>
                                    <td>{{ $survey->created_at->format('Y.m.d H:i') }}</td>
                                    <td>
                                        @if($survey->want_help === 'igen')
                                            <span class="badge badge-success">Igen</span>
                                        @elseif($survey->want_help === 'bizonytalan')
                                            <span class="badge badge-warning">Bizonytalan</span>
                                        @else
                                            <span class="badge badge-secondary">Nem</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm send-single-btn"
                                                data-id="{{ $survey->id }}"
                                                data-name="{{ $survey->institution_name }}">
                                            <i class="fas fa-envelope"></i>
                                            Küldés
                                        </button>
                                        <a href="{{ route('admin.surveys.show', $survey->id) }}"
                                           class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                            Megtekint
                                        </a>
                                        <a href="{{ route('admin.result-letters.preview', $survey->id) }}"
                                           class="btn btn-secondary btn-sm" target="_blank">
                                            <i class="fas fa-search"></i>
                                            Előnézet
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($validSurveys->isEmpty())
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Nincs érvényes email címmel rendelkező kitöltés.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Előnézet Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eredménylevél előnézet</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <strong>Tárgy:</strong> Köszönetlevél és következő lépések - Művelődési intézményi digitalizációs felmérés
                </div>
                <div style="max-height: 500px; overflow-y: auto; border: 1px solid #ddd; padding: 15px;">
                    @if($validSurveys->isNotEmpty())
                        @include('emails.survey-result', [
                            'institutionName' => $validSurveys->first()->institution_name,
                            'survey' => $validSurveys->first(),
                            'preview' => true
                        ])
                    @endif
                </div>
                <div class="mt-3">
                    <strong>Csatolmány:</strong> Művelődési_Intézmények_Digitalizációs_Felmérése_2025.pdf
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Bezárás</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Tömeges küldés
    $('#sendAllBtn').click(function() {
        if (!confirm('Biztosan el szeretné küldeni az eredményleveleket mind a {{ $surveysWithValidEmail }} intézménynek?')) {
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Küldés folyamatban...');

        $.ajax({
            url: '{{ route("admin.result-letters.send-all") }}',
            type: 'POST',
            data: {
                '_token': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    btn.html('<i class="fas fa-check"></i> Elküldve (' + response.sent_count + ' db)');

                    // Ha voltak hibák, megjeleníti
                    if (response.error_count > 0) {
                        toastr.warning('Néhány levél küldése sikertelen volt. Ellenőrizze a logokat.');
                    }
                } else {
                    toastr.error(response.message);
                    btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Összes eredménylevél küldése');
                }
            },
            error: function(xhr) {
                toastr.error('Hiba történt a küldés során');
                btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Összes eredménylevél küldése');
            }
        });
    });

    // Egyedi küldés
    $('.send-single-btn').click(function() {
        const surveyId = $(this).data('id');
        const institutionName = $(this).data('name');

        if (!confirm(`Biztosan el szeretné küldeni az eredménylevelet a(z) "${institutionName}" intézménynek?`)) {
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Küldés...');

        $.ajax({
            url: '{{ route("admin.result-letters.send", ":id") }}'.replace(':id', surveyId),
            type: 'POST',
            data: {
                '_token': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    btn.html('<i class="fas fa-check"></i> Elküldve').removeClass('btn-primary').addClass('btn-success');
                } else {
                    toastr.error(response.message);
                    btn.prop('disabled', false).html('<i class="fas fa-envelope"></i> Küldés');
                }
            },
            error: function(xhr) {
                toastr.error('Hiba történt a küldés során');
                btn.prop('disabled', false).html('<i class="fas fa-envelope"></i> Küldés');
            }
        });
    });
});
</script>
@endsection
