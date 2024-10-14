<?php

namespace App\Http\Controllers;

use App\Models\Tag;

class TagController extends Controller
{
    public function index()
    {
        return view('manage.tags-overview', [
            'tags' => Tag::all(),
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
            Tag::create([
                'name' => request('name'),
            ]);
        } catch (\Exception $exception) {
            return back()->withErrors([
                'error' => 'Deze tag bestaald al!',
            ]);
        }

        return redirect(route('manage.tags'))->with('success', 'Tag has been successfully created.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return back()->with('success', 'Tag has been successfully deleted.');
    }
}
