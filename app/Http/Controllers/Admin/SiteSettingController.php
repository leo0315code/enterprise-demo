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
        $groups = SiteSetting::query()
            ->orderBy('group')
            ->orderBy('sort')
            ->get()
            ->groupBy('group');

        $groupLabels = [
            'general' => '基础信息',
            'contact' => '联系方式',
            'social' => '社交媒体',
            'seo' => 'SEO 设置',
            'theme' => '主题配置',
        ];

        return view('admin.settings.index', compact('groups', 'groupLabels'));
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

        return redirect()
            ->route('admin.settings.index')
            ->with('success', '站点设置已保存');
    }
}
