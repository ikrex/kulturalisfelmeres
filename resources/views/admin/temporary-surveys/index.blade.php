@extends('layouts.admin')

@section('title', 'Folyamatban lévő kitöltések')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                    <h2 class="m-0 font-weight-bold">Folyamatban lévő kitöltések</h2>
                </div>
                <div class="card-body">
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

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tempSurveysTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>UUID</th>
                                    <th>Intézmény neve</th>
                                    <th>Rendezvény szoftver</th>
                                    <th>IP cím</th>
                                    <th>Elkezdve</th>
                                    <th>Utolsó aktivitás</th>
                                    <th>Műveletek</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($surveys as $survey)
                                <tr>
                                    <td>{{ $survey->id }}</td>
                                    <td><small>{{ $survey->uuid }}</small></td>
                                    <td>{{ $survey->institution_name ?? 'Még nincs megadva' }}</td>
                                    <td>{{ $survey->event_software ?? 'Még nincs megadva' }}</td>
                                    <td>{{ $survey->ip_address }}</td>
                                    <td>{{ $survey->created_at->format('Y.m.d. H:i') }}</td>
                                    <td>{{ $survey->updated_at->format('Y.m.d. H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.temporary-surveys.show', $survey->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Megtekintés
                                        </a>
                                        <form action="{{ route('admin.temporary-surveys.destroy', $survey->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Biztosan törölni szeretné ezt a folyamatban lévő kitöltést?')">
                                                <i class="fas fa-trash"></i> Törlés
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Nincs folyamatban lévő kitöltés.</td>
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
        $('#tempSurveysTable').DataTable({
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
