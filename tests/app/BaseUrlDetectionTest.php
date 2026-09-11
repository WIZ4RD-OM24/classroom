<?php

namespace Tests\App;

use CodeIgniter\Test\CIUnitTestCase;
use Config\App;

/**
 * Base URL resolution.
 *
 * Every asset, link and redirect is built from app.baseURL, so a value that
 * does not match how the site is actually reached leaves the page loading its
 * stylesheet from a URL that does not exist — the site renders as unstyled
 * HTML. It used to be hard-coded to one developer's path
 * ('http://localhost/classroom/public'), which broke it for everyone else.
 *
 * @internal
 */
final class BaseUrlDetectionTest extends CIUnitTestCase
{
    /** @var array<string, mixed> */
    private array $server = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->server = $_SERVER;
    }

    protected function tearDown(): void
    {
        $_SERVER = $this->server;
        parent::tearDown();
    }

    /**
     * Build a config as though a web request had arrived, bypassing the CLI
     * short-circuit that the test runner would otherwise hit.
     */
    private function baseUrlFor(array $server): string
    {
        $_SERVER = array_merge($_SERVER, $server);

        $config = new App();

        // The constructor short-circuits to a fixed value under CLI, which is
        // the SAPI PHPUnit runs in, so call the request-based detection itself.
        $method = (new \ReflectionClass($config))->getMethod('detectBaseURL');
        $method->setAccessible(true);

        return $method->invoke($config);
    }

    public function testDefaultIsAutoRatherThanSomeonesLocalPath(): void
    {
        $this->assertSame(
            App::BASE_URL_AUTO,
            (new \ReflectionClass(App::class))->getDefaultProperties()['baseURL'],
            'baseURL should default to auto-detection, not a hard-coded URL'
        );
    }

    public function testDetectsHostAndPort(): void
    {
        $this->assertSame(
            'http://localhost:8080/',
            $this->baseUrlFor(['HTTP_HOST' => 'localhost:8080', 'SCRIPT_NAME' => '/index.php'])
        );
    }

    /**
     * The layout the project started from: served by Apache out of a folder
     * rather than from the document root.
     */
    public function testDetectsASubFolderDeployment(): void
    {
        $this->assertSame(
            'http://localhost/classroom/public/',
            $this->baseUrlFor([
                'HTTP_HOST'   => 'localhost',
                'SCRIPT_NAME' => '/classroom/public/index.php',
            ])
        );
    }

    public function testDetectsHttpsFromTheHttpsVariable(): void
    {
        $this->assertSame(
            'https://127.0.0.1/',
            $this->baseUrlFor(['HTTP_HOST' => '127.0.0.1', 'SCRIPT_NAME' => '/index.php', 'HTTPS' => 'on'])
        );
    }

    public function testTreatsHttpsOffAsPlainHttp(): void
    {
        $this->assertStringStartsWith(
            'http://',
            $this->baseUrlFor(['HTTP_HOST' => 'localhost', 'SCRIPT_NAME' => '/index.php', 'HTTPS' => 'off'])
        );
    }

    /**
     * @dataProvider localHosts
     */
    public function testGuessesForLocalHosts(string $host): void
    {
        $url = $this->baseUrlFor(['HTTP_HOST' => $host, 'SCRIPT_NAME' => '/index.php', 'HTTPS' => '']);

        $this->assertStringEndsWith('/', $url);
        $this->assertStringContainsString(rtrim(explode(':', $host)[0], ']'), $url . ']');
    }

    public static function localHosts(): array
    {
        return [
            ['localhost'],
            ['localhost:8080'],
            ['127.0.0.1'],
            ['127.0.0.1:8000'],
            ['192.168.1.24'],
            ['10.0.0.5'],
            ['172.16.4.9'],
            ['classroom.test'],
            ['classroom.localhost'],
            ['host.docker.internal'],
        ];
    }

    /**
     * The Host header is attacker-controlled, so a public hostname must be
     * configured rather than guessed — otherwise a forged header ends up in
     * generated links and cached pages.
     *
     * @dataProvider publicHosts
     */
    public function testRefusesToGuessForAPublicHost(string $host): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/app\.baseURL is not configured/');

        $this->baseUrlFor(['HTTP_HOST' => $host, 'SCRIPT_NAME' => '/index.php']);
    }

    public static function publicHosts(): array
    {
        return [
            ['evil.example.com'],
            ['classroom.example.edu'],
            ['8.8.8.8'],
            ['203.0.113.9'],
            [''],
        ];
    }

    /**
     * A header carrying a path, a newline or quote characters must never reach
     * the generated URLs. Sanitising leaves a host that is no longer local, so
     * the correct outcome is a refusal rather than a guess.
     *
     * @dataProvider hostileHosts
     */
    public function testRefusesAnInjectedHostHeader(string $host): void
    {
        try {
            $url = $this->baseUrlFor(['HTTP_HOST' => $host, 'SCRIPT_NAME' => '/index.php']);
        } catch (\RuntimeException $e) {
            // Refused outright — the safest outcome.
            $this->addToAssertionCount(1);

            return;
        }

        // If it did resolve, nothing of the injected payload may survive.
        $this->assertSame('http://localhost/', $url, "unsafe host {$host} leaked into the base URL");
    }

    public static function hostileHosts(): array
    {
        return [
            'path appended'   => ['localhost/evil.com'],
            'header injected' => ["localhost\r\nX-Bad: 1"],
            'attribute break' => ['localhost"onload='],
            'scheme smuggled' => ['localhost@evil.com'],
        ];
    }

    /**
     * An explicit setting must always win over detection — phpunit.xml.dist
     * supplies one via $_SERVER, exactly as .env does at runtime.
     */
    public function testExplicitConfigurationWinsOverDetection(): void
    {
        $_SERVER['app.baseURL'] = 'https://classroom.example.edu/';

        $this->assertSame('https://classroom.example.edu/', (new App())->baseURL);
    }

    public function testCliGetsAUsableDefaultWhenNothingIsConfigured(): void
    {
        // Drop the value phpunit.xml.dist injects, so the constructor reaches
        // its CLI branch: spark commands and migrations have no request.
        unset($_SERVER['app.baseURL']);

        $this->assertSame('http://localhost/', (new App())->baseURL);
    }

    public function testAlwaysEndsWithASlash(): void
    {
        $_SERVER['app.baseURL'] = 'https://classroom.example.edu';

        $this->assertStringEndsWith('/', (new App())->baseURL);
    }
}
