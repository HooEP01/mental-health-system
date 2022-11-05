<?php

namespace App\Http\Controllers\Professional;
use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use DB;

class ContentController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:professional content list', ['only' => ['index', 'show']]);
        $this->middleware('can:professional content create', ['only' => ['create', 'store']]);
        $this->middleware('can:professional content edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:professional content delete', ['only' => ['destroy']]);
    }   
     
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('contents')
        -> select('contents.*')
        -> where('contents.user_id','=',Auth::id())
        -> orderBy('created_at','desc')
        -> paginate(100);
 
        return Inertia::render('Professional/Content/Index', [
            'contents' => $data,
            'can' => [
                'create' => Auth::user()->can('professional content create'),
                'edit' => Auth::user()->can('professional content edit'),
                'delete' => Auth::user()->can('professional content delete'),
            ]
        ]);
    }

    public function show($id)
    {
        $data = DB::table('contents')
        -> select('contents.*')
        -> where('contents.id', '=', $id)
        -> where('contents.user_id', '=', Auth::Id())
        -> first();

        return Inertia::render('Professional/Content/Show', [
            'content' => [
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
        return Inertia::render('Professional/Content/Create');
    }

    public function store(Request $request)
    {

        $image_path = '';

        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('image', 'public');
        }

        $user_id = Auth::user()->id;
        $data = Content::create([
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
        $data = DB::table('contents')
        -> select('contents.*')
        -> where('contents.id', '=', $id)
        -> where('contents.user_id', '=', Auth::Id())
        -> first();
        
        return Inertia::render('Professional/Content/Edit', [
            'content' => [
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
        $data = Content::find($id);

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
        $data = Content::find($id);
        $data->delete();
    }
}