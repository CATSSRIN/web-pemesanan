<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class AdminList extends Component
{
    use WithPagination;

    public $search = '';
    public $isOpen = false;
    public $adminId;
    public $name, $email, $password;
    public $is_active = true;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset(['adminId', 'name', 'email', 'password', 'is_active']);
    }

    public function create()
    {
        $this->reset(['adminId', 'name', 'email', 'password', 'is_active']);
        $this->openModal();
    }

    public function edit($id)
    {
        $admin = User::findOrFail($id);
        $this->adminId = $id;
        $this->name = $admin->name;
        $this->email = $admin->email;
        $this->is_active = $admin->is_active;
        $this->openModal();
    }

    public function store()
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->adminId,
        ];
        
        if (!$this->adminId) {
            $rules['password'] = 'required|min:6';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'is_active' => $this->is_active,
        ];

        if ($this->password) {
            $data['password'] = bcrypt($this->password);
        }

        $user = User::updateOrCreate(['id' => $this->adminId], $data);
        if (!$this->adminId) {
            $user->assignRole('admin');
        }

        session()->flash('message', $this->adminId ? 'Admin diperbarui.' : 'Admin ditambahkan.');
        $this->closeModal();
    }

    public function destroy($id)
    {
        $admin = User::findOrFail($id);

        if ((int) $admin->id === (int) auth()->id()) {
            session()->flash('message', 'Akun yang sedang digunakan tidak bisa dihapus.');
            return;
        }

        if (User::role('admin')->count() <= 1) {
            session()->flash('message', 'Minimal harus ada 1 admin aktif di sistem.');
            return;
        }

        $admin->delete();
        session()->flash('message', 'Admin berhasil dihapus.');
    }

    public function render()
    {
        $admins = User::role('admin')
            ->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin-list', ['admins' => $admins])->layout('layouts.app', ['header' => 'Daftar Admin']);
    }
}
