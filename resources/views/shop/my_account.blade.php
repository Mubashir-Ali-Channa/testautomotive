@extends('layouts.app')

@section('title', 'My Account')

@section('content')

    <!-- Inner Hero -->
    <section class="hero-section" style="padding: 50px 0; background-position: center 30%;">
        <div class="container text-center">
            <h1 class="hero-title" style="font-size: 3rem; margin-bottom: 5px;">Customer Profile</h1>
            <span class="hero-subtitle">Manage billing information, reset passwords, and view previous orders</span>
        </div>
    </section>

    <!-- Account Details -->
    <section class="section section-light" style="padding-top: 40px;">
        <div class="container" x-data="{ activeTab: 'profile' }">
            
            <div style="display: grid; grid-template-columns: 250px 1fr; gap: 40px; align-items: start;">
                
                <!-- Account Navigation Sidebar -->
                <div class="card" style="padding: 20px; background-color: var(--bg-card); border-color: var(--border-light);">
                    <div class="flex-between" style="border-bottom: 1px solid var(--border-light); padding-bottom: 15px; margin-bottom: 15px;">
                        <div>
                            <strong style="display:block; text-transform: uppercase;">{{ auth()->user()->name }}</strong>
                            <span class="text-muted" style="font-size: 0.8rem;">Joined: {{ auth()->user()->created_at->format('M Y') }}</span>
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <button type="button" @click="activeTab = 'profile'" :class="activeTab === 'profile' ? 'btn btn-primary' : 'btn btn-secondary'" style="width: 100%; justify-content: flex-start; text-transform: uppercase; padding: 10px 15px;">
                            <i class="fa-solid fa-user-gear"></i> Settings Profile
                        </button>
                        
                        <button type="button" @click="activeTab = 'orders'" :class="activeTab === 'orders' ? 'btn btn-primary' : 'btn btn-secondary'" style="width: 100%; justify-content: flex-start; text-transform: uppercase; padding: 10px 15px;">
                            <i class="fa-solid fa-box-open"></i> Order History ({{ $orders->count() }})
                        </button>
                    </div>
                </div>

                <!-- Content Area -->
                <div>
                    <!-- Settings Profile Tab (Livewire Component) -->
                    <div x-show="activeTab === 'profile'">
                        <livewire:user-profile-settings />
                    </div>

                    <!-- Order History Tab -->
                    <div x-show="activeTab === 'orders'" class="card" style="padding: 30px; background-color: var(--bg-card); display: none;" :style="{ display: activeTab === 'orders' ? 'block' : 'none' }">
                        <h3 style="font-size: 1.5rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--border-dark); padding-bottom: 10px;">Your Orders</h3>
                        
                        @if($orders->isEmpty())
                            <p class="text-muted" style="padding: 20px 0; text-align: center;">You have not placed any orders yet.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table" style="vertical-align: top; font-size: 0.95rem;">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Date</th>
                                            <th>Delivery Address</th>
                                            <th>Items</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                            <tr>
                                                <td style="font-weight: 700; color: var(--primary);">#{{ $order->id }}</td>
                                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <span style="display:block; font-size: 0.85rem; line-height: 1.3;">{{ $order->address }}, {{ $order->city }}, {{ $order->zip }}</span>
                                                </td>
                                                <td>
                                                    <div style="font-size: 0.82rem; line-height: 1.3;">
                                                        @foreach($order->items as $item)
                                                            <div>{{ $item->product->name ?? 'Deleted Item' }} <strong class="text-primary">x{{ $item->quantity }}</strong></div>
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td style="font-weight: 700; color: var(--primary);">${{ number_format($order->total, 2) }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $order->status }}">{{ $order->status }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                </div>

            </div>

        </div>
    </section>

@endsection
