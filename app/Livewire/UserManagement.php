<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UserManagement extends Component
{
    public $users;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $is_admin = 0;
    public $editId = null;

    public function mount()
    {
        $this->refreshUsers();
    }

    public function refreshUsers()
    {
        $this->users = User::all();
    }

    public function create()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'is_admin' => 'required|boolean',
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'is_admin' => $this->is_admin,
        ]);

        $this->resetForm();
        $this->refreshUsers();
        session()->flash('success', 'User created successfully!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->editId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->is_admin = $user->is_admin;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->editId,
            'password' => 'nullable|string|min:8|confirmed',
            'is_admin' => 'required|boolean',
        ]);

        $user = User::findOrFail($this->editId);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password ? Hash::make($this->password) : $user->password,
            'is_admin' => $this->is_admin,
        ]);

        $this->resetForm();
        $this->refreshUsers();
        session()->flash('success', 'User updated successfully!');
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        $this->refreshUsers();
        session()->flash('success', 'User deleted successfully!');
    }

    public function resetForm()
    {
        $this->editId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->is_admin = 0;
    }

    public function render()
    {
        return view('livewire.user-management');
    }
}
