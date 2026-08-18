@extends('layouts.admin')

@section('content')

    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 2rem; text-transform: uppercase;">Customer Orders Manager</h2>
        <p class="text-muted">View order logs and manage delivery statuses</p>
    </div>

    <div class="card" style="padding: 25px;">
        @if($orders->isEmpty())
            <p class="text-muted" style="text-align: center; padding: 40px 0;">No customer orders placed yet.</p>
        @else
            <div class="table-responsive">
                <table class="table" style="vertical-align: middle; font-size: 0.95rem;">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Date</th>
                            <th>Customer & Delivery</th>
                            <th>Payment</th>
                            <th>Items</th>
                            <th style="text-align: right;">Total Price</th>
                            <th style="width: 220px; text-align: center;">Status Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td style="font-weight: 700; color: var(--primary);">#{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <strong style="display:block;">{{ $order->first_name }} {{ $order->last_name }}</strong>
                                    <span style="display:block; font-size: 0.85rem; color:var(--text-muted);">{{ $order->email }} | {{ $order->phone }}</span>
                                    <span style="display:block; font-size: 0.85rem; color:var(--text-muted); margin-top: 3px;"><i class="fa-solid fa-truck"></i> {{ $order->address }}, {{ $order->city }}, {{ $order->zip }}</span>
                                    @if($order->notes)
                                        <span style="display:block; font-size: 0.8rem; background-color: var(--bg-input); padding: 5px; border-radius: 2px; margin-top: 5px; color: var(--text-muted); font-style: italic;">
                                            Note: "{{ $order->notes }}"
                                        </span>
                                    @endif
                                </td>
                                <td style="text-transform: uppercase; font-size: 0.8rem; font-weight: bold;">
                                    {{ str_replace('_', ' ', $order->payment_method) }}
                                </td>
                                <td>
                                    <div style="display:flex; flex-direction:column; gap:3px; max-height: 80px; overflow-y:auto; padding-right: 5px;">
                                        @foreach($order->items as $item)
                                            <span style="font-size:0.8rem; line-height: 1.2;">
                                                {{ $item->product->name ?? 'Deleted Product' }} <strong class="text-primary">x{{ $item->quantity }}</strong>
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td style="font-weight: 800; color: var(--primary); text-align: right;">
                                    ${{ number_format($order->total, 2) }}
                                </td>
                                <td style="text-align: center;">
                                    <div x-data="{ statusVal: '{{ $order->status }}' }">
                                        <select name="status" class="form-control" style="padding: 6px; font-size: 0.85rem; font-weight: 700; width: 130px;"
                                            x-model="statusVal"
                                            @change="
                                                fetch('{{ route('admin.orders.status', $order->id) }}', {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'Accept': 'application/json',
                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                    },
                                                    body: JSON.stringify({ status: statusVal })
                                                })
                                                .then(res => res.json())
                                                .then(data => {
                                                    window.dispatchEvent(new CustomEvent('toast', {
                                                        detail: { message: data.message || 'Order status updated.', status: 'success' }
                                                    }));
                                                })
                                                .catch(err => {
                                                    window.dispatchEvent(new CustomEvent('toast', {
                                                        detail: { message: 'Failed to update order status.', status: 'error' }
                                                    }));
                                                });
                                            ">
                                            <option value="pending">Pending</option>
                                            <option value="processing">Processing</option>
                                            <option value="completed">Completed</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div style="margin-top: 30px; display: flex; justify-content: center;">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

@endsection
