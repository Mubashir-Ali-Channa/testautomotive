@extends('layouts.admin')

@section('content')

    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 2rem; text-transform: uppercase;">Leads & Submissions Inbox</h2>
        <p class="text-muted">Review contact form submissions and job application resume files</p>
    </div>

    <!-- Leads Tabs Header -->
    <div class="flex" style="gap: 15px; margin-bottom: 25px; border-bottom: 1px solid var(--border-dark); padding-bottom: 10px;">
        <button onclick="switchTab('messages')" id="tab-btn-messages" class="btn btn-primary" style="padding: 10px 20px; font-size: 0.95rem;">
            Contact Messages ({{ $messages->count() }})
        </button>
        <button onclick="switchTab('applications')" id="tab-btn-applications" class="btn btn-secondary" style="padding: 10px 20px; font-size: 0.95rem;">
            Job Applications ({{ $applications->count() }})
        </button>
    </div>

    <!-- Contact Messages Listing -->
    <div id="tab-content-messages" class="card" style="padding: 25px;">
        @if($messages->isEmpty())
            <p class="text-muted" style="text-align: center; padding: 30px 0;">No contact form messages received yet.</p>
        @else
            <div class="table-responsive">
                <table class="table" style="vertical-align: top; font-size: 0.95rem;">
                    <thead>
                        <tr>
                            <th>Sender</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th style="width: 150px;">Received At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($messages as $msg)
                            <tr>
                                <td>
                                    <strong style="display:block; text-transform: uppercase;">{{ $msg->name }}</strong>
                                    <span class="text-muted" style="font-size: 0.85rem;">{{ $msg->email }}</span>
                                </td>
                                <td style="font-weight: 700; color: var(--primary);">
                                    {{ $msg->subject }}
                                </td>
                                <td style="font-size: 0.9rem; line-height: 1.4; color: var(--text-dark);">
                                    {{ $msg->message }}
                                </td>
                                <td style="font-size: 0.85rem; color: var(--text-muted);">
                                    {{ $msg->created_at->format('M d, Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Job Applications Listing -->
    <div id="tab-content-applications" class="card" style="padding: 25px; display: none;">
        @if($applications->isEmpty())
            <p class="text-muted" style="text-align: center; padding: 30px 0;">No job applications submitted yet.</p>
        @else
            <div class="table-responsive">
                <table class="table" style="vertical-align: top; font-size: 0.95rem;">
                    <thead>
                        <tr>
                            <th>Position</th>
                            <th>Applicant</th>
                            <th>Cover Letter / Message</th>
                            <th style="width: 150px; text-align: center;">Resume</th>
                            <th style="width: 150px;">Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $app)
                            <tr>
                                <td>
                                    <strong style="color: var(--primary); text-transform: uppercase;">{{ $app->career->title ?? 'Deleted Position' }}</strong>
                                    <span style="display:block; font-size:0.8rem; color:var(--text-muted);">{{ $app->career->department ?? '' }}</span>
                                </td>
                                <td>
                                    <strong style="display:block; text-transform: uppercase;">{{ $app->name }}</strong>
                                    <span style="display:block; font-size: 0.85rem; color: var(--text-muted);">{{ $app->email }}</span>
                                    <span style="display:block; font-size: 0.85rem; color: var(--text-muted);">{{ $app->phone }}</span>
                                </td>
                                <td style="font-size: 0.9rem; line-height: 1.4; color: var(--text-dark);">
                                    {{ $app->message ?? 'No cover letter attached.' }}
                                </td>
                                <td style="text-align: center;">
                                    <div class="flex" style="gap: 5px; justify-content: center;">
                                        <a href="{{ route('admin.leads.resume', $app->id) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.8rem;">
                                            <i class="fa-solid fa-eye"></i> View
                                        </a>
                                        <a href="{{ asset('storage/' . $app->resume_path) }}" download class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.8rem;" title="Download File">
                                            <i class="fa-solid fa-download"></i>
                                        </a>
                                    </div>
                                </td>
                                <td style="font-size: 0.85rem; color: var(--text-muted);">
                                    {{ $app->created_at->format('M d, Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@endsection

@section('scripts')
    <script>
        function switchTab(tab) {
            var msgTab = document.getElementById('tab-content-messages');
            var appTab = document.getElementById('tab-content-applications');
            var msgBtn = document.getElementById('tab-btn-messages');
            var appBtn = document.getElementById('tab-btn-applications');

            if (tab === 'messages') {
                msgTab.style.display = 'block';
                appTab.style.display = 'none';
                msgBtn.className = 'btn btn-primary';
                appBtn.className = 'btn btn-secondary';
            } else {
                msgTab.style.display = 'none';
                appTab.style.display = 'block';
                msgBtn.className = 'btn btn-secondary';
                appBtn.className = 'btn btn-primary';
            }
        }
    </script>
@endsection
