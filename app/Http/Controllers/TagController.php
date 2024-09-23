<?php

namespace App\Http\Controllers;

use App\Models\Tags;

class TagController extends Controller
{
    public function index()
    {
        return view('manage.tags-overview', [
            'tags' => Tags::all(),
        ]);
    }

    public function create()
    {
        return view('manage.tags-new');
    }

    public function store()
    {
        request()->validate([
            'name' => 'required',
        ]);
        try {
            Tags::create([
                'name' => request('name'),
            ]);
        } catch (\Exception $exception) {
            return back()->withErrors([
                'error' => 'Deze tag bestaald al!',
            ]);
        }

        return redirect(route('manage.tags'))->with('success', 'Tag has been successfully created.');
    }

    public function destroy(Tags $tag)
    {
        $tag->delete();

        return back()->with('success', 'Tag has been successfully deleted.');
    }
}
