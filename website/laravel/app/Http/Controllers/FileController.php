<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\File;
use App\Folder;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        
        return view('files.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('files.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $request->validate([
        'title' => 'required:max:255',
        'overview' => 'required',
        'price' => 'required|numeric'
      ]);

      auth()->user()->files()->create([
        'title' => $request->get('title'),
        'overview' => $request->get('overview'),
        'price' => $request->get('price')
      ]);

      return back()->with('message', 'Your file is submitted Successfully');
    }      

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function upload(Request $request)
    {
      $uploadedFile = $request->file('file');
      $filename = time().$uploadedFile->getClientOriginalName();

      Storage::disk('local')->putFileAs(
        'files/'.$filename,
        $uploadedFile,
        $filename
      );

      $upload = new Upload;
      $upload->filename = $filename;

      $upload->user()->associate(auth()->user());

      $upload->save();

      return response()->json([
        'id' => $upload->id
      ]);
    }
}
