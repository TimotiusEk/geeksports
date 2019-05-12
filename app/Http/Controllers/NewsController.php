<?php

namespace App\Http\Controllers;

use App\News;
use Illuminate\Http\Request;
use App\Flight;

class NewsController extends Controller
{
    public function getAllWithCondition($id)
    {
        $news = News::where('writer_id', '=', $id)->get();

        return view('broadcast_news', ['news' => $news]);
    }

}
