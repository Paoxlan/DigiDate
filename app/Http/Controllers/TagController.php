<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use App\Models\Tag;
use App\Traits\AuditTrailable;
use Exception;

class TagController extends Controller
{
    use AuditTrailable;

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
            $tag = Tag::create([
                'name' => request('name'),
            ]);

            AuditTrail::create([
                'user_id' => auth()->id(),
                'class' => Tag::class,
                'method' => 'Create',
                'model' => $this->auditTrailJson($tag)
            ]);

        } catch (Exception) {
            return back()->withErrors([
                'error' => 'Deze tag bestaat al!',
            ]);
        }

        return redirect(route('manage.tags'))->with('success', 'Tag has been successfully created.');
    }

    public function destroy(Tag $tag)
    {
        AuditTrail::create([
            'user_id' => auth()->id(),
            'class' => Tag::class,
            'method' => 'Delete',
            'model' => $this->auditTrailJson($tag)
        ]);

        $tag->delete();

        return back()->with('success', 'Tag has been successfully deleted.');
    }
}
