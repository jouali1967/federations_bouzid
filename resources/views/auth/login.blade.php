@extends('layouts.guest')

@section('content')
<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="row w-100 justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 overflow-hidden">
                <div class="row g-0">
                    <!-- Colonne de gauche avec image/bannière (optionnel) -->
                    <div class="col-lg-5 d-none d-lg-block bg-primary">
                        <div class="d-flex flex-column justify-content-center h-100 p-4 text-white">
                            <h2 class="fw-bold mb-4">{{ config('app.name', 'Laravel') }}</h2>
                            <p class="lead">Bienvenue sur votre espace de gestion</p>
                            <div class="mt-auto text-center">
                                <i class="fas fa-shield-alt fa-4x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Formulaire de connexion -->
                    <div class="col-lg-7">
                        <div class="card-body p-4 p-lg-5">
                            <div class="text-center mb-4 d-lg-none">
                                <h4 class="text-primary fw-bold">
                                    <i class="fas fa-user-lock me-2"></i>
                                    {{ __('Connexion') }}
                                </h4>
                            </div>
                            
                            <h4 class="mb-4 d-none d-lg-block fw-bold">{{ __('Connexion à votre compte') }}</h4>
                            
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="mb-4">
                                    <label for="email" class="form-label fw-medium">{{ __('Adresse Email') }}</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-envelope text-primary"></i>
                                        </span>
                                        <input id="email" type="email" 
                                            class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                            name="email" 
                                            value="{{ old('email') }}" 
                                            required 
                                            autocomplete="email" 
                                            autofocus
                                            placeholder="exemple@email.com">

                                        @error('email')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label for="password" class="form-label fw-medium">{{ __('Mot de passe') }}</label>
                                        @if (Route::has('password.request'))
                                            <a class="small text-decoration-none" href="{{ route('password.request') }}">
                                                {{ __('Mot de passe oublié ?') }}
                                            </a>
                                        @endif
                                    </div>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-lock text-primary"></i>
                                        </span>
                                        <input id="password" 
                                            type="password" 
                                            class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                            name="password" 
                                            required 
                                            autocomplete="current-password"
                                            placeholder="••••••••">
                                        <button class="btn btn-outline-secondary border-start-0" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">
                                            {{ __('Se souvenir de moi') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        {{ __('Se connecter') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4 text-muted small">
                &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }} - Tous droits réservés
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        
        togglePassword.addEventListener('click', function() {
            // Change le type de l'input
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Change l'icône
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    });
</script>
@endpush
@endsection
