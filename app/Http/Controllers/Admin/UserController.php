<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index');
    }

    // متد جدید برای دریافت داده‌های جدول با Ajax
    public function getData()
    {
        $users = User::query();

        return DataTables::of($users)
            ->addIndexColumn()
            ->editColumn('name', function ($user) {
                return $user->name ?? 'نامشخص';
            })
            ->editColumn('email', function ($user) {
                return $user->email ?? 'نامشخص';
            })
            ->addColumn('actions', function ($user) {
                return view('admin.users.actions', compact('user'))->render();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|regex:/^09[0-9]{9}$/|unique:users,phone_number',
            'role' => 'required|in:admin,staff,user',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'کاربر با موفقیت ایجاد شد.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'required|regex:/^09[0-9]{9}$/|unique:users,phone_number,' . $user->id,
            'role' => 'required|in:admin,employee,user',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'role' => $validated['role'],
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'کاربر با موفقیت به‌روزرسانی شد.');
    }

    public function destroy(User $user)
    {
        // جلوگیری از حذف خود ادمین
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'شما نمی‌توانید خودتان را حذف کنید!');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'کاربر با موفقیت حذف شد.');
    }
}