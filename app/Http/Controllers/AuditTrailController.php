<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;

class AuditTrailController extends Controller
{
    public function index()
    {
        return view('admin.audit-trails', [
            'auditTrails' => AuditTrail::orderByDesc('id')
                ->paginate(10)
        ]);
    }

    public function show(AuditTrail $auditTrail)
    {
        return view('admin.audit-trail', [
            'auditTrail' => $auditTrail
        ]);
    }

    public function destroy(AuditTrail $auditTrail)
    {
        $auditTrail->delete();

        return redirect()->route('audit-trails');
    }
}
