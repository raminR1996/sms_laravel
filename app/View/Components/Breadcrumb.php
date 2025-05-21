<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Breadcrumb extends Component
{
    public $breadcrumbs;

    public function __construct()
    {
        $this->breadcrumbs = $this->generateBreadcrumbs();
    }

    protected function generateBreadcrumbs()
    {
        $currentRoute = request()->route()->getName();

        // تعریف سلسله‌مراتب مسیرها
        $breadcrumbHierarchy = [
            'admin.dashboard' => [
                'title' => 'داشبورد',
                'parent' => null, // والد ندارد
            ],
            'admin.settings.index' => [
                'title' => 'تنظیمات سایت',
                'parent' => 'admin.dashboard',
            ],
            'admin.settings.create' => [
                'title' => 'ایجاد تنظیمات',
                'parent' => 'admin.settings.index',
            ],
            'admin.settings.edit' => [
                'title' => 'ویرایش تنظیمات',
                'parent' => 'admin.settings.index',
            ],
             'admin.users.index' => [
                'title' => 'مدیریت کاربران',
                'parent' => 'admin.dashboard',
            ],
              'admin.users.create' => [
                'title' => 'ایجاد کاربر جدید',
                'parent' => 'admin.users.index',
            ],
            'admin.users.edit' => [
                'title' => 'ویرایش کاربر',
                'parent' => 'admin.users.index',
            ],
               'admin.contacts.index' => [
                'title' => 'مدیریت مخاطبین',
                'parent' => 'admin.dashboard',
            ],
            
            'admin.contacts.edit' => [
                'title' => 'ویرایش مخاطب',
                'parent' => 'admin.contacts.index',
            ],
        ];

        $items = [];

        // اگر مسیر فعلی در سلسله‌مراتب تعریف نشده باشد، عنوان خام را نمایش می‌دهیم
        if (!array_key_exists($currentRoute, $breadcrumbHierarchy)) {
            $items[] = ['title' => ucfirst(str_replace('.', ' ', $currentRoute)), 'url' => null, 'active' => true];
            return $items;
        }

        // مسیر فعلی و والدهایش را جمع‌آوری می‌کنیم
        $current = $currentRoute;
        $path = [];

        // جمع‌آوری مسیرها از مسیر فعلی تا ریشه
        while ($current !== null) {
            if (array_key_exists($current, $breadcrumbHierarchy)) {
                $path[] = $current;
                $current = $breadcrumbHierarchy[$current]['parent'];
            } else {
                break;
            }
        }

        // معکوس کردن مسیرها (از ریشه به مسیر فعلی)
        $path = array_reverse($path);

        // تولید آیتم‌های نان بری
        foreach ($path as $index => $route) {
            $isActive = $route === $currentRoute;
            $title = $breadcrumbHierarchy[$route]['title'];
            $url = $isActive ? null : route($route);

            $items[] = [
                'title' => $title,
                'url' => $url,
                'active' => $isActive,
            ];
        }

        return $items;
    }

    public function render()
    {
        return view('components.breadcrumb');
    }
}