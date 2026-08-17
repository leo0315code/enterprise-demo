<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 站点设置列表（按分组展示）
     */
    public function index()
    {
        $groupLabels = [
            'general' => '基础信息',
            'contact' => '联系方式',
            'social' => '社交媒体',
            'seo' => 'SEO 设置',
            'theme' => '主题配置',
        ];

        $groups = SiteSetting::query()
            ->orderBy('group')
            ->orderBy('sort')
            ->get()
            ->groupBy('group')
            ->map(function ($items, $group) use ($groupLabels) {
                return [
                    'key' => $group,
                    'label' => $groupLabels[$group] ?? $group,
                    'items' => $items->map(function ($s) {
                        return [
                            'key' => $s->key,
                            'label' => $s->label,
                            'description' => $s->description,
                            'type' => $s->type,
                            'value' => $s->value,
                        ];
                    })->all(),
                ];
            })
            ->values()
            ->all();

        return inertia('Settings', [
            'groups' => $groups,
        ]);
    }

    /**
     * 更新设置
     */
    public function update(Request $request)
    {
        $settings = $request->input('settings', []);

        foreach ($settings as $key => $value) {
            SiteSetting::where('key', $key)->update(['value' => $value]);
        }

        SiteSetting::clearCache();

        if ($request->inertia() || $request->ajax()) {
            return response()->json(['ok' => true]);
        }

        return redirect()
            ->route('admin.settings.index')
            ->with('success', '站点设置已保存');
    }
}
