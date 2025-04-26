@extends('layouts.admin')

@section('title', 'Kérdőívek listája')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                    <h2 class="m-0 font-weight-bold">Kérdőívek listája</h2>
                    <div>
                        <a href="{{ route('admin.surveys.export') }}" class="btn btn-success">
                            <i class="fas fa-file-export"></i> Exportálás CSV-be
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="surveysTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Intézmény neve</th>
                                    <th>Rendezvény szoftver</th>
                                    <th>Kapcsolat</th>
                                    <th>Létrehozva</th>
                                    <th>Segítséget szeretne</th>
                                    <th>Műveletek</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($surveys as $survey)
                                <tr>
                                    <td>{{ $survey->id }}</td>
                                    <td>{{ $survey->institution_name }}</td>
                                    <td>{{ $survey->event_software }}</td>
                                    <td>{{ $survey->contact ?? 'Nincs megadva' }}</td>
                                    <td>{{ $survey->created_at->format('Y.m.d. H:i') }}</td>
                                    <td>{{ $survey->want_help }}</td>
                                    <td>
                                        <a href="{{ route('admin.surveys.show', $survey->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Megtekintés
                                        </a>
                                        <form action="{{ route('admin.surveys.destroy', $survey->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Biztosan törölni szeretné ezt a kérdőívet?')">
                                                <i class="fas fa-trash"></i> Törlés
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Nincs még kitöltött kérdőív.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $surveys->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#surveysTable').DataTable({
            "paging": false,
            "info": false,
            "order": [[ 0, "desc" ]],
            "language": {
                "search": "Keresés:",
                "zeroRecords": "Nincs találat",
            }
        });
    });
</script>
@endsection
