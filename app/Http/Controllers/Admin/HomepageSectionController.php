<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use Illuminate\Http\Request;

class HomepageSectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $sections = HomepageSection::orderBy('sort')->orderBy('id')->get();
        return view('admin.sections.index', compact('sections'));
    }

    public function create()
    {
        return view('admin.sections.form', ['section' => null]);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request);

        if (isset($data['extra']) && is_string($data['extra'])) {
            $decoded = json_decode($data['extra'], true);
            $data['extra'] = is_array($decoded) ? $decoded : null;
        }

        HomepageSection::create($data);

        return redirect()->route('admin.sections.index')
            ->with('success', '板块已创建');
    }

    public function edit(HomepageSection $section)
    {
        return view('admin.sections.form', compact('section'));
    }

    public function update(Request $request, HomepageSection $section)
    {
        $data = $this->validate($request);

        if (isset($data['extra']) && is_string($data['extra'])) {
            $decoded = json_decode($data['extra'], true);
            $data['extra'] = is_array($decoded) ? $decoded : null;
        }

        $section->update($data);

        return redirect()->route('admin.sections.index')
            ->with('success', '板块已更新');
    }

    public function destroy(HomepageSection $section)
    {
        $section->delete();
        return redirect()->route('admin.sections.index')
            ->with('success', '板块已删除');
    }

    protected function validate(Request $request): array
    {
        return $request->validate([
            'type' => ['required', 'string', 'in:hero,intro,features,products,news,cta,custom'],
            'title' => ['nullable', 'string', 'max:200'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:500'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_link' => ['nullable', 'string', 'max:500'],
            'extra' => ['nullable'],
            'sort' => ['integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);
    }
}
