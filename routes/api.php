<?php

use App\Models\Catatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/catatan', function (Request $request) {
    return Catatan::with('user')->get();
});

Route::post('/catatan', function (Request $request) {
    $catatan = new Catatan();
    $catatan->user_id = $request->input('user_id');
    $catatan->title = $request->input('title');
    $catatan->content = $request->input('content');
    $catatan->save();

    return response()->json($catatan);
});

Route::get('/catatan/{id}', function ($id) {
    $catatan = Catatan::with('user')->find($id);

    if (!$catatan) {
        return response()->json(['message' => 'Catatan tidak ditemukan'], 404);
    }

    return response()->json($catatan);
});


Route::put('/catatan/{id}', function (Request $request, $id) {
    $catatan = Catatan::find($id);

    if (!$catatan) {
        return response()->json(['message' => 'Catatan tidak ditemukan'], 404);
    }

    $catatan->title = $request->input('title');
    $catatan->content = $request->input('content');

    if ($request->input('user_id')) {
        $catatan->user_id = $request->input('user_id');
    }

    $catatan->save();

    return response()->json($catatan);
});

Route::delete('/catatan/{id}', function ($id) {
    $catatan = Catatan::find($id);

    if (!$catatan) {
        return response()->json(['message' => 'Catatan tidak ditemukan'], 404);
    }

    $catatan->delete();

    return response()->json(['message' => 'Catatan berhasil dihapus']);
});

// Pembatasan API untuk User dan Catatan

Route::get('/users', function (Request $request) {
    return response()->json(User::get());
});

Route::post('/users', function (Request $request) {
    $user = new User();
    $user->name = $request->input('name');
    $user->email = $request->input('email');
    $user->password = bcrypt($request->input('password'));
    $user->save();

    return response()->json($user);
});

Route::get('/users/{id}', function ($id) {
    $user = User::find($id);
    return response()->json($user);
});

Route::put('/users/{id}', function (Request $request, $id) {
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'User tidak ditemukan'], 404);
    }

    $user->name = $request->input('name');
    $user->email = $request->input('email');

    if ($request->input('password')) {
        $user->password = bcrypt($request->input('password'));
    }

    $user->save();

    return response()->json($user);
});

Route::delete('/users/{id}', function ($id) {
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'User tidak ditemukan'], 404);
    }

    $user->delete();

    return response()->json(['message' => 'User berhasil dihapus']);
});

