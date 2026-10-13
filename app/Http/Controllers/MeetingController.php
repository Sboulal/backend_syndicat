<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function index()
    {
        // كنجيبو الاجتماعات ومعاهم القرارات اللي تتاخدات فيهم
        $meetings = Meeting::with('decisions')->get();
        return response()->json($meetings, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'scheduled_date' => 'required|date',
            'agenda' => 'nullable|array', // جدول الأعمال (Ordre du jour)
        ]);

        $meeting = Meeting::create($request->all());

        return response()->json(['message' => 'Réunion programmée', 'meeting' => $meeting], 201);
    }

    public function show($id)
    {
        $meeting = Meeting::with('decisions')->findOrFail($id);
        return response()->json($meeting, 200);
    }

    public function update(Request $request, $id)
    {
        $meeting = Meeting::findOrFail($id);
        $meeting->update($request->all());

        return response()->json(['message' => 'Réunion modifiée', 'meeting' => $meeting], 200);
    }

    public function destroy($id)
    {
        Meeting::destroy($id);
        return response()->json(['message' => 'Réunion annulée/supprimée'], 200);
    }
}