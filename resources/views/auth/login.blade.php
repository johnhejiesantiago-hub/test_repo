<x-guest-layout>
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-6 col-lg-5">
            <div class="card">
                <div class="card-header bg-white text-center pt-4 pb-0 border-0">
                    <i class="bi bi-truck fs-1 text-primary"></i>
                    <h3 class="mt-2">Welcome Back!</h3>
                    <p class="text-muted">Login to your QuickServe account</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input id="email" type="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    name="email" value="{{ old('email') }}" required autofocus>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input id="password" type="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    name="password" required>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                            <label class="form-check-label" for="remember_me">
                                Remember me
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </button>
                        </div>
                        
                        @if (Route::has('password.request'))
                            <div class="text-center mt-3">
                                <a class="text-decoration-none" href="{{ route('password.request') }}">
                                    Forgot your password?
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
                <div class="card-footer bg-white text-center py-3 border-0">
                    <p class="mb-0">Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none">Register here</a></p>
                </div>
            </div>
            
            <!-- Demo Credentials Card -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="text-center mb-2">Demo Credentials</h6>
                    <div class="row small">
                        <div class="col-6">
                            <strong>👑 Admin:</strong><br>
                            admin@quickserve.com<br>
                            password
                        </div>
                        <div class="col-6">
                            <strong>👤 Passenger:</strong><br>
                            passenger@example.com<br>
                            password
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>