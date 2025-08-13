@extends('installer::layouts.master')

@section('title', 'License Verification')

@section('container')
    <div class="step-container">
        <div class="step-header">
            <h2 class="step-title">
                <i class="fas fa-key step-icon"></i>
                License Verification
            </h2>
            <p class="step-description">
                Please enter your license key to verify your software license before proceeding with the installation.
            </p>
        </div>

        <div class="step-content">
            <form method="POST" action="{{ route('installer.license-verification.save') }}" class="license-form">
                @csrf
                
                <div class="form-group">
                    <label for="license_key" class="form-label">
                        <i class="fas fa-key"></i>
                        License Key *
                    </label>
                    <input 
                        type="text" 
                        name="license_key" 
                        id="license_key"
                        class="form-control @error('license_key') is-invalid @enderror" 
                        value="{{ old('license_key') }}"
                        placeholder="Enter your license key"
                        required
                    >
                    @error('license_key')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <small class="form-text text-muted">
                        Please enter the license key you received when purchasing this software
                    </small>
                </div>

                <div class="form-group">
                    <label for="domain" class="form-label">
                        <i class="fas fa-globe"></i>
                        Domain *
                    </label>
                    <input 
                        type="text" 
                        name="domain" 
                        id="domain"
                        class="form-control @error('domain') is-invalid @enderror" 
                        value="{{ old('domain', request()->getHost()) }}"
                        placeholder="example.com"
                        required
                    >
                    @error('domain')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <small class="form-text text-muted">
                        The domain where this software will be installed
                    </small>
                </div>

                <div class="license-info-box">
                    <div class="info-header">
                        <i class="fas fa-info-circle"></i>
                        License Information
                    </div>
                    <ul class="info-list">
                        <li>Your license key is required to activate and use this software</li>
                        <li>Each license is tied to a specific domain</li>
                        <li>Make sure you have a valid internet connection for verification</li>
                        <li>Contact support if you encounter any issues with license verification</li>
                    </ul>
                </div>

                <div class="step-actions">
                    <button type="submit" class="btn btn-primary btn-verify">
                        <i class="fas fa-check-circle"></i>
                        Verify License & Continue
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .license-form {
            max-width: 600px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-control {
            padding: 0.75rem;
            border: 2px solid #e1e5e9;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            display: block;
            font-size: 0.875rem;
            color: #dc3545;
            margin-top: 0.25rem;
        }

        .license-info-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin: 2rem 0;
        }

        .info-header {
            font-weight: 600;
            color: #495057;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-list {
            margin: 0;
            padding-left: 1.5rem;
        }

        .info-list li {
            margin-bottom: 0.5rem;
            color: #6c757d;
        }

        .step-actions {
            text-align: center;
            margin-top: 2rem;
        }

        .btn-verify {
            padding: 0.75rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .step-icon {
            color: #007bff;
            font-size: 1.5rem;
        }

        .step-title {
            color: #343a40;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .step-description {
            color: #6c757d;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }
    </style>
@endsection