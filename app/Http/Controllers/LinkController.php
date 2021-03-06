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
         try {
            $data->save();
          } catch (\Illuminate\Database\QueryException $e) {
            if($e->errorInfo[0]==23000) return "данную ссылку уже вводили";
            else return $e->errorInfo[2];
          }
     }

     function RedirectLink($token){
        $link = new Link();
        $url = $link->where('token', '=', $token)->get();
        if(isset($url[0]->link))return redirect()->to($url[0]->link);
        abort(404);
     }
}