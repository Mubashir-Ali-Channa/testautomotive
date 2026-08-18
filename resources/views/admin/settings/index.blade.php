@extends('layouts.admin')

@section('content')

    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 2rem; text-transform: uppercase;">Global Site Settings</h2>
        <p class="text-muted">Configure variables shown across the storefront headers, footers, maps, and contacts</p>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" class="card" style="padding: 35px; max-width: 800px;">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label" for="site_name">Site Title</label>
                <input type="text" name="site_name" id="site_name" required class="form-control" value="{{ $settings['site_name'] ?? 'TestAutomotive Specialist' }}">
            </div>
            
            <div class="form-group">
                <label class="form-label" for="contact_phone">Contact Phone Number</label>
                <input type="text" name="contact_phone" id="contact_phone" required class="form-control" value="{{ $settings['contact_phone'] ?? '+1 (555) 123-4567' }}">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label" for="contact_email">General Contact Email</label>
                <input type="email" name="contact_email" id="contact_email" required class="form-control" value="{{ $settings['contact_email'] ?? 'info@testautomotive.com' }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="map_coordinates">Map Coordinates (Lat,Lng)</label>
                <input type="text" name="map_coordinates" id="map_coordinates" required class="form-control" placeholder="e.g. 34.0522,-118.2437" value="{{ $settings['map_coordinates'] ?? '34.0522,-118.2437' }}">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="address">Garage Address</label>
            <input type="text" name="address" id="address" required class="form-control" value="{{ $settings['address'] ?? '789 Throttle Lane, Exhaust City, EC 90210' }}">
        </div>

        <div class="form-group">
            <label class="form-label" for="opening_hours">Opening Hours String</label>
            <input type="text" name="opening_hours" id="opening_hours" required class="form-control" value="{{ $settings['opening_hours'] ?? 'Mon - Fri: 8:00 AM - 6:00 PM, Sat: 9:00 AM - 4:00 PM' }}">
        </div>

        <div class="form-group">
            <label class="form-label">Drag Marker to Pinpoint Location</label>
            <div id="settings-map" style="height: 300px; border-radius: 4px; border: 1px solid var(--border-light); z-index: 5;"></div>
            <p class="text-muted" style="font-size: 0.8rem; margin-top: 5px;">Drag the marker or click anywhere on the map to automatically update the map coordinates field above.</p>
        </div>

        <h3 style="font-size: 1.3rem; text-transform: uppercase; margin-top: 40px; margin-bottom: 20px; border-bottom: 1px solid var(--border-dark); padding-bottom: 8px;">Social Profile Links</h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label class="form-label" for="facebook_url"><i class="fa-brands fa-facebook"></i> Facebook URL</label>
                <input type="text" name="facebook_url" id="facebook_url" class="form-control" value="{{ $settings['facebook_url'] ?? '' }}">
            </div>
            
            <div class="form-group">
                <label class="form-label" for="instagram_url"><i class="fa-brands fa-instagram"></i> Instagram URL</label>
                <input type="text" name="instagram_url" id="instagram_url" class="form-control" value="{{ $settings['instagram_url'] ?? '' }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="twitter_url"><i class="fa-brands fa-twitter"></i> Twitter / X URL</label>
                <input type="text" name="twitter_url" id="twitter_url" class="form-control" value="{{ $settings['twitter_url'] ?? '' }}">
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 200px; height: 48px; margin-top: 20px;">
            Save Settings <i class="fa-solid fa-save" style="margin-left: 5px;"></i>
        </button>
    </form>

@endsection

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const coordInput = document.getElementById('map_coordinates');
            let coords = coordInput.value.split(',');
            let lat = parseFloat(coords[0]) || 34.0522;
            let lng = parseFloat(coords[1]) || -118.2437;

            const map = L.map('settings-map').setView([lat, lng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            const marker = L.marker([lat, lng], { draggable: true }).addTo(map);

            function updateCoords(newLat, newLng) {
                coordInput.value = `${newLat.toFixed(6)},${newLng.toFixed(6)}`;
            }

            marker.on('dragend', function (e) {
                const position = marker.getLatLng();
                updateCoords(position.lat, position.lng);
            });

            map.on('click', function (e) {
                marker.setLatLng(e.latlng);
                updateCoords(e.latlng.lat, e.latlng.lng);
            });

            coordInput.addEventListener('change', () => {
                let currentCoords = coordInput.value.split(',');
                let newLat = parseFloat(currentCoords[0]);
                let newLng = parseFloat(currentCoords[1]);
                if (!isNaN(newLat) && !isNaN(newLng)) {
                    marker.setLatLng([newLat, newLng]);
                    map.setView([newLat, newLng], 13);
                }
            });
        });
    </script>
@endsection
