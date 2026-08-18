@extends('layouts.app')

@section('title', 'About Our Workshop & Team')
@section('meta_description', 'Learn about the history of TestAutomotive, our expert mechanics, and our absolute passion for engineering high-performance motorcycles.')

@section('content')

    <!-- Inner Hero -->
    <section class="hero-section" style="padding: 60px 0; background-position: center 30%;">
        <div class="container text-center">
            <h1 class="hero-title" style="font-size: 3.5rem; margin-bottom: 10px;">About Our Garage</h1>
            <span class="hero-subtitle">The TestAutomotive Story & Team</span>
        </div>
    </section>

    <!-- Content details -->
    <section class="section section-light scroll-fade">
        <div class="container">
            <div class="grid grid-2" style="align-items: center; gap: 50px;">
                <div>
                    <h2 style="font-size: 2.5rem; text-transform: uppercase; margin-bottom: 20px;">Built by Riders, for Riders</h2>
                    <p style="margin-bottom: 20px; font-size: 1.05rem;">
                        Founded in 2008 by custom builder Ryder Vance, TestAutomotive started as a small, one-car garage with a single welder and a dream to craft unique Cafe Racers. Over the years, we have grown into one of the country's most respected custom motorcycle and tuning shops.
                    </p>
                    <p style="color: var(--text-muted); margin-bottom: 30px;">
                        We believe that a motorcycle is not just a mode of transportation—it's an extension of the rider's personality. That’s why we approach every oil change, brake calibration, dyno-tune, and full ground-up build with the same level of absolute precision and artistic dedication.
                    </p>
                    <div class="flex" style="gap: 15px;">
                        <a href="{{ route('contact') }}" class="btn btn-primary">Book an Appointment</a>
                        <a href="{{ route('mechanics') }}" class="btn btn-secondary">Meet Our Specialists</a>
                    </div>
                </div>
                <div>
                    <img src="https://images.unsplash.com/photo-1449426468159-d96dbf08f19f?w=600" alt="Motorcycle Shop" style="border-radius: 4px; border: 1px solid var(--border-light); width: 100%;">
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="section section-grey scroll-fade" style="border-top: 1px solid var(--border-light); border-bottom: 1px solid var(--border-light);">
        <div class="container">
            <div class="grid grid-3 text-center">
                <!-- Years of Craft -->
                <div class="kpi-card" style="padding: 40px 20px;" x-data="{ 
                    currentVal: 0, 
                    targetVal: 18,
                    startCount() {
                        let duration = 2000;
                        let startTime = null;
                        const animate = (timestamp) => {
                            if (!startTime) startTime = timestamp;
                            const progress = Math.min((timestamp - startTime) / duration, 1);
                            const easeOutCubic = 1 - Math.pow(1 - progress, 3);
                            this.currentVal = Math.floor(easeOutCubic * this.targetVal);
                            if (progress < 1) {
                                requestAnimationFrame(animate);
                            } else {
                                this.currentVal = this.targetVal;
                            }
                        };
                        requestAnimationFrame(animate);
                    }
                }" x-init="
                    const observer = new IntersectionObserver((entries) => {
                        if (entries[0].isIntersecting) {
                            startCount();
                            observer.disconnect();
                        }
                    }, { threshold: 0.1 });
                    observer.observe($el);
                ">
                    <div class="kpi-val" style="font-size: 3.5rem; color: var(--primary); font-family: var(--font-heading); font-weight: 800;"><span x-text="currentVal"></span>+</div>
                    <div class="kpi-title" style="font-size: 1.1rem; margin-top: 10px;">Years of Craft</div>
                </div>

                <!-- Custom Bikes Built -->
                <div class="kpi-card" style="padding: 40px 20px;" x-data="{ 
                    currentVal: 0, 
                    targetVal: 1200,
                    startCount() {
                        let duration = 2000;
                        let startTime = null;
                        const animate = (timestamp) => {
                            if (!startTime) startTime = timestamp;
                            const progress = Math.min((timestamp - startTime) / duration, 1);
                            const easeOutCubic = 1 - Math.pow(1 - progress, 3);
                            this.currentVal = Math.floor(easeOutCubic * this.targetVal);
                            if (progress < 1) {
                                requestAnimationFrame(animate);
                            } else {
                                this.currentVal = this.targetVal;
                            }
                        };
                        requestAnimationFrame(animate);
                    }
                }" x-init="
                    const observer = new IntersectionObserver((entries) => {
                        if (entries[0].isIntersecting) {
                            startCount();
                            observer.disconnect();
                        }
                    }, { threshold: 0.1 });
                    observer.observe($el);
                ">
                    <div class="kpi-val" style="font-size: 3.5rem; color: var(--primary); font-family: var(--font-heading); font-weight: 800;"><span x-text="currentVal.toLocaleString()"></span>+</div>
                    <div class="kpi-title" style="font-size: 1.1rem; margin-top: 10px;">Custom Bikes Built</div>
                </div>

                <!-- Rider Satisfaction -->
                <div class="kpi-card" style="padding: 40px 20px;" x-data="{ 
                    currentVal: 0.0, 
                    targetVal: 4.9,
                    startCount() {
                        let duration = 2000;
                        let startTime = null;
                        const animate = (timestamp) => {
                            if (!startTime) startTime = timestamp;
                            const progress = Math.min((timestamp - startTime) / duration, 1);
                            const easeOutCubic = 1 - Math.pow(1 - progress, 3);
                            this.currentVal = parseFloat((easeOutCubic * this.targetVal).toFixed(1));
                            if (progress < 1) {
                                requestAnimationFrame(animate);
                            } else {
                                this.currentVal = this.targetVal;
                            }
                        };
                        requestAnimationFrame(animate);
                    }
                }" x-init="
                    const observer = new IntersectionObserver((entries) => {
                        if (entries[0].isIntersecting) {
                            startCount();
                            observer.disconnect();
                        }
                    }, { threshold: 0.1 });
                    observer.observe($el);
                ">
                    <div class="kpi-val" style="font-size: 3.5rem; color: var(--primary); font-family: var(--font-heading); font-weight: 800;"><span x-text="currentVal.toFixed(1)"></span>★</div>
                    <div class="kpi-title" style="font-size: 1.1rem; margin-top: 10px;">Rider Satisfaction Rating</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values -->
    <section class="section section-light scroll-fade">
        <div class="container">
            <div class="text-center" style="margin-bottom: 50px;">
                <h2 style="font-size: 2.8rem; text-transform: uppercase;">Our Core Philosophies</h2>
                <p class="text-muted" style="max-width: 500px; margin: 10px auto 0;">What drives us to build and tune the finest machines on the road.</p>
            </div>

            <div class="grid grid-3">
                <div class="card" style="padding: 30px;">
                    <div style="font-size: 2.2rem; color: var(--primary); margin-bottom: 15px;"><i class="fa-solid fa-gauge-high"></i></div>
                    <h3 class="card-title" style="font-size: 1.4rem;">Performance Driven</h3>
                    <p class="text-muted">We don’t compromise on engine tuning or component calibration. If it doesn’t improve performance, it doesn’t go on the bike.</p>
                </div>
                <div class="card" style="padding: 30px;">
                    <div style="font-size: 2.2rem; color: var(--primary); margin-bottom: 15px;"><i class="fa-solid fa-compass-drafting"></i></div>
                    <h3 class="card-title" style="font-size: 1.4rem;">Artistic Precision</h3>
                    <p class="text-muted">Fabrication is clean, hand-crafted, and aesthetically balanced. We focus on clean lines, hidden wiring, and custom metal shapes.</p>
                </div>
                <div class="card" style="padding: 30px;">
                    <div style="font-size: 2.2rem; color: var(--primary); margin-bottom: 15px;"><i class="fa-solid fa-shield-halved"></i></div>
                    <h3 class="card-title" style="font-size: 1.4rem;">Rider Safety First</h3>
                    <p class="text-muted">Every customized part and performance tune goes through safety check guidelines, track tests, and dyno inspections.</p>
                </div>
            </div>
        </div>
    </section>

@endsection
