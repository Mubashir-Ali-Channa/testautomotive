@extends('layouts.admin')

@section('content')

    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 2rem; text-transform: uppercase;">Administrators Directory</h2>
        <p class="text-muted">Manage back-office administrative access credentials and roles</p>
    </div>

    <div class="admin-admins-grid">
        
        <!-- Add Admin Form -->
        <div class="card" style="padding: 25px;">
            <h3 style="font-size: 1.3rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">Create Admin Account</h3>
            
            <form action="{{ route('admin.admins.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="name">Full Name</label>
                    <input type="text" name="name" id="name" required class="form-control" placeholder="e.g. Sarah Connor">
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" name="email" id="email" required class="form-control" placeholder="e.g. sarah@testautomotive.com">
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" name="password" id="password" required class="form-control" placeholder="••••••••">
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required class="form-control" placeholder="••••••••">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; height: 44px; margin-top: 10px;">
                    Create Admin Profile <i class="fa-solid fa-user-shield" style="margin-left: 5px;"></i>
                </button>
            </form>
        </div>

        <!-- Admins List -->
        <div class="card" style="padding: 25px;">
            <h3 style="font-size: 1.3rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">Active Administrators</h3>
            
            <div class="table-responsive">
                <table class="table" style="vertical-align: middle;">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email Address</th>
                            <th style="text-align: center;">Account Role</th>
                            <th style="text-align: center; width: 100px;">Status</th>
                            <th style="width: 100px; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admins as $admin)
                            <tr x-data="{ visible: true }" x-show="visible" x-transition>
                                <td>
                                    <strong style="font-size: 1.05rem; text-transform: uppercase;">{{ $admin->name }}</strong>
                                    @if($admin->id === auth()->id())
                                        <span class="badge badge-processing" style="font-size: 0.65rem; margin-left: 5px;">You</span>
                                    @endif
                                </td>
                                <td style="font-family: monospace;">{{ $admin->email }}</td>
                                <td style="text-align: center;">
                                    @if($admin->role === 'super_admin')
                                        <span class="badge badge-completed" style="font-size: 0.72rem; padding: 4px 10px; background-color: rgba(239, 68, 68, 0.1); color: var(--primary); border: 1px solid rgba(239,68,68,0.2);">Super Admin</span>
                                    @else
                                        <span class="badge badge-processing" style="font-size: 0.72rem; padding: 4px 10px;">Admin</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    @if($admin->role === 'super_admin')
                                        <span style="color: var(--success); font-weight: 700; font-size: 0.9rem;">Active</span>
                                    @else
                                        <div x-data="{ active: {{ $admin->is_active ? 'true' : 'false' }} }" style="display: inline-block;">
                                            <button type="button" @click="
                                                active = !active;
                                                fetch('{{ route('admin.admins.toggle_active', $admin->id) }}', {
                                                    method: 'POST',
                                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                                }).then(res => {
                                                    if (!res.ok) active = !active;
                                                });
                                            " class="btn btn-secondary" style="padding: 4px 8px; font-size: 0.75rem;">
                                                <span x-show="active" style="color: var(--success); font-weight: 700;">Active</span>
                                                <span x-show="!active" style="color: var(--danger); font-weight: 700;">Inactive</span>
                                            </button>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($admin->role !== 'super_admin' && $admin->id !== auth()->id())
                                        <div style="text-align: center;">
                                            <button type="button" @click="
                                                if (confirm('Remove administrative privileges for {{ $admin->name }}?')) {
                                                    visible = false;
                                                    fetch('{{ route('admin.admins.delete', $admin->id) }}', {
                                                        method: 'POST',
                                                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                                    }).then(res => {
                                                        if (!res.ok) {
                                                            visible = true;
                                                            alert('Failed to delete admin profile.');
                                                        }
                                                    });
                                                }
                                            " class="btn btn-danger" style="padding: 6px 12px; font-size: 0.8rem;">
                                                <i class="fa-solid fa-trash-can"></i> Delete
                                            </button>
                                        </div>
                                    @else
                                        <div style="text-align: center; color: var(--text-muted); font-size: 0.8rem; font-style: italic;">Locked</div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection
