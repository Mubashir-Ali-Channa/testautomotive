@extends('layouts.app')

@section('title', 'Contact Our Garage & Workshop')
@section('meta_description', 'Get in touch with the TestAutomotive team. Call us, send a message, or visit our custom garage and workshop in Exhaust City.')

@section('content')

    <!-- Inner Hero -->
    <section class="hero-section" style="padding: 60px 0; background-position: center 90%;">
        <div class="container text-center">
            <h1 class="hero-title" style="font-size: 3.5rem; margin-bottom: 10px;">Contact TestAutomotive</h1>
            <span class="hero-subtitle">Get in Touch with Our Builders and Diagnostics Team</span>
        </div>
    </section>

    <!-- Form Section -->
    <section class="section section-light scroll-fade">
        <div class="container">
            <div class="grid grid-2" style="gap: 50px; align-items: start;">
                <div>
                    <h2 style="font-size: 2.2rem; text-transform: uppercase; margin-bottom: 20px;">Send a Message</h2>
                    <p class="text-muted" style="margin-bottom: 30px;">
                        Have questions about a bike restoration, custom exhaust system fitment, dyno tuning slots, or parts availability? Shoot us a message and we'll reply within 24 business hours.
                    </p>

                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label" for="name">Your Name</label>
                            <input type="text" name="name" id="name" required class="form-control" placeholder="John Doe">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email">Your Email</label>
                            <input type="email" name="email" id="email" required class="form-control" placeholder="john@example.com">
                        </div>

                        <div class="form-group" x-data="{ 
                            subjectType: '{{ request('service') ? 'select' : 'select' }}', 
                            selectedVal: '{{ request('service') ? 'Booking Inquiry: ' . request('service') : '' }}', 
                            customVal: '',
                            get customWordCount() {
                                return this.customVal.trim() ? this.customVal.trim().split(/\s+/).length : 0;
                            },
                            enforceCustomLimit(e) {
                                if (this.customWordCount >= 100 && e.keyCode !== 8 && e.keyCode !== 46) {
                                    e.preventDefault();
                                }
                            }
                        }">
                            <label class="form-label" for="subject_select">Subject</label>
                            <select id="subject_select" name="subject_select" class="form-control" @change="subjectType = ($event.target.value === 'other') ? 'other' : 'select'; selectedVal = $event.target.value;" required>
                                <option value="">-- Select Subject --</option>
                                @foreach(App\Models\Service::all() as $svc)
                                    <option value="Booking Inquiry: {{ $svc->title }}" {{ request('service') === $svc->slug ? 'selected' : '' }}>
                                        Booking Inquiry: {{ $svc->title }}
                                    </option>
                                @endforeach
                                <option value="other">Other (Custom Subject)</option>
                            </select>

                            <!-- Custom Subject input, visible only when 'Other' is selected -->
                            <div x-show="subjectType === 'other'" style="margin-top: 12px; display: none;" x-transition>
                                <label class="form-label" for="subject_custom">Custom Subject</label>
                                <input type="text" id="subject_custom" class="form-control" placeholder="Enter your custom subject here" 
                                    x-model="customVal" 
                                    @keydown="enforceCustomLimit($event)"
                                    @input="
                                        let words = customVal.trim().split(/\s+/);
                                        if (words.length > 100) {
                                            customVal = words.slice(0, 100).join(' ');
                                        }
                                    "
                                    :required="subjectType === 'other'">
                                <div style="font-size: 0.8rem; text-align: right; margin-top: 4px; color: var(--text-muted);">
                                    Words: <span x-text="customWordCount"></span> / 100
                                </div>
                            </div>

                            <!-- Hidden field that passes the final parsed value to validation -->
                            <input type="hidden" name="subject" :value="subjectType === 'other' ? customVal : selectedVal">
                        </div>

                        <div class="form-group" x-data="{ 
                            message: '', 
                            get wordCount() {
                                return this.message.trim() ? this.message.trim().split(/\s+/).length : 0;
                            },
                            enforceLimit(e) {
                                if (this.wordCount >= 200 && e.keyCode !== 8 && e.keyCode !== 46) {
                                    e.preventDefault();
                                }
                            }
                        }">
                            <label class="form-label" for="message">Message</label>
                            <textarea name="message" id="message" required class="form-control" placeholder="Write details about your bike and request here..."
                                x-model="message"
                                @keydown="enforceLimit($event)"
                                @input="
                                    let words = message.trim().split(/\s+/);
                                    if (words.length > 200) {
                                        message = words.slice(0, 200).join(' ');
                                    }
                                "></textarea>
                            <div style="font-size: 0.8rem; text-align: right; margin-top: 4px; color: var(--text-muted);">
                                Words: <span x-text="wordCount"></span> / 200
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-top: 10px;">
                            Send Message <i class="fa-solid fa-paper-plane" style="margin-left: 5px;"></i>
                        </button>
                    </form>
                </div>

                <div>
                    <h2 style="font-size: 2.2rem; text-transform: uppercase; margin-bottom: 20px;">Other Ways to Connect</h2>
                    
                    <div style="margin-bottom: 30px;">
                        <h4 style="color: var(--primary); text-transform: uppercase; font-size: 1rem; margin-bottom: 5px;"><i class="fa-solid fa-location-dot"></i> Visit the Garage</h4>
                        <p style="font-size: 1.05rem; color: var(--text-dark);">
                            {{ App\Models\Setting::get('address', '789 Throttle Lane, Exhaust City, EC 90210') }}
                        </p>
                    </div>

                    <div style="margin-bottom: 30px;">
                        <h4 style="color: var(--primary); text-transform: uppercase; font-size: 1rem; margin-bottom: 5px;"><i class="fa-solid fa-phone"></i> Phone Support</h4>
                        <p style="font-size: 1.05rem; color: var(--text-dark);">
                            {{ App\Models\Setting::get('contact_phone', '+1 (555) 123-4567') }}
                        </p>
                        <p class="text-muted" style="font-size: 0.85rem; margin-top: 2px;">Calls answered Mon-Sat, 8:00 AM - 6:00 PM PST.</p>
                    </div>

                    <div style="margin-bottom: 30px;">
                        <h4 style="color: var(--primary); text-transform: uppercase; font-size: 1rem; margin-bottom: 5px;"><i class="fa-solid fa-envelope"></i> General Enquiries</h4>
                        <p style="font-size: 1.05rem; color: var(--text-dark);">
                            {{ App\Models\Setting::get('contact_email', 'info@testautomotive.com') }}
                        </p>
                    </div>

                    <div class="card" style="padding: 25px; border-color: var(--border-light); background-color: var(--bg-white); margin-top: 40px;">
                        <h4 style="text-transform: uppercase; font-size: 1.2rem; margin-bottom: 8px;">Dealers & Resellers</h4>
                        <p class="text-muted" style="font-size: 0.9rem; line-height: 1.5;">
                            Interested in retailing TestAutomotive custom graphics or merchandise? Shoot an email with "Reseller Application" in the subject line to our wholesale email: <strong class="text-primary">wholesale@testautomotive.com</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>



@endsection
