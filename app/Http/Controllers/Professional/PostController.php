<?php

namespace App\Http\Controllers\Professional;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use DB;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:professional post list', ['only' => ['index', 'show']]);
        $this->middleware('can:professional post create', ['only' => ['create', 'store']]);
        $this->middleware('can:professional post edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:professional post delete', ['only' => ['destroy']]);
    }   
     
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('posts')
        -> select('posts.*')
        -> where('posts.user_id','=',Auth::id())
        -> orderBy('created_at','desc')
        -> paginate(100);
 
        return Inertia::render('Professional/Post/Index', [
            'posts' => $data,
            'can' => [
                'create' => Auth::user()->can('user post create'),
                'edit' => Auth::user()->can('user post edit'),
                'delete' => Auth::user()->can('user post delete'),
            ]
        ]);
    }

    public function show($id)
    {
        $data = DB::table('posts')
        -> select('posts.*')
        -> where('posts.id', '=', $id)
        -> where('posts.user_id', '=', Auth::Id())
        -> first();

        return Inertia::render('Professional/Post/Show', [
            'post' => [
                'id' => $id,
                'title' => $data->title,
                'image' => $data->image,
                'category' => $data->category,
                'status' => $data->status,
                'description' =>$data->description,
            ]
        ]);

    }

    public function create()
    {
        return Inertia::render('Professional/Post/Create');
    }

    public function store(Request $request)
    {

        $image_path = '';

        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('image', 'public');
        }

        $user_id = Auth::user()->id;
        $data = Post::create([
            'user_id'=>$user_id,
            'image'=>$image_path,
            'title'=>$request->title,
            'category'=>$request->category,
            'status'=>$request->status,
            'description'=>$request->description,
        ]);

        return $this->index();
    }

    public function edit($id)
    {
        $data = DB::table('posts')
        -> select('posts.*')
        -> where('posts.id', '=', $id)
        -> where('posts.user_id', '=', Auth::Id())
        -> first();
        
        return Inertia::render('Professional/Post/Edit', [
            'post' => [
                'id' => $id,
                'title' => $data->title,
                'image' => $data->image,
                'category' => $data->category,
                'status' => $data->status,
                'description' =>$data->description,
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = Post::find($id);

        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('image', 'public');
            $data->image = $image_path;
        }

        $data->title=$request->title;
        $data->category=$request->category;
        $data->status=$request->status;
        $data->description=$request->description;
        $data->save();

        return $this->index();
    }

    public function destroy($id)
    {
        $data = Post::find($id);
        $data->delete();
    }
}