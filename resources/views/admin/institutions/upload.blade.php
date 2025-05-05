@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Excel fájl importálása</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.institutions.index') }}">Kulturális intézmények</a></li>
        <li class="breadcrumb-item active">Excel importálása</li>
    </ol>

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-upload me-1"></i>
            Excel fájl feltöltése
        </div>
        <div class="card-body">
            <form action="{{ route('admin.institutions.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="excel_file" class="form-label">Excel fájl kiválasztása</label>
                    <input class="form-control @error('excel_file') is-invalid @enderror" type="file" id="excel_file" name="excel_file" accept=".xlsx,.xls,.csv">
                    @error('excel_file')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="form-text">
                        Támogatott formátumok: .xlsx, .xls, .csv
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-info-circle me-1"></i>
                        Importálási információk
                    </div>
                    <div class="card-body">
                        <p>Az Excel fájl importálása során a rendszer az alábbi oszlopokat fogja keresni:</p>
                        <ul>
                            <li><strong>Név / Intézmény neve / Kultúrház neve</strong> - Az intézmény megnevezése (kötelező)</li>
                            <li><strong>E-mail / Email / Email cím</strong> - Az intézmény email címe (kötelező)</li>
                            <li><strong>Kapcsolattartó / Kapcsolattartó neve</strong> - A kapcsolattartó személy neve</li>
                            <li><strong>Telefon / Telefonszám</strong> - Az intézmény telefonszáma</li>
                            <li><strong>Cím</strong> - Az intézmény címe</li>
                            <li><strong>Város / Település</strong> - Az intézmény települése</li>
                            <li><strong>Irányítószám</strong> - Az intézmény irányítószáma</li>
                            <li><strong>Régió / Megye</strong> - Az intézmény régiója vagy megyéje</li>
                            <li><strong>Weboldal / Honlap</strong> - Az intézmény weboldala</li>
                        </ul>
                        <p class="mb-0">Az importálás során a rendszer automatikusan egyedi követőkódot generál minden intézménynek.</p>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.institutions.index') }}" class="btn btn-secondary">Vissza</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-1"></i> Importálás
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
