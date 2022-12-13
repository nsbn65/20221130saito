<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Todo::all();
        $user = Auth::user();
        $tags = Tag::all();
        return view('index',
        [
        'posts' => $posts,
        'user' => $user,
        'tags' => $tags
        ]);
    }

    public function create(PostRequest $request)
    {
        $form = $request->all();
        $form['user_id'] = Auth::id();
        unset($form['_token']);
        Todo::create($form);
        return redirect('/');
    }

    public function update(PostRequest $request)
    {
        $form = $request->all();
        Todo::find($request->id)->update($form);
        return redirect('/');
    }

    public function delete($id)
    {
        $form = Todo::find($id);
        $form->delete();
        return redirect('/');
    }

    public function search()
    {
        $posts = Todo::all();
        $user = Auth::user();
        $tags = Tag::all();
        $tags_list = Tag::all();
        $tags_item = Tag::all();
        return view('search',
        [
        'posts' => $posts,
        'user' => $user,
        'tags' => $tags,
        'keyword' => ''
        ]);
        
    }
    public function find(PostRequest $request)
    {
        $tags = $request->input('tags');
        $keyword = $request->input('keyword');
        $category=Tag::all();
        $query = Todo::query();
        $posts = $query->get();
        if(!empty($tags)) {
            $query->where('tags', 'LIKE', $tags);
        }
        if(!empty($keyword)) {
            $query->where('name', 'LIKE', "%{$keyword}%");
        }
        
        return view('search',
        [
            'posts' => $posts,
            'tags' => $tags,
            'category' => $category
        ]);
    }
}