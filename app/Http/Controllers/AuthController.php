<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
    
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'nullable|string|in:user',
            'birthdate_thai' => 'required|date', 
            'Category_code' => 'required|in:1,2,3,4,5',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $birthdate = \Carbon\Carbon::createFromFormat('Y-m-d', $request->birthdate_thai)->subYears(543);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'user',
            'birthdate' => $birthdate,
            'Category_code' => $request->Category_code,
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'token' => $token,
            'user' => Auth::user()
        ]);
    }

    

    public function me()
    {
        try {
            if (!$user = auth()->user()) {
                return response()->json(['error' => 'User not found'], 404);
            }

            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ?? 'user', // ถ้าไม่มี role จะกำหนดเป็น 'user'
                'Category_code' => $user->Category_code,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    

    public function logout()
    {
        auth()->logout();
    
        return response()->json(['message' => 'Successfully logged out']);
    }
    

    
    public function updateProfile(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'name' => 'string|max:255',
        'email' => 'email|max:255|unique:users,email,' . $user->id,
        'password' => 'nullable|min:6',
         // อนุญาตให้ระบุ 'role' ได้ถ้าเป็น admin
         'role' => 'nullable|string|in:user,admin', 
    ]);

    if ($request->has('name')) {
        $user->name = $request->name;
    }

    if ($request->has('email')) {
        $user->email = $request->email;
    }

    if ($request->has('password')) {
        $user->password = bcrypt($request->password);
    }

    if ($request->has('role') && $user->role === 'admin') {
        $user->role = $request->role; // admin สามารถเปลี่ยน role ได้
    }

    $user->save();

    return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
}

public function updateUserRole(Request $request, $id)
{
    // ตรวจสอบว่า user ที่ล็อกอินเป็น admin หรือไม่
    $authenticatedUser = auth()->user();
    if ($authenticatedUser->role !== 'admin') {
        return response()->json(['error' => 'Unauthorized'], 403); // ถ้าไม่ใช่ admin จะไม่สามารถเปลี่ยน role ได้
    }

    // หาผู้ใช้ที่ต้องการจะอัปเดต role
    $user = User::find($id);
    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    // ตรวจสอบว่า role ที่ส่งมา valid หรือไม่
    $request->validate([
        'role' => 'required|string|in:user,admin,Advanced_users,viewer', // เพิ่ม role อื่นๆ ถ้าจำเป็น
    ]);

    // อัปเดต role
    $user->role = $request->role;
    $user->save();

    return response()->json(['message' => 'User role updated successfully', 'user' => $user]);
}

}
