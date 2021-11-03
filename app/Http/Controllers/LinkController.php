<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Link;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LinkController extends Controller
{
    // метод-MakeShortLink берет ссылку из формы создает токен и записывает в бд 
     public function MakeShortLink(Request $req){
        /* валидация - ссылка не может быть пустой
        и должна быть правильной*/  
        $validation = $req->validate([
             "url" => "required|url"
         ]);
         // подготовка обьекта
         $data = new Link();
         // установим переданный URL ссылки
         $data->link = $req->input('url');
         // установим токен для URL
         $data->token = Str::random(6);
         // записываем в БД
         return env('APP_URL') . $data->token;
         if($data->save()){
             return appUrl() . $data->token;
         };
     }

     function RedirectLink($token){
        // вытаскиваем URL который подходит нашему токену
        $url = DB::table('links')->where('token',$token)->value('link');
        // перенаправляем в нужный URL
        return redirect()->to($url);
     }
}