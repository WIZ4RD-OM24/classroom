<?php

namespace Tests\App;

use CodeIgniter\Test\CIUnitTestCase;

/**
 * Every accent must stay readable in both light and dark mode.
 *
 * The accents in public/assets/css/app.css are declared as a hue and a
 * saturation, with every brand colour derived from them by varying lightness.
 * That makes adding a theme a three-line change — and makes it very easy to
 * add an unreadable one, because the same hue behaves completely differently
 * on white and on near-black.
 *
 * This parses the stylesheet, rebuilds the derived colours exactly as the CSS
 * does, and checks each against WCAG AA (4.5:1 for normal text).
 *
 * @internal
 */
final class AccentContrastTest extends CIUnitTestCase
{
    /** WCAG 2.1 AA, normal-size text. */
    private const AA_NORMAL = 4.5;

    /* Defaults from the :root and :root[data-theme="dark"] blocks. Kept in
       step with the stylesheet by testDefaultsMatchTheStylesheet() below. */
    private const L_SOLID_DEFAULT = 52.0;
    private const L_DEEP_DEFAULT  = 44.0;
    private const L_DARK_DEFAULT  = 62.0;

    private const ON_BRAND_LIGHT = [255, 255, 255]; // #ffffff
    private const ON_BRAND_DARK  = [11, 14, 20];    // #0b0e14
    private const SURFACE_LIGHT  = [255, 255, 255]; // #ffffff
    private const SURFACE_DARK   = [20, 25, 34];    // #141922

    private static function stylesheet(): string
    {
        $path = ROOTPATH . 'public/assets/css/app.css';
        $css  = file_get_contents($path);

        if ($css === false) {
            throw new \RuntimeException("Could not read {$path}");
        }

        return $css;
    }

    /**
     * Pull every `:root[data-accent="…"]` block out of the stylesheet.
     *
     * @return array<string, array<string, float>>
     */
    private static function accents(): array
    {
        preg_match_all(
            '/\[data-accent="(?<name>[a-z]+)"\]\s*\{(?<body>[^}]*)\}/',
            self::stylesheet(),
            $blocks,
            PREG_SET_ORDER
        );

        $accents = [];

        foreach ($blocks as $block) {
            preg_match_all('/--(?<key>[a-z-]+):\s*(?<value>[0-9.]+)%?;/', $block['body'], $decls, PREG_SET_ORDER);

            $values = [];

            foreach ($decls as $decl) {
                $values[$decl['key']] = (float) $decl['value'];
            }

            $accents[$block['name']] = $values;
        }

        return $accents;
    }

    public static function accentProvider(): array
    {
        $cases = [];

        foreach (self::accents() as $name => $values) {
            $cases[$name] = [$name, $values];
        }

        return $cases;
    }

    /**
     * @dataProvider accentProvider
     */
    public function testAccentIsReadableInBothModes(string $name, array $v): void
    {
        $this->assertArrayHasKey('h', $v, "accent {$name} declares no --h");
        $this->assertArrayHasKey('s', $v, "accent {$name} declares no --s");

        $h = $v['h'];
        $s = $v['s'];

        // --brand / --on-brand, light mode: white on the solid colour.
        $brandLight = self::hsl($h, $s, $v['l-solid'] ?? self::L_SOLID_DEFAULT);
        $this->assertGreaterThanOrEqual(
            self::AA_NORMAL,
            $ratio = self::contrast(self::ON_BRAND_LIGHT, $brandLight),
            sprintf('%s: white on the light-mode button is %.2f:1 — lower --l-solid to darken it', $name, $ratio)
        );

        // --brand-text on --surface, light mode: links and active nav labels.
        $textLight = self::hsl($h, $s, $v['l-deep'] ?? self::L_DEEP_DEFAULT);
        $this->assertGreaterThanOrEqual(
            self::AA_NORMAL,
            $ratio = self::contrast($textLight, self::SURFACE_LIGHT),
            sprintf('%s: link text on a light card is %.2f:1 — lower --l-deep', $name, $ratio)
        );

        // Dark mode puts dark text on a lifted solid.
        $brandDark = self::hsl($h, $s, $v['l-dark'] ?? self::L_DARK_DEFAULT);
        $this->assertGreaterThanOrEqual(
            self::AA_NORMAL,
            $ratio = self::contrast(self::ON_BRAND_DARK, $brandDark),
            sprintf('%s: dark text on the dark-mode button is %.2f:1 — raise --l-dark to brighten it', $name, $ratio)
        );

        // --brand-text in dark mode is hsl(h, s - 10%, 74%).
        $textDark = self::hsl($h, max(0.0, $s - 10.0), 74.0);
        $this->assertGreaterThanOrEqual(
            self::AA_NORMAL,
            $ratio = self::contrast($textDark, self::SURFACE_DARK),
            sprintf('%s: link text on a dark card is %.2f:1', $name, $ratio)
        );
    }

    public function testEveryAccentOfferedInThePickerExists(): void
    {
        $declared = array_keys(self::accents());
        $markup   = file_get_contents(APPPATH . 'Views/partials/theme_menu.php');

        preg_match_all("/'([a-z]+)'\s*=>\s*'[A-Z]/", $markup, $offered);

        foreach ($offered[1] as $accent) {
            $this->assertContains(
                $accent,
                $declared,
                "The picker offers '{$accent}' but the stylesheet declares no such accent"
            );
        }

        $this->assertNotEmpty($offered[1], 'No accents were found in the theme picker');
    }

    /**
     * The JavaScript keeps its own allow-list, so an accent added to the
     * picker but not to that list is silently ignored at runtime.
     */
    public function testJavaScriptAllowListMatchesThePicker(): void
    {
        $js = file_get_contents(FCPATH . 'assets/js/app.js');

        preg_match("/var ACCENTS = \[(?<list>[^\]]+)\]/", $js, $m);
        $this->assertArrayHasKey('list', $m, 'Could not find the ACCENTS allow-list in app.js');

        preg_match_all("/'([a-z]+)'/", $m['list'], $allowed);

        $markup = file_get_contents(APPPATH . 'Views/partials/theme_menu.php');
        preg_match_all("/'([a-z]+)'\s*=>\s*'[A-Z]/", $markup, $offered);

        sort($allowed[1]);
        sort($offered[1]);

        $this->assertSame($offered[1], $allowed[1], 'The picker and app.js disagree about the available accents');
    }

    /**
     * Guards the constants above against drifting out of step with the CSS.
     */
    public function testDefaultsMatchTheStylesheet(): void
    {
        $css = self::stylesheet();

        $this->assertStringContainsString(
            'var(--l-solid, ' . self::L_SOLID_DEFAULT . '%)',
            $css,
            'The default --l-solid in the stylesheet no longer matches this test'
        );
        $this->assertStringContainsString(
            'var(--l-deep, ' . self::L_DEEP_DEFAULT . '%)',
            $css,
            'The default --l-deep in the stylesheet no longer matches this test'
        );
        $this->assertStringContainsString(
            'var(--l-dark, ' . self::L_DARK_DEFAULT . '%)',
            $css,
            'The default --l-dark in the stylesheet no longer matches this test'
        );
    }

    // ---- colour maths ----------------------------------------------------

    /**
     * HSL (degrees, percent, percent) to 8-bit sRGB.
     *
     * @return array{0: float, 1: float, 2: float}
     */
    private static function hsl(float $h, float $s, float $l): array
    {
        $s /= 100;
        $l /= 100;

        $c = (1 - abs(2 * $l - 1)) * $s;
        $x = $c * (1 - abs(fmod($h / 60, 2) - 1));
        $m = $l - $c / 2;

        [$r, $g, $b] = match (true) {
            $h < 60  => [$c, $x, 0.0],
            $h < 120 => [$x, $c, 0.0],
            $h < 180 => [0.0, $c, $x],
            $h < 240 => [0.0, $x, $c],
            $h < 300 => [$x, 0.0, $c],
            default  => [$c, 0.0, $x],
        };

        return [($r + $m) * 255, ($g + $m) * 255, ($b + $m) * 255];
    }

    /**
     * WCAG relative luminance.
     */
    private static function luminance(array $rgb): float
    {
        $channels = array_map(static function (float $value): float {
            $value /= 255;

            return $value <= 0.03928
                ? $value / 12.92
                : (($value + 0.055) / 1.055) ** 2.4;
        }, $rgb);

        return 0.2126 * $channels[0] + 0.7152 * $channels[1] + 0.0722 * $channels[2];
    }

    private static function contrast(array $a, array $b): float
    {
        $la = self::luminance($a);
        $lb = self::luminance($b);

        return (max($la, $lb) + 0.05) / (min($la, $lb) + 0.05);
    }
}
