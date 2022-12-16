<?php

namespace App\Http\Controllers\Professional;
use App\Http\Controllers\Controller;

// models
use App\Models\Content;
use App\Models\ContentQuestion;

// illuminate
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// inertia
use Inertia\Inertia;


class ContentController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('can:professional content list', ['only' => ['index', 'show']]);
        $this->middleware('can:professional content create', ['only' => ['create', 'store']]);
        $this->middleware('can:professional content edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:professional content delete', ['only' => ['destroy']]);
    }   
     
    /**
     * Display a listing of contents 
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $contents = DB::table('contents')
        ->select('contents.*')
        ->where('contents.user_id', '=', Auth::Id())
        ->orderBy('created_at', 'desc')
        ->paginate(9);

        foreach($contents as $content){
            $question = DB::table('content_questions')
            ->select('content_questions.*')
            ->where('content_questions.content_id', '=', $content->id)
            ->count();

            $content->questionCount = $question;
        }
        
 
        return Inertia::render('Professional/Content/Index', [
            'contents' => $contents,
            'can' => [
                'create' => Auth::user()->can('professional content create'),
                'edit' => Auth::user()->can('professional content edit'),
                'delete' => Auth::user()->can('professional content delete'),
            ]
        ]);
    }

    /**
     * Display a content with a list of questions
     *
     * @param \App\Models\Content $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $content = DB::table('contents')
        ->select('contents.*')
        ->where('contents.id', '=', $id)
        ->where('contents.user_id', '=', Auth::Id())
        ->first();

        $content->formats = json_decode($content->formats);

        $questions = DB::table('content_questions')
        ->select('content_questions.*')
        ->where('content_questions.content_id', '=', $id)
        ->orderBy('content_questions.index', 'asc')
        ->get();

        foreach($questions as $question) {
            $question->data =  json_decode($question->data);
        }

        return Inertia::render('Professional/Content/Show', [
            'content' => $content,
            'questions' => $questions,
            'can' => [
                'create' => Auth::user()->can('professional content create'),
                'edit' => Auth::user()->can('professional content edit'),
                'delete' => Auth::user()->can('professional content delete'),
            ]
        ]);
    }

    /**
     * Display create page
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Professional/Content/Create', [
            'categories' => Content::CATEGORIES,
            'statuses' => Content::STATUSES,
            'formats' => Content::FORMATS,
        ]);
    }

    /**
     * Display a content with a list of questions
     *
     * @param \App\Models\Content $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $content = DB::table('contents')
        ->select('contents.*')
        ->where('contents.id', '=', $id)
        ->where('contents.user_id', '=', Auth::Id())
        ->first();

        $content->formats = json_decode($content->formats);

        $questions = DB::table('content_questions')
        ->select('content_questions.*')
        ->where('content_questions.content_id', '=', $id)
        ->orderBy('content_questions.index', 'asc')
        ->get();

        foreach($questions as $question) {
            $question->data = json_decode($question->data);
        }


        return Inertia::render('Professional/Content/Edit', [

            'content' => $content,
            'questions' => $questions,
            'categories' => Content::CATEGORIES,
            'statuses' => Content::STATUSES,
            'formats' => Content::FORMATS,
            'can' => [
                'create' => Auth::user()->can('professional content create'),
                'edit' => Auth::user()->can('professional content edit'),
                'delete' => Auth::user()->can('professional content delete'),
            ]
        ]);
    }

    /**
     * Store a newly created or edited content in storage 
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $content_id = $request->content_id;
        if($content_id != null){
            $this->updateContent($request);
            return redirect()->route('contents.show', [$content_id]);
        }else{
            return $this->storeContent($request);
            // return $this->index();
        }
    }

    /**
     * Remove the specified content from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $content = Content::find($id);
        $content->delete();

        return redirect()->route('contents.index');
    }

    private function storeContent(Request $request)
    {
        $image_path = "";
        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('image', 'public');
        }

        $audio_path = "";
        if ($request->hasFile('audio')) {
            $audio_path = $request->file('audio')->store('audio', 'public');
        }

        $user_id = Auth::Id();

        $formats = "{}";
        if(!empty($request->formats)) {
            $formats = json_encode($request->formats);
        }

        $data = Content::create([
            'user_id'=>$user_id,
            'image'=>$image_path,
            'audio'=>$audio_path,
            'title'=>$request->title,
            'category'=>$request->category,
            'status'=>$request->status,
            'description'=>$request->description,
            'format_category'=>$request->format_category,
            'formats'=>$formats,
        ]);
        


        if($request->questions != null) {
            foreach ($request->questions as $question) {
                $question['content_id'] = $data->id;
                $this->storeQuestion($question);
            }
        }

        return redirect()->route('contents.show', [$data->id]);
    }

    private function updateContent(Request $request)
    {
        $content = Content::find($request->content_id);
        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('image', 'public');
            $content->image = $image_path;
        }

        if ($request->hasFile('audio')) {
            $audio_path = $request->file('audio')->store('audio', 'public');
            $content->audio = $audio_path;
        }

        if(!empty($request->formats)) {
            $formats = json_encode($request->formats);
            $content->formats = $formats;
        }

        $content->title = $request->title;
        $content->category = $request->category;
        $content->status = $request->status;
        $content->description = $request->description;
        $content->format_category = $request->format_category;

        if(!empty($request->questions)){            

            // get ids of exist questions
            $existIds = $content->questions()->pluck('id')->toArray();
            // get ids of new questions
            $newIds = Arr::pluck($request->questions, 'id');
            // question to destroy
            $toDestroy = array_diff($existIds, $newIds);
            // question to store
            $toStore = array_diff($newIds, $existIds);

            ContentQuestion::destroy($toDestroy);

            // store questions
            foreach ($request->questions as $question) {
                if (in_array($question['id'], $toStore)) {
                    $question['content_id'] = $request->content_id;
                    $this->storeQuestion($question);
                }
            }
            // update questions

            foreach ($request->questions as $question) {
                if(in_array($question['id'], $existIds)){
                    $this->updateQuestion($question);
                }
            }


        } else {
            $questions = DB::table('content_questions')
            ->select('content_questions.*')
            ->where('content_questions.content_id', '=', $content->id)
            ->get();
            foreach($questions as $question){
                ContentQuestion::destroy($question->id);
            }
        }
        // save content
        $content->save();

        return $this->index();
    }

    private function storeQuestion($question)
    {
        $data = "{}";
        if(!empty($question['data'])) {
            $data = json_encode($question['data']);
        }
            
        return ContentQuestion::create([
            'content_id'=>$question['content_id'],
            'index'=>$question['index'],
            'question'=>$question['question'],
            'category'=>$question['category'],
            'description'=>$question['description'],
            'data' => $data,           
        ]);        
    }

    private function updateQuestion($question)
    {
        $contentQuestion = ContentQuestion::find($question['id']);

        $data = $question['data'] ?? "";
        if($data != ""){
            if(is_array($question['data']) || !($question['data'] == "{}") ) {
                if(!empty($question['data'])){
                    $contentQuestion->data = json_encode($question['data']);
                }
            }else{
                $contentQuestion->data = "{}";
            }
        }
        
        $contentQuestion->index = $question['index'];
        $contentQuestion->question = $question['question'];
        $contentQuestion->category = $question['category'];
        $contentQuestion->description = $question['description'];
        
        return $contentQuestion->save();
    }
}