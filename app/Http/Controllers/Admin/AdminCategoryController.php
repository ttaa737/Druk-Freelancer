<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Skill;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    // ─── Categories ──────────────────────────────────────────────────────────

    public function index()
    {
        $categories = Category::with('children')->whereNull('parent_id')->latest()->get();
        $skills      = Skill::latest()->paginate(30);

        return view('admin.categories.index', compact('categories', 'skills'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:500',
            'parent_id'   => 'nullable|exists:categories,id',
            'icon'        => 'nullable|string|max:100',
        ]);

        $category = Category::create(array_merge($validated, ['slug' => Str::slug($validated['name'])]));
        AuditLogService::log('category.created', $category);

        return back()->with('success', "Category '{$category->name}' created.");
    }

    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'icon'        => 'nullable|string|max:100',
            'is_active'   => 'sometimes|boolean',
        ]);

        $category->update(array_merge($validated, ['slug' => Str::slug($validated['name'])]));
        AuditLogService::log('category.updated', $category);

        return back()->with('success', "Category '{$category->name}' updated.");
    }

    public function destroyCategory(Category $category)
    {
        if ($category->jobs()->exists() || $category->children()->exists()) {
            return back()->with('error', 'Cannot delete a category that has jobs or sub-categories.');
        }

        $category->delete();
        AuditLogService::log('category.deleted', $category);

        return back()->with('success', 'Category deleted.');
    }

    // ─── Skills ──────────────────────────────────────────────────────────────

    public function storeSkill(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:skills,name',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $skill = Skill::create(array_merge($validated, ['slug' => Str::slug($validated['name'])]));
        AuditLogService::log('skill.created', $skill);

        return back()->with('success', "Skill '{$skill->name}' added.");
    }

    public function destroySkill(Skill $skill)
    {
        $skill->delete();
        AuditLogService::log('skill.deleted', $skill);

        return back()->with('success', 'Skill removed.');
    }
}

