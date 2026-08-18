@extends('layouts.app')

@section('title', 'Careers')

@section('content')

    <!-- Inner Hero -->
    <section class="hero-section" style="padding: 60px 0; background-position: center 80%;">
        <div class="container text-center">
            <h1 class="hero-title" style="font-size: 3.5rem; margin-bottom: 10px;">Careers with Us</h1>
            <span class="hero-subtitle">Join Our Passionate Crew of Builders, Mechanics, and Restorers</span>
        </div>
    </section>

    <!-- Careers List -->
    <section class="section section-light scroll-fade">
        <div class="container">
            @if($careers->isEmpty())
                <div class="card" style="padding: 50px; text-align: center;">
                    <div style="font-size: 3rem; color: var(--text-muted); margin-bottom: 15px;"><i class="fa-solid fa-face-smile"></i></div>
                    <h3 style="text-transform: uppercase;">No Open Positions</h3>
                    <p class="text-muted" style="margin-top: 10px;">We are currently fully staffed, but we're always looking for outstanding builders. Check back soon!</p>
                </div>
            @else
                <div class="grid grid-2" style="gap: 40px; align-items: start;" x-data="{ showForm: false }">
                    <div style="display: flex; flex-direction: column; gap: 30px;">
                        @foreach($careers as $career)
                            <div class="card" style="padding: 30px;">
                                <div class="flex-between" style="margin-bottom: 15px; align-items: center; gap: 15px;">
                                    <h3 style="font-size: 1.6rem; text-transform: uppercase; margin-bottom: 0;">{{ $career->title }}</h3>
                                    <span class="badge badge-processing" style="white-space: nowrap;">{{ $career->type }}</span>
                                </div>
                                <span class="text-primary" style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 15px;">
                                    <i class="fa-solid fa-folder-open" style="margin-right: 5px;"></i> {{ $career->department }}
                                </span>
                                
                                <h4 style="font-size: 1rem; text-transform: uppercase; margin-bottom: 5px; color: var(--primary);">Role Overview</h4>
                                <p class="text-muted" style="margin-bottom: 20px; font-size: 0.95rem;">
                                    {{ $career->description }}
                                </p>

                                <h4 style="font-size: 1rem; text-transform: uppercase; margin-bottom: 5px; color: var(--primary);">Requirements</h4>
                                <p class="text-muted" style="white-space: pre-line; font-size: 0.95rem; line-height: 1.5; margin-bottom: 25px;">
                                    {{ $career->requirements }}
                                </p>

                                <button type="button" @click="showForm = true; document.getElementById('career_id').value = '{{ $career->id }}'; document.getElementById('position_name').innerText = '{{ $career->title }}'; $nextTick(() => { window.scrollTo({top: document.getElementById('apply-form').offsetTop - 100, behavior: 'smooth'}); });" class="btn btn-primary" style="padding: 10px 20px; font-size: 0.95rem; cursor: pointer; border: none;">
                                    Apply For Position
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <!-- Application Form Card -->
                    <div id="apply-form" style="position: sticky; top: 100px;">
                        <div class="card" id="apply-form-card" x-show="showForm" x-collapse style="padding: 35px; border-color: var(--primary); display: none;" @close-apply-form.window="showForm = false">
                            <h3 style="font-size: 1.8rem; text-transform: uppercase; margin-bottom: 5px;">Submit Application</h3>
                            <p class="text-muted" style="font-size: 0.9rem; margin-bottom: 25px;">Applying for: <strong class="text-primary" id="position_name">-</strong></p>

                            <form action="" method="POST" enctype="multipart/form-data" id="job-app-form">
                                @csrf
                                <input type="hidden" name="career_id" id="career_id" value="">

                                <div class="form-group">
                                    <label class="form-label" for="name">Your Name</label>
                                    <input type="text" name="name" id="name" required class="form-control" placeholder="John Doe">
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="email">Your Email</label>
                                    <input type="email" name="email" id="email" required class="form-control" placeholder="john@example.com">
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="phone">Phone Number</label>
                                    <input type="text" name="phone" id="phone" required class="form-control" placeholder="+1 (555) 000-0000">
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="resume">Resume / CV (PDF, DOC, DOCX - Max 4MB)</label>
                                    <input type="file" name="resume" id="resume" required class="form-control" style="background-color: var(--bg-input); padding: 10px;">
                                </div>

                                <div class="form-group" x-data="{ 
                                    messageText: '', 
                                    get wordCount() {
                                        return this.messageText.trim() ? this.messageText.trim().split(/\s+/).length : 0;
                                    },
                                    enforceLimit(e) {
                                        if (this.wordCount >= 200 && e.keyCode !== 8 && e.keyCode !== 46) {
                                            e.preventDefault();
                                        }
                                    }
                                }">
                                    <label class="form-label" for="message">Cover Letter / Message</label>
                                    <textarea name="message" id="message" class="form-control" placeholder="Tell us why you want to join our custom workshop..."
                                        x-model="messageText"
                                        @keydown="enforceLimit($event)"
                                        @input="
                                            let words = messageText.trim().split(/\s+/);
                                            if (words.length > 200) {
                                                messageText = words.slice(0, 200).join(' ');
                                            }
                                        "></textarea>
                                    <div style="font-size: 0.8rem; text-align: right; margin-top: 4px; color: var(--text-muted);">
                                        Words: <span x-text="wordCount"></span> / 200
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">
                                    Submit Application <i class="fa-solid fa-paper-plane" style="margin-left: 5px;"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('job-app-form');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const careerId = document.getElementById('career_id').value;
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Submitting <i class="fa-solid fa-spinner fa-spin" style="margin-left: 5px;"></i>';

                const formData = new FormData(form);
                fetch('/careers/' + careerId + '/apply', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => {
                    if (!res.ok) {
                        return res.json().then(data => { throw new Error(data.message || 'Validation failed.') });
                    }
                    return res.json();
                })
                .then(data => {
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: { message: data.message || 'Application submitted successfully!', status: 'success' }
                    }));
                    form.reset();
                    window.dispatchEvent(new CustomEvent('close-apply-form'));
                })
                .catch(err => {
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: { message: err.message || 'Failed to submit application.', status: 'error' }
                    }));
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
            });
        });
    </script>
@endsection
