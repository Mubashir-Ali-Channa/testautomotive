@extends('layouts.app')

@section('title', 'Verify Your Email')

@section('content')

    <section class="section" style="background-color: var(--bg-light); display: flex; align-items: center; justify-content: center; min-height: calc(100vh - 160px);">
        <div class="container" style="max-width: 520px;">
            
            <div class="card" style="padding: 40px; text-align: center;">
                <div style="width: 70px; height: 70px; border-radius: 50%; background-color: rgba(227, 24, 55, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 20px;">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </div>

                <h2 style="font-family: var(--font-heading); font-size: 1.6rem; font-weight: 800; text-transform: uppercase; margin-bottom: 10px;">
                    Verify Your Email Address
                </h2>

                <p class="text-muted" style="font-size: 0.95rem; line-height: 1.6; margin-bottom: 25px;">
                    Thanks for joining <strong>TESTAUTOMOTIVE</strong>! Before continuing, please check your inbox and click the verification link we just emailed to <strong>{{ auth()->user()->email ?? 'your email' }}</strong>.
                </p>

                @if (session('success'))
                    <div style="background-color: rgba(40, 167, 69, 0.1); color: #28a745; padding: 12px 16px; border-radius: var(--radius-sm); font-size: 0.9rem; margin-bottom: 20px; font-weight: 600;">
                        <i class="fa-solid fa-circle-check" style="margin-right: 6px;"></i> {{ session('success') }}
                    </div>
                @endif

                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <form action="{{ route('verification.send') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary" style="width: 100%; height: 46px;">
                            <i class="fa-solid fa-paper-plane" style="margin-right: 6px;"></i> Resend Verification Email
                        </button>
                    </form>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline" style="width: 100%; height: 42px;">
                            <i class="fa-solid fa-arrow-right-from-bracket" style="margin-right: 6px;"></i> Log Out
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </section>

@endsection
