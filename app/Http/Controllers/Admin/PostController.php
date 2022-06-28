<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Mail\NewPostCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderByDesc('id')->get();
        //dd($posts);
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::All();
        $tags = Tag::all();

        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PostRequest;  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        //dd($request->all());

        //validate data
        $val_data = $request->validated();

        //se l'id esiste tra gli id della tabella categories 
        

        //generate the slug
        $slug = Str::slug($request->title, '-');
        //dd($slug);
        $val_data['slug'] = $slug;
        //dd($val_data);

        //verificare se la richiesta contiene un file

        if ($request->hasFile('cover_image')) {
            //valida il file
            $request->validate([
                'cover_image' => 'nullable|image|max:250',
            ]);

            $path = Storage::put('post_image', $request->cover_image);
            //dd($path);

            $val_data['cover_image'] = $path;
        }



        //create the resource
        $new_post = Post::create($val_data);
        // $new_post->cover_image = $path;
        // $new_post->save();
        $new_post->tags()->attach($request->tags);

        //view mail anteprima
        //return (new NewPostCreated($new_post))->render();


        Mail::to($request->user())->send(new NewPostCreated($new_post));

        //redirect to a get route
        return redirect()->route('admin.posts.index')->with('message', 'Post Created gj!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\PostRequest;  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //dd($request->all());
        /*validate data con PostRequest*/
        //$val_data = $request->validated();

        /*validate data unique*/
        $val_data = $request->validate([
            'title' => ['required', Rule::unique('posts')->ignore($post)],
            'cover_image' => 'nullable',
            'content' => 'nullable',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'exists:tags,id',
        ]);

        //dd($val_data);
        //generate slug
        $slug = Str::slug($request->title, '-');
        $val_data['slug'] = $slug;

        //file

        if ($request->hasFile('cover_image')) {
            $request->validate([
                'cover_image' => 'nullable|image|max:250',
            ]);

            //salvo nel filesystem
            Storage::delete($post->cover_image);
            //recupero il percorso

            $path = Storage::put('post_image', $request->cover_image);

            $val_data['cover_image'] = $path;
        }


        //update data
        $post->update($val_data);

        //sync tags
        $post->tags()->sync($request->tags);

        //redirect to get route
        return redirect()->route('admin.posts.index')->with('message', "$post->title Your post is now updated!");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('message', "$post->title GG" );
    }
}
