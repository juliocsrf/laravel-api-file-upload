<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('upload', function(Request $request) {
    $array = ['error' => ''];
    $rules = [
        'name' => ['required', 'min:2'],
        'foto' => ['mimes:jpg,png', 'file', 'required']
    ];

    $validator = Validator::make($request->all(), $rules);

    if($validator->fails()){
        $array['error'] = $validator->messages();
        return $array;
    }

    if($request->hasFile('foto')) {
        if($request->file('foto')->isValid()){
            $foto = $request->file('foto')->store('public');
            $url = asset(Storage::url($foto));
            $array['foto'] = $url;
            return $array;
        } else {
            $array['error'] = 'Arquivo corrompido durante upload';
        }
    } else {
        $array['error'] = 'Não foi enviado arquivo';
    }

    return $array;
});