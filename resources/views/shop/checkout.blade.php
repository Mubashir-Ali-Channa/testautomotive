@extends('layouts.app')

@section('title', 'Checkout')

@section('content')

    <!-- Inner Hero -->
    <section class="hero-section" style="padding: 40px 0; background-position: center 30%;">
        <div class="container text-center">
            <h1 class="hero-title" style="font-size: 2.8rem; margin-bottom: 5px;">Order Checkout</h1>
            <span class="hero-subtitle">Provide delivery info and confirm payment details</span>
        </div>
    </section>

    <!-- Checkout Form -->
    <section class="section section-light" style="padding-top: 40px;">
        <div class="container">
            
            <form action="{{ route('checkout.submit') }}" method="POST">
                @csrf
                <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 40px; align-items: start;">
                    
                    <!-- Billing & Shipping Form -->
                    <div class="card" style="padding: 30px; background-color: var(--bg-card);">
                        <h3 style="font-size: 1.5rem; text-transform: uppercase; margin-bottom: 25px; border-bottom: 1px solid var(--border-light); padding-bottom: 10px;">Billing Details</h3>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label class="form-label" for="first_name">First Name</label>
                                @php
                                    $fullName = auth()->check() ? auth()->user()->name : '';
                                    $parts = explode(' ', $fullName);
                                    $firstName = $parts[0] ?? '';
                                    $lastName = isset($parts[1]) ? implode(' ', array_slice($parts, 1)) : '';
                                @endphp
                                <input type="text" name="first_name" id="first_name" required class="form-control" value="{{ old('first_name', $firstName) }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="last_name">Last Name</label>
                                <input type="text" name="last_name" id="last_name" required class="form-control" value="{{ old('last_name', $lastName) }}">
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label class="form-label" for="email">Email Address</label>
                                <input type="email" name="email" id="email" required class="form-control" value="{{ old('email', auth()->check() ? auth()->user()->email : '') }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="phone">Phone Number</label>
                                <input type="text" name="phone" id="phone" required class="form-control" value="{{ old('phone', auth()->check() ? auth()->user()->phone : '') }}">
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label class="form-label" for="address">Delivery Address</label>
                                <input type="text" name="address" id="address" required class="form-control" value="{{ old('address', auth()->check() ? auth()->user()->address : '') }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="city">City</label>
                                <input type="text" name="city" id="city" required class="form-control" value="{{ old('city', auth()->check() ? auth()->user()->city : '') }}">
                            </div>
                        </div>

                        <div class="form-group" style="width: 50%;">
                            <label class="form-label" for="zip">ZIP / Postcode</label>
                            <input type="text" name="zip" id="zip" required class="form-control" value="{{ old('zip', auth()->check() ? auth()->user()->zip : '') }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="notes">Order Notes (Optional)</label>
                            <textarea name="notes" id="notes" class="form-control" placeholder="e.g. Leave package by garage door..."></textarea>
                        </div>
                    </div>

                    <!-- Side Order Summary & Payment -->
                    <div style="display: flex; flex-direction: column; gap: 25px;">
                        
                        <!-- Order Summary Card -->
                        <div class="card" style="padding: 25px;">
                            <h3 style="font-size: 1.3rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 2px solid var(--primary); padding-bottom: 8px;">Your Order</h3>
                            
                            <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px; max-height: 200px; overflow-y: auto; padding-right: 5px;">
                                @foreach($cart as $id => $item)
                                    <div class="flex-between" style="font-size: 0.95rem;">
                                        <span style="color: var(--text-dark); font-weight: 600; line-height: 1.3;">
                                            {{ $item['name'] }} <strong class="text-primary">x{{ $item['quantity'] }}</strong>
                                        </span>
                                        <span style="font-weight: 700;">
                                            ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="flex-between" style="border-top: 1px solid var(--border-light); padding-top: 15px; margin-bottom: 12px; font-size: 1.05rem;">
                                <span class="text-muted">Subtotal:</span>
                                <span style="font-weight: 600;">${{ number_format($total, 2) }}</span>
                            </div>

                            <div class="flex-between" style="margin-bottom: 15px; font-size: 1.05rem; border-bottom: 1px solid var(--border-light); padding-bottom: 15px;">
                                <span class="text-muted">Shipping:</span>
                                <span class="text-primary" style="font-weight: 700; text-transform: uppercase;">Free</span>
                            </div>

                            <div class="flex-between" style="font-size: 1.35rem; font-weight: 800;">
                                <span>Total Price:</span>
                                <span style="color: var(--primary);">${{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <!-- Payment Method Card -->
                        <div class="card" style="padding: 25px;">
                            <h3 style="font-size: 1.3rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 2px solid var(--primary); padding-bottom: 8px;">Payment Method</h3>
                            
                            <div class="form-group" style="display: flex; flex-direction: column; gap: 12px;">
                                <label class="flex" style="cursor: pointer; border: 1px solid var(--border-light); padding: 12px; border-radius: 4px; background-color: var(--bg-input);">
                                    <input type="radio" name="payment_method" value="cash_on_delivery" checked style="accent-color: var(--primary); width:18px; height:18px;">
                                    <div style="margin-left: 10px;">
                                        <strong style="text-transform: uppercase; display: block; font-size: 0.9rem;">Cash on Delivery</strong>
                                        <span class="text-muted" style="font-size: 0.8rem;">Pay cash when bike parts arrive at door</span>
                                    </div>
                                </label>
                                
                                <label class="flex" style="cursor: pointer; border: 1px solid var(--border-light); padding: 12px; border-radius: 4px; background-color: var(--bg-input);">
                                    <input type="radio" name="payment_method" value="bank_transfer" style="accent-color: var(--primary); width:18px; height:18px;">
                                    <div style="margin-left: 10px;">
                                        <strong style="text-transform: uppercase; display: block; font-size: 0.9rem;">Direct Bank Transfer</strong>
                                        <span class="text-muted" style="font-size: 0.8rem;">Transfer directly to our corporate account</span>
                                    </div>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary" style="width: 100%; height: 50px; margin-top: 10px;">
                                Place Order Now <i class="fa-solid fa-circle-check" style="margin-left: 5px;"></i>
                            </button>
                        </div>

                    </div>

                </div>
            </form>

        </div>
    </section>

@endsection
