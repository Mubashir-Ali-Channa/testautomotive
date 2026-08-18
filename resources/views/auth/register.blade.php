@extends('layouts.app')

@section('title', 'Register')

@section('content')

    <section class="section" style="background-color: var(--bg-light); display: flex; align-items: center; justify-content: center; min-height: calc(100vh - 160px);">
        <div class="container" style="max-width: 480px;">
            
            <div class="card" style="padding: 40px; border-color: var(--border-light);">
                <div style="text-align: center; margin-bottom: 30px;">
                    <div style="font-family: var(--font-heading); font-size: 2.5rem; font-weight: 800; text-transform: uppercase;">
                        REGISTER TO TEST<span style="color: var(--primary);">AUTOMOTIVE</span>
                    </div>
                    <p class="text-muted" style="margin-top: 5px; font-size: 0.95rem;">Join our specialist shop community</p>
                </div>

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label" for="name">Your Name</label>
                        <input type="text" name="name" id="name" required class="form-control" placeholder="John Doe" value="{{ old('name') }}">
                        @error('name')
                            <span class="text-danger" style="font-size: 0.85rem; margin-top: 5px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <input type="email" name="email" id="email" required class="form-control" placeholder="john@example.com" value="{{ old('email') }}">
                        @error('email')
                            <span class="text-danger" style="font-size: 0.85rem; margin-top: 5px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" name="password" id="password" required class="form-control" placeholder="••••••••">
                        @error('password')
                            <span class="text-danger" style="font-size: 0.85rem; margin-top: 5px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 30px;">
                        <label class="form-label" for="password_confirmation">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required class="form-control" placeholder="••••••••">
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; height: 48px;">
                        Create Account <i class="fa-solid fa-user-plus" style="margin-left: 5px;"></i>
                    </button>
                </form>

                <div style="margin-top: 30px; text-align: center; border-top: 1px solid var(--border-light); padding-top: 20px; font-size: 0.95rem;">
                    Already have an account? <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 700;">Log In</a>
                </div>
            </div>

        </div>
    </section>

@endsection
