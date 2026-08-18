<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UserProfileSettings extends Component
{
    public $name;
    public $email;
    public $phone;
    public $address;
    public $city;
    public $zip;

    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->address = $user->address;
        $this->city = $user->city;
        $this->zip = $user->zip;
    }

    public function updateProfile()
    {
        $user = User::find(Auth::id());

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:50',
        ]);

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'zip' => $this->zip,
        ]);

        $this->dispatch('toast', message: 'Profile details saved successfully.', status: 'success');
    }

    public function updatePassword()
    {
        $user = User::find(Auth::id());

        $this->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($this->current_password, $user->password)) {
            $this->dispatch('toast', message: 'Current password does not match.', status: 'error');
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        $this->dispatch('toast', message: 'Password reset successfully.', status: 'success');
    }

    public function render()
    {
        return view('livewire.user-profile-settings');
    }
}
