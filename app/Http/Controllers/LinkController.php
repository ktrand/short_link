<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Link;
use Illuminate\Support\Facades\DB;

class LinkController extends Controller
{
    // метод-make_short_link берет ссылку из формы создает токен и записывает в бд 
     public function make_short_link(Request $req){
        /* валидация - ссылка не может быть пустой
        и должна быть правильной*/  
        $validation = $req->validate([
             "url" => "required|url"
         ]);
         // подготовка обьекта
         $data = new Link();
         // установим переданный URL ссылки
         $data->link = $req->input('url');
         $hash = md5($req->input('url'));
         // установим токен для URL
         $data->token = substr($hash,0,6);
         // записываем в БД
         if($data->save()){
             return 'http://short-link/' . $data->token;
         };
     }

     function redirect_link($token){
        // вытаскиваем URL который подходит нашему токену
        $url = DB::table('links')->where('token',$token)->value('link');
        // перенаправляем в нужный URL
        return redirect()->to($url);
     }
}