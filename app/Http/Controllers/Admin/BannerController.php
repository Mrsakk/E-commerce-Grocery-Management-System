<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->paginate(10);

        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function show($id)
    {
        $banner = Banner::findOrFail($id);

        return view('admin.banners.show', compact('banner'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_km' => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'description_km' => 'nullable|string',
            'badge_en' => 'nullable|string|max:100',
            'badge_km' => 'nullable|string|max:100',
            'link' => 'nullable|string|max:255',
            'button_text_en' => 'nullable|string|max:100',
            'button_text_km' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'gradient_css' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'required|integer',
        ]);

        if ($request->hasFile('image')) {
            try {
                $validated['image_path'] = $request->file('image')->store('uploads/banners', 'public');
            } catch (\Exception $e) {
                // File storage may fail on read-only environments (e.g. Vercel)
            }
        }

        unset($validated['image']);

        try {
            Banner::create($validated);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create banner: '.$e->getMessage());
        }

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner created successfully.');
    }

    public function edit($id)
    {
        $banner = Banner::findOrFail($id);

        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_km' => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'description_km' => 'nullable|string',
            'badge_en' => 'nullable|string|max:100',
            'badge_km' => 'nullable|string|max:100',
            'link' => 'nullable|string|max:255',
            'button_text_en' => 'nullable|string|max:100',
            'button_text_km' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'gradient_css' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'required|integer',
        ]);

        $banner = Banner::findOrFail($id);

        if ($request->hasFile('image')) {
            try {
                if ($banner->image_path && File::exists(storage_path('app/public/'.$banner->image_path))) {
                    File::delete(storage_path('app/public/'.$banner->image_path));
                }
                $validated['image_path'] = $request->file('image')->store('uploads/banners', 'public');
            } catch (\Exception $e) {
                // File storage may fail on read-only environments (e.g. Vercel)
            }
        }

        unset($validated['image']);

        try {
            $banner->update($validated);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update banner: '.$e->getMessage());
        }

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner updated successfully.');
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);

        if ($banner->image_path) {
            try {
                if (File::exists(storage_path('app/public/'.$banner->image_path))) {
                    File::delete(storage_path('app/public/'.$banner->image_path));
                }
            } catch (\Exception $e) {
                // File deletion may fail on read-only environments
            }
        }

        try {
            $banner->delete();
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete banner: '.$e->getMessage());
        }

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner deleted successfully.');
    }
}
