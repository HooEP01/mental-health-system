<?php

namespace App\Http\Controllers\Professional;
use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\ContentQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
            ],
            'can' => [
                'create' => Auth::user()->can('professional content create'),
                'edit' => Auth::user()->can('professional content edit'),
                'delete' => Auth::user()->can('professional content delete'),
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

        // Create new questions
        foreach ($request->questions as $question) {
            $question['content_id'] = $data->id;
            $this->storeQuestion($question);
        }

        return $this->index();
    }

    public function edit($id)
    {
        $data = DB::table('contents')
        -> select('contents.*')
        -> where('contents.id', '=', $id)
        -> where('contents.user_id', '=', Auth::Id())
        -> first();
        
        $questions = DB::table('content_questions')
        -> select('content_questions.*')
        -> where('content_questions.content_id', '=', $id)
        -> get();

        // decode json
        foreach($questions as $question) {
            $question->data =  json_decode($question->data);
        }

        return Inertia::render('Professional/Content/Edit', [
            'content' => [
                'id' => $id,
                'title' => $data->title,
                'image' => $data->image,
                'category' => $data->category,
                'status' => $data->status,
                'description' =>$data->description,
                'questions' =>$questions,
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

        // Get ids as plain array of existing questions
        $existingIds = $data->questions()->pluck('id')->toArray();

        // Get ids as plain array of new questions
        $newIds = Arr::pluck($request['questions'], 'id');

        // Find questions to delete
        $toDelete = array_diff($existingIds, $newIds);

        // Find questions to add
        $toAdd = array_diff($newIds, $existingIds);

        // Delete questions by $toDelete array
        ContentQuestion::destroy($toDelete);

        // Create new questions
        foreach ($request['questions'] as $question) {
            if (in_array($question['id'], $toAdd)) {
                $question['content_id'] = $request->id;
                $this->storeQuestion($question);
            }
        }

        // Update existing questions
        $questionMap = collect($request['questions'])->keyBy('id');
        foreach ($data->questions as $question) {
            if (isset($questionMap[$question->id])) {
                $this->updateQuestion($question, $questionMap[$question->id]);
            }
        }

        return $this->index();
    }

    public function destroy($id)
    {
        $data = Content::find($id);
        $data->delete();

        return $this->index();
    }

    
    private function storeQuestion($data)
    {
        if (is_array($data['data'])) {
            $data['data'] = json_encode($data['data']);
        }

        return ContentQuestion::create([
            'content_id'=>$data['content_id'],
            'question'=>$data['question'],
            'category'=>$data['category'],
            'description'=>$data['description'],
            'data'=>$data['data'],
        ]);
    }

    private function updateQuestion(ContentQuestion $question, $data)
    {
        if (is_array($data['data'])) {
            $data['data'] = json_encode($data['data']);
        }

        $question->question = $data['question'];
        $question->category=$data['category'];
        $question->description=$data['description'];
        $question->data = $data['data'];
        return $question->save();
    
        
    }
}