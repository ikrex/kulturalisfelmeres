@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Kulturális intézmények</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Kulturális intézmények</li>
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

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i>
                Intézmények listája
            </div>
            <div>
                <a href="{{ route('admin.institutions.upload') }}" class="btn btn-sm btn-success me-2">
                    <i class="fas fa-upload"></i> Excel importálása
                </a>
                <form method="POST" action="{{ route('admin.institutions.generate-tracking') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-code"></i> Hiányzó követőkódok generálása
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <form action="{{ route('admin.institutions.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="name" class="form-label">Intézmény neve</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ request('name') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="region" class="form-label">Régió</label>
                        <select class="form-select" id="region" name="region">
                            <option value="">Összes régió</option>
                            @foreach($regions as $region)
                                <option value="{{ $region }}" {{ request('region') == $region ? 'selected' : '' }}>{{ $region }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="survey_completed" class="form-label">Felmérés kitöltése</label>
                        <select class="form-select" id="survey_completed" name="survey_completed">
                            <option value="">Mind</option>
                            <option value="1" {{ request('survey_completed') === '1' ? 'selected' : '' }}>Kitöltötte</option>
                            <option value="0" {{ request('survey_completed') === '0' ? 'selected' : '' }}>Nem töltötte ki</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Szűrés</button>
                        <a href="{{ route('admin.institutions.index') }}" class="btn btn-secondary">Törlés</a>
                    </div>
                </form>
            </div>

            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>
                            <a href="{{ route('admin.institutions.index', ['sort' => 'name', 'direction' => request('sort') == 'name' && request('direction') == 'asc' ? 'desc' : 'asc'] + request()->except(['sort', 'direction', 'page'])) }}">
                                Intézmény neve
                                @if(request('sort') == 'name')
                                    <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>Email</th>
                        <th>Kapcsolattartó</th>
                        <th>Régió</th>
                        <th>Város</th>
                        <th>Követőkód</th>
                        <th>Email utolsó megnyitása</th>
                        <th>
                            <a href="{{ route('admin.institutions.index', ['sort' => 'survey_completed', 'direction' => request('sort') == 'survey_completed' && request('direction') == 'asc' ? 'desc' : 'asc'] + request()->except(['sort', 'direction', 'page'])) }}">
                                Felmérés kitöltve
                                @if(request('sort') == 'survey_completed')
                                    <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>Műveletek</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($institutions as $institution)
                    <tr>
                        <td>{{ $institution->name }}</td>
                        <td>{{ $institution->email }}</td>
                        <td>{{ $institution->contact_person }}</td>
                        <td>{{ $institution->region }}</td>
                        <td>{{ $institution->city }}</td>
                        <td>
                            @if($institution->tracking_code)
                                <span class="badge bg-info">{{ $institution->tracking_code }}</span>
                            @else
                                <span class="badge bg-warning">Nincs</span>
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
                            @if($institution->survey_completed)
                                <span class="badge bg-success">Igen</span>
                            @else
                                <span class="badge bg-danger">Nem</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.institutions.edit', $institution->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> Szerkesztés
                            </a>
                            <form action="{{ route('admin.institutions.destroy', $institution->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Biztos törölni szeretné ezt az intézményt?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Törlés
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Nincsenek kulturális intézmények az adatbázisban.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center">
                {{ $institutions->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
