@extends('layouts.app')

@section('title', 'Login')

@section('content')

    <section class="section" style="background-color: var(--bg-light); display: flex; align-items: center; justify-content: center; min-height: calc(100vh - 160px);">
        <div class="container" style="max-width: 450px;">
            
            <div class="card" style="padding: 40px; border-color: var(--border-light);">
                <div style="text-align: center; margin-bottom: 30px;">
                    <div style="font-family: var(--font-heading); font-size: 2.5rem; font-weight: 800; text-transform: uppercase;">
                        TEST<span style="color: var(--primary);">AUTOMOTIVE</span> LOGIN
                    </div>
                    <p class="text-muted" style="margin-top: 5px; font-size: 0.95rem;">Enter credentials to access account</p>
                </div>

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <input type="email" name="email" id="email" required class="form-control" placeholder="email@example.com" value="{{ old('email') }}">
                        @error('email')
                            <span class="text-danger" style="font-size: 0.85rem; margin-top: 5px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 25px;">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" name="password" id="password" required class="form-control" placeholder="••••••••">
                    </div>

                    <div class="form-group flex-between" style="margin-bottom: 25px;">
                        <label class="flex" style="gap: 8px; cursor: pointer; font-size: 0.9rem;">
                            <input type="checkbox" name="remember" style="accent-color: var(--primary);"> Remember Me
                        </label>
                        <a href="#" style="font-size: 0.9rem; color: var(--primary);">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; height: 48px;">
                        Log In <i class="fa-solid fa-lock-open" style="margin-left: 5px;"></i>
                    </button>
                </form>

                <div style="margin-top: 30px; text-align: center; border-top: 1px solid var(--border-light); padding-top: 20px; font-size: 0.95rem;">
                    Don't have an account? <a href="{{ route('register') }}" style="color: var(--primary); font-weight: 700;">Register Here</a>
                </div>
            </div>

        </div>
    </section>

@endsection
