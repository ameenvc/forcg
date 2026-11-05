<?php
namespace Tests\Feature;

use Tests\TestCase;

class RiskRegisterGlobalThemeTest extends TestCase
{
    public function testSharedAssetsRenderOnceWithOneGlobalSelector()
    {
        view()->getFinder()->prependLocation(base_path('tests/Fixtures/views'));
        app('events')->forget('composing: *');
        $html = view('theme-compatibility')->render();
        $this->assertSame(1, substr_count($html, 'data-risk-theme-select'));
        $this->assertSame(1, substr_count($html, 'js/risk-theme.js'));
        $this->assertSame(1, substr_count($html, 'js/app-theme.js'));
        $this->assertSame(1, substr_count($html, 'css/app-typography.css'));
        $this->assertStringContainsString('fonts/inter/CgbodInter-Variable.woff2', $html);
        $this->assertStringNotContainsString('fonts.googleapis.com', $html);
        if (getenv('THEME_COMPATIBILITY_PREVIEW_PATH')) file_put_contents(getenv('THEME_COMPATIBILITY_PREVIEW_PATH'), $html);
    }

    public function testUserModuleLayoutChainsHaveGlobalThemeAssetsAndControl()
    {
        $directory = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(resource_path('views/user')));
        $count = 0;
        foreach ($directory as $file) {
            if (!$file->isFile() || substr($file->getFilename(), -10) !== '.blade.php') continue;
            $source = file_get_contents($file->getPathname());
            if (!preg_match('/@extends\([\'\"]([^\'\"]+)[\'\"]/', $source, $match)) continue;
            $seen = [];
            while (isset($match[1])) {
                $parent = $match[1];
                $this->assertArrayNotHasKey($parent, $seen); $seen[$parent] = true;
                $path = resource_path('views/'.str_replace('.', '/', $parent).'.blade.php');
                $this->assertFileExists($path); $source = file_get_contents($path);
                if (!preg_match('/@extends\([\'\"]([^\'\"]+)[\'\"]/', $source, $match)) break;
            }
            $this->assertStringContainsString("@include('shared.theme.assets')", $source, $file->getPathname());
            $this->assertStringContainsString("@include('shared.forms.theme-control')", $source, $file->getPathname()); $count++;
        }
        $this->assertGreaterThan(400, $count);
    }
}
