<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    public function index(Request $request) : Response
    {
        $posts = Post::query()->when($request->input('search'), function ($q, $k){
            $q->where('name', 'like', "%$k%");
        })
            ->latest('id')->paginate(50)->through(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'src' => $item->src,
                'size' => $item->size,
                'file_ext' => $item->file_ext,
            ];
        });

        return Inertia::render('Post/Index', ['title' => 'Posts', 'appName' => config('app.name', 'Inertia'),'posts' => $posts, 'search' => $request->input('search')]);
    }

    public function create() : Response
    {
        return Inertia::render('Post/Create', ['title' => 'Создание']);
    }

    public function edit(Post $post) : Response
    {
        return Inertia::render('Post/Create', ['post' => $post, 'isUpdating' => true, 'title' => 'Редактирование']);
    }

    public function store(Request $request) : RedirectResponse
    {
        sleep(5);
        $request->validate([
            'file' => 'file|max:8000'
        ]);

        $post = new Post();

        $data = $this->fileUp($request);

        $post->name = $data['name'];
        $post->src = $data['src'];
        $post->size = $data['size'];
        $post->file_ext = $data['file_ext'];

        $post->save();

        return redirect()->route('posts.index')->with('success', 'asd');
    }

    public function update(Request $request, Post $post) : RedirectResponse
    {
        $request->validate([
            'file' => 'file|max:8000'
        ]);

        if ($request->file('file'))
        {
            // Удаляем файл
            $this->fileDel($post->src);

            $data = $this->fileUp($request);

            $post->name = $data['name'];
            $post->src = $data['src'];
            $post->size = $data['size'];
            $post->file_ext = $data['file_ext'];
        }
        else
            $post->name = $request->name;

        $post->save();

        return redirect()->route('posts.index');
    }

    public function destroy(Post $post) : RedirectResponse
    {
        // Удаляем файл
        $this->fileDel($post->src);

        // удаляем запись
        $post->delete();
        return redirect()->back();
    }

    private function fileUp($request) : array
    {
        $contents = file_get_contents($request->file);
        $name = $request->file('file')->getClientOriginalName();

        // убрать в названии генерированные символы
        $position = strpos($name, '?');

        if ($position !== false) {
            $name = substr(basename($request->file), 0, $position);
        }

        // добавляем таймстамп от дубликатов
        $filename = now()->timestamp . '_' . $name;

        Storage::disk('public')->put('uploads/' . $filename, $contents);

        $data = [];
        $data['name'] = $request->name ?? $name;
        $data['src'] = 'uploads/' . $filename;
        $data['size'] = round($request->file('file')->getSize()/1000000, 2);
        $data['file_ext'] = strtolower($request->file('file')->getClientOriginalExtension());

        return $data;
    }

    private function fileDel($x) : void
    {
        // проверяем есть ли он и удаляем если есть
        if(Storage::disk('public')->exists($x))
            Storage::disk('public')->delete($x);
    }
}
