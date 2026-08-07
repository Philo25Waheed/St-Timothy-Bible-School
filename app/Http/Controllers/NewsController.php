<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    public function index()
    {
        $newsList = News::with('author')->latest()->paginate(10);
        return view('news.index', compact('newsList'));
    }

    public function create()
    {
        return view('news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        News::create([
            'title' => $request->title,
            'content' => $request->content,
            'author_id' => Auth::id(),
            'is_published' => true,
            'published_at' => now(),
        ]);

        return redirect()->route('news.index')->with('success', 'تم نشر الخبر بنجاح.');
    }

    public function show(News $news)
    {
        return view('news.show', compact('news'));
    }
}
