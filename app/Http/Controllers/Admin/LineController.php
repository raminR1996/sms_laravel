<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Line;
use Illuminate\Http\Request;

class LineController extends Controller
{
    public function index()
    {
        $lines = Line::all();
        return view('admin.lines.index', compact('lines'));
    }

    public function create()
    {
        return view('admin.lines.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'line_number' => 'required|string|unique:lines',
            'operator_name' => 'required|string',
            'line_type' => 'required|in:advertising,service',
            'is_active' => 'boolean',
        ]);

        Line::create($validated);
        return redirect()->route('admin.lines.index')->with('success', 'خط با موفقیت اضافه شد.');
    }

    public function edit(Line $line)
    {
        return view('admin.lines.edit', compact('line'));
    }

    public function update(Request $request, Line $line)
    {
        $validated = $request->validate([
            'line_number' => 'required|string|unique:lines,line_number,' . $line->id,
            'operator_name' => 'required|string',
            'line_type' => 'required|in:advertising,service',
            'is_active' => 'boolean',
        ]);

        $line->update($validated);
        return redirect()->route('admin.lines.index')->with('success', 'خط با موفقیت ویرایش شد.');
    }

    public function destroy(Line $line)
    {
        $line->delete();
        return redirect()->route('admin.lines.index')->with('success', 'خط با موفقیت حذف شد.');
    }

    public function toggleStatus(Line $line)
    {
        $line->update(['is_active' => !$line->is_active]);
        return redirect()->route('admin.lines.index')->with('success', 'وضعیت خط با موفقیت تغییر کرد.');
    }
}