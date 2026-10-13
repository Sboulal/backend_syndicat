<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::all();
        return response()->json($documents, 200);
    }

    // دالة لرفع الملفات (Upload)
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB Max
            'title' => 'required|string',
            'documentable_type' => 'required|string', // باش نلصقوه فـ (Claim, Expense, Meeting...)
            'documentable_id' => 'required|integer',
        ]);

        // تخزين الملف فـ الدوسي public/documents
        $path = $request->file('file')->store('documents', 'public');

        $document = Document::create([
            'title' => $request->title,
            'category' => $request->category,
            'file_path' => $path,
            'documentable_type' => $request->documentable_type,
            'documentable_id' => $request->documentable_id,
        ]);

        return response()->json(['message' => 'Document uploadé avec succès', 'document' => $document], 201);
    }

    public function show($id)
    {
        $document = Document::findOrFail($id);
        return response()->json($document, 200);
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        
        // كمسحو الفايل من السيرفر
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }
        
        $document->delete();

        return response()->json(['message' => 'Document supprimé'], 200);
    }
} 