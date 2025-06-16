@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Intézmény szerkesztése</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.institutions.index') }}">Kulturális intézmények</a></li>
        <li class="breadcrumb-item active">Intézmény szerkesztése</li>
    </ol>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            {{ $institution->name }} szerkesztése
        </div>
        <div class="card-body">
            <form action="{{ route('admin.institutions.update', $institution->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Intézmény neve *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $institution->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email cím *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $institution->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="contact_person" class="form-label">Kapcsolattartó neve</label>
                        <input type="text" class="form-control @error('contact_person') is-invalid @enderror" id="contact_person" name="contact_person" value="{{ old('contact_person', $institution->contact_person) }}">
                        @error('contact_person')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label">Telefonszám</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $institution->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Cím</label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $institution->address) }}">
                    @error('address')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="postal_code" class="form-label">Irányítószám</label>
                        <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code', $institution->postal_code) }}">
                        @error('postal_code')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="city" class="form-label">Város</label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $institution->city) }}">
                        @error('city')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="region" class="form-label">Régió/Megye</label>
                        <input type="text" class="form-control @error('region') is-invalid @enderror" id="region" name="region" value="{{ old('region', $institution->region) }}">
                        @error('region')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="website" class="form-label">Weboldal</label>
                        <input type="url" class="form-control @error('website') is-invalid @enderror" id="website" name="website" value="{{ old('website', $institution->website) }}">
                        @error('website')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="tracking_code" class="form-label">Követőkód</label>
                        <input type="text" class="form-control" id="tracking_code" value="{{ $institution->tracking_code }}" readonly>
                        <div class="form-text">A követőkód automatikusan generált és nem szerkeszthető.</div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="survey_completed" name="survey_completed" value="1" {{ old('survey_completed', $institution->survey_completed) ? 'checked' : '' }}>
                        <label class="form-check-label" for="survey_completed">Felmérés kitöltve</label>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="can_receive_emails" name="can_receive_emails" value="1" {{ old('can_receive_emails', $institution->can_receive_emails) ? 'checked' : '' }}>
                        <label class="form-check-label" for="can_receive_emails">Fogadhat emaileket</label>
                    </div>
                </div>

                <div class="mb-3" id="email_opt_out_reason_container" {{ old('can_receive_emails', $institution->can_receive_emails) ? 'style="display:none;"' : '' }}>
                    <label for="email_opt_out_reason" class="form-label">Email tiltás indoka</label>
                    <textarea class="form-control" id="email_opt_out_reason" name="email_opt_out_reason" rows="2">{{ old('email_opt_out_reason', $institution->email_opt_out_reason) }}</textarea>
                    <div class="form-text">Írd le, hogy miért nem fogadhat emaileket az intézmény.</div>
                </div>

                <div class="mb-3">
                    <label for="admin_notes" class="form-label">Admin megjegyzések</label>
                    <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3">{{ old('admin_notes', $institution->admin_notes) }}</textarea>
                    <div class="form-text">Belső megjegyzések az intézménnyel kapcsolatban.</div>
                </div>


                @if($institution->email_opens && count($institution->email_opens) > 0)
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fas fa-envelope-open-text me-1"></i>
                        Email megnyitási történet
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($institution->email_opens as $timestamp)
                                <li class="list-group-item">{{ \Carbon\Carbon::parse($timestamp)->format('Y-m-d H:i:s') }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.institutions.index') }}" class="btn btn-secondary">Vissza</a>
                    <button type="submit" class="btn btn-primary">Mentés</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        var canReceiveEmailsCheckbox = document.getElementById('can_receive_emails');
        var optOutReasonContainer = document.getElementById('email_opt_out_reason_container');

        // Kezdeti állapot beállítása
        optOutReasonContainer.style.display = canReceiveEmailsCheckbox.checked ? 'none' : 'block';

        // Eseménykezelő hozzáadása
        canReceiveEmailsCheckbox.addEventListener('change', function() {
            optOutReasonContainer.style.display = this.checked ? 'none' : 'block';
        });
    });
    </script>

@endsection
