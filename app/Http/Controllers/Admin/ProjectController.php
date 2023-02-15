<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Tecnology;
use App\Models\Type;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
    }
    
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = Type::all();
        $tecnologies = Tecnology::all();
        return view('admin.projects.create', compact('types', "tecnologies"));
    }

    public function search(Request $request)
    {
        $query = $request->get('name');
        $projects = Project::where('name', 'like', '%'.$query.'%')->get();
        return view('admin.projects.show', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated();
        $project = Project::create($data);
    
        if (key_exists('cover_img', $data)){
            $path = Storage::put('projects', $data['cover_img']);
            $project->cover_img = $path;
        } 
        if ($request->has("tecnologies")) {
            $project->tecnologies()->attach($data["tecnologies"]);
        }
        $project->save();
        return redirect()->route("admin.projects.show", $project->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {

        return view("admin.projects.show", compact("project"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $types = Type::all();
        $tecnologies = Tecnology::all();
        return view('admin.projects.edit', compact('project', 'types', 'tecnologies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $data=$request->validated();
        $project->update($data);
        
        if(key_exists("cover_img", $data)){
            $path = Storage::put("projects", $data["cover_img"]);
            Storage::delete($project->cover_img);
            $project->cover_img = $path;
        }
        $project->tecnologies()->sync($data["tecnologies"]);
        $project->save();

        return redirect()->route("admin.projects.show", $project->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        if($project->cover_img){
            Storage::delete($project->cover_img);
        }
        $project->tecnologies()->detach();
        $project->delete();
        return redirect()->route("admin.dashboard");
    }
}