<div style="display: flex; flex-direction: column; gap: 40px;">
    
    <!-- Profile Info Form -->
    <div class="card" style="padding: 30px; background-color: var(--bg-card);">
        <h3 style="font-size: 1.5rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--border-dark); padding-bottom: 10px;">Contact & Shipping Info</h3>
        
        <form wire:submit.prevent="updateProfile">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label" for="profile-name">Full Name</label>
                    <input type="text" id="profile-name" wire:model="name" required class="form-control">
                    @error('name') <span class="text-danger" style="font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="profile-email">Email Address</label>
                    <input type="email" id="profile-email" wire:model="email" required class="form-control">
                    @error('email') <span class="text-danger" style="font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label" for="profile-phone">Phone Number</label>
                    <input type="text" id="profile-phone" wire:model="phone" class="form-control" placeholder="+1 (555) 000-0000">
                    @error('phone') <span class="text-danger" style="font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="profile-zip">ZIP / Postcode</label>
                    <input type="text" id="profile-zip" wire:model="zip" class="form-control" placeholder="e.g. 90210">
                    @error('zip') <span class="text-danger" style="font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label" for="profile-address">Address</label>
                    <input type="text" id="profile-address" wire:model="address" class="form-control" placeholder="123 Throttle Rd, Appt 5">
                    @error('address') <span class="text-danger" style="font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="profile-city">City / Town</label>
                    <input type="text" id="profile-city" wire:model="city" class="form-control" placeholder="Exhaust City">
                    @error('city') <span class="text-danger" style="font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top: 10px;">
                Save Changes <i class="fa-solid fa-save" style="margin-left: 5px;"></i>
            </button>
        </form>
    </div>

    <!-- Password Reset Form -->
    <div class="card" style="padding: 30px; background-color: var(--bg-card);">
        <h3 style="font-size: 1.5rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--border-dark); padding-bottom: 10px;">Reset Password</h3>
        
        <form wire:submit.prevent="updatePassword">
            <div class="form-group">
                <label class="form-label" for="current_password">Current Password</label>
                <input type="password" id="current_password" wire:model="current_password" required class="form-control" placeholder="••••••••">
                @error('current_password') <span class="text-danger" style="font-size: 0.85rem;">{{ $message }}</span> @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label" for="new_password">New Password</label>
                    <input type="password" id="new_password" wire:model="new_password" required class="form-control" placeholder="••••••••">
                    @error('new_password') <span class="text-danger" style="font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="new_password_confirmation">Confirm New Password</label>
                    <input type="password" id="new_password_confirmation" wire:model="new_password_confirmation" required class="form-control" placeholder="••••••••">
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top: 10px;">
                Reset Password <i class="fa-solid fa-key" style="margin-left: 5px;"></i>
            </button>
        </form>
    </div>

</div>
