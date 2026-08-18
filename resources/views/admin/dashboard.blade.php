@extends('layouts.admin')

@section('styles')
<style>
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }
    .kpi-card {
        background: var(--bg-white);
        border: 1px solid var(--border-light);
        padding: 20px;
        border-radius: 6px;
        box-shadow: var(--shadow);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }
    .kpi-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-hover);
    }
    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary);
    }
    .kpi-title {
        font-family: var(--font-heading);
        font-weight: 700;
        text-transform: uppercase;
        color: var(--text-muted);
        font-size: 0.85rem;
        margin-bottom: 8px;
    }
    .kpi-val {
        font-family: var(--font-heading);
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-dark);
    }
    
    /* Charts layout responsive media query */
    @media (max-width: 1200px) {
        .analytics-row {
            grid-template-columns: 1fr !important;
        }
        .activity-row {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection

@section('content')

    <div class="flex-between" style="margin-bottom: 30px;">
        <h2 style="font-size: 2rem; text-transform: uppercase;">Control Center Overview</h2>
        <span style="font-size: 0.9rem; color: var(--text-muted); font-weight: 600;">
            <i class="fa-solid fa-clock"></i> Live Status Updates
        </span>
    </div>

    <!-- KPI Summary Section -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-title">Completed Revenue</div>
            <div class="kpi-val" style="color: var(--success);">${{ number_format($stats['revenue'], 2) }}</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-title">Customer Orders</div>
            <div class="kpi-val" style="color: var(--primary);">{{ $stats['orders_count'] }}</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-title">Store Products</div>
            <div class="kpi-val">{{ $stats['products_count'] }}</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-title">Contact Messages</div>
            <div class="kpi-val">{{ $stats['messages_count'] }}</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-title">Job Applications</div>
            <div class="kpi-val" style="color: #3b82f6;">{{ $stats['applications_count'] }}</div>
        </div>
    </div>

    <!-- Analytics Charts Section -->
    <div class="analytics-row" style="display: grid; grid-template-columns: 1.6fr 1fr; gap: 30px; margin-top: 30px;">
        <!-- Monthly Revenue Chart -->
        <div class="card" style="padding: 25px; background-color: var(--bg-card); border: 1px solid var(--border-light); border-radius: 4px; box-shadow: var(--shadow);">
            <h3 style="font-size: 1.3rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--border-light); padding-bottom: 10px; color: var(--text-dark);">
                <i class="fa-solid fa-chart-line" style="color: var(--primary); margin-right: 8px;"></i> Revenue Growth Trend (6m)
            </h3>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Orders Status Breakdown Chart -->
        <div class="card" style="padding: 25px; background-color: var(--bg-card); border: 1px solid var(--border-light); border-radius: 4px; box-shadow: var(--shadow);">
            <h3 style="font-size: 1.3rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--border-light); padding-bottom: 10px; color: var(--text-dark);">
                <i class="fa-solid fa-chart-pie" style="color: var(--primary); margin-right: 8px;"></i> Orders Status Breakdown
            </h3>
            <div style="position: relative; height: 300px; width: 100%; display: flex; align-items: center; justify-content: center;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Orders & Messages Grid -->
    <div class="activity-row" style="display: grid; grid-template-columns: 1.6fr 1fr; gap: 30px; margin-top: 35px; margin-bottom: 30px;">
        
        <!-- Recent Orders -->
        <div class="card" style="padding: 25px; background-color: var(--bg-card); border: 1px solid var(--border-light); border-radius: 4px; box-shadow: var(--shadow);">
            <h3 style="font-size: 1.3rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--border-light); padding-bottom: 10px;">Recent Orders</h3>
            @if($recentOrders->isEmpty())
                <p class="text-muted" style="text-align: center; padding: 20px 0;">No orders placed yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table" style="font-size: 0.9rem;">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th style="text-align: right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td style="font-weight: 700; color: var(--primary);">#{{ $order->id }}</td>
                                    <td>
                                        <span style="display:block; font-weight: 600;">{{ $order->first_name }} {{ $order->last_name }}</span>
                                        <span class="text-muted" style="font-size:0.8rem;">{{ $order->email }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $order->status }}">{{ $order->status }}</span>
                                    </td>
                                    <td style="font-weight: 700; text-align: right;">${{ number_format($order->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 15px; text-align: right;">
                    <a href="{{ route('admin.orders') }}" style="color: var(--primary); font-weight: bold; font-size: 0.85rem; text-transform: uppercase;">
                        Manage All Orders <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            @endif
        </div>

        <!-- Recent Contact Messages -->
        <div class="card" style="padding: 25px; background-color: var(--bg-card); border: 1px solid var(--border-light); border-radius: 4px; box-shadow: var(--shadow);">
            <h3 style="font-size: 1.3rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--border-light); padding-bottom: 10px;">Leads / Messages</h3>
            @if($recentMessages->isEmpty())
                <p class="text-muted" style="text-align: center; padding: 20px 0;">No messages received yet.</p>
            @else
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    @foreach($recentMessages as $msg)
                        <div style="background-color: var(--bg-input); border: 1px solid var(--border-light); padding: 15px; border-radius: 4px;">
                            <div class="flex-between" style="margin-bottom: 5px;">
                                <strong style="font-size:0.95rem; text-transform: uppercase;">{{ $msg->name }}</strong>
                                <span class="text-muted" style="font-size:0.75rem;">{{ $msg->created_at->diffForHumans() }}</span>
                            </div>
                            <span class="text-primary" style="font-size:0.8rem; font-weight: 700; display:block; margin-bottom: 5px;">Subject: {{ $msg->subject }}</span>
                            <p class="text-muted" style="font-size: 0.85rem; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                                {{ $msg->message }}
                            </p>
                        </div>
                    @endforeach
                </div>
                <div style="margin-top: 15px; text-align: right;">
                    <a href="{{ route('admin.leads') }}" style="color: var(--primary); font-weight: bold; font-size: 0.85rem; text-transform: uppercase;">
                        Open Leads Inbox <i class="fa-solid fa-inbox"></i>
                    </a>
                </div>
            @endif
        </div>

    </div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Line Chart for Revenue Growth
        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        const revenueGradient = ctxRevenue.createLinearGradient(0, 0, 0, 300);
        revenueGradient.addColorStop(0, 'rgba(255, 46, 46, 0.3)');
        revenueGradient.addColorStop(1, 'rgba(255, 46, 46, 0.0)');

        new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Revenue ($)',
                    data: {!! json_encode($chartData) !!},
                    borderColor: '#ff2e2e',
                    borderWidth: 3,
                    backgroundColor: revenueGradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ff2e2e',
                    pointBorderColor: '#fff',
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: '#ff2e2e',
                    pointHoverBorderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        padding: 12,
                        backgroundColor: '#0a0a0c',
                        titleColor: '#fff',
                        bodyColor: '#e4e4e7',
                        borderColor: '#27272a',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                return ' Revenue: $' + context.raw.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f4f4f5'
                        },
                        ticks: {
                            font: {
                                family: 'Mulish'
                            },
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: 'Mulish'
                            }
                        }
                    }
                }
            }
        });

        // Doughnut Chart for Order Status
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($statusLabels) !!}.map(s => s.charAt(0).toUpperCase() + s.slice(1)),
                datasets: [{
                    data: {!! json_encode($statusData) !!},
                    backgroundColor: [
                        '#eab308', // Pending - Yellow
                        '#60a5fa', // Processing - Blue
                        '#22c55e', // Completed - Green
                        '#ef4444'  // Cancelled - Red
                    ],
                    borderWidth: 2,
                    borderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 12,
                            font: {
                                family: 'Mulish',
                                size: 12
                            },
                            padding: 15
                        }
                    },
                    tooltip: {
                        padding: 12,
                        backgroundColor: '#0a0a0c',
                        titleColor: '#fff',
                        bodyColor: '#e4e4e7',
                        borderColor: '#27272a',
                        borderWidth: 1,
                    }
                },
                cutout: '65%'
            }
        });
    });
</script>
@endsection
