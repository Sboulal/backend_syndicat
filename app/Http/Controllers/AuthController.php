<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 1. دالة تسجيل الدخول (Login)
    public function login(Request $request)
    {
        // أولا: كنتأكدو بلي اليوزر صيفط لينا الإيميل والمودپاس
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // ثانيا: كنقلبو على اليوزر بهاد الإيميل فـ لاباز دوني
        $user = User::where('email', $request->email)->first();

        // ثالثا: كنتأكدو واش اليوزر كاين، وواش المودپاس صحيح
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email ou mot de passe incorrect.' // إيميل أو مودپاس غالط
            ], 401);
        }

        // رابعا: يلا كان كلشي صحيح، كنكرييو ليه Token (Sanctum)
        $token = $user->createToken('auth_token')->plainTextToken;

        // خامسا: كنصيفطو ليه الـ Token ومعلومات ديالو باش يخدم بيهم فـ Flutter
        return response()->json([
            'message' => 'Connexion réussie',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 200);
    }

    // 2. دالة تسجيل الخروج (Logout)
    public function logout(Request $request)
    {
        // كنمسحو الـ Token الحالي باش ميبقاش خدام
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie'
        ], 200);
    }

    // 3. دالة جلب معلومات المستخدم (Profile)
    public function profile(Request $request)
    {
        // كنرجعو المعلومات ديال اليوزر المكونيكطي + الشقق اللي عندو
        $user = $request->user()->load('lots');

        return response()->json([
            'user' => $user
        ], 200);
    }
}