<?php

namespace Tests\App;

use App\Libraries\FileStore;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\Test\CIUnitTestCase;
use Tests\App\Support\FakeUploadedFile;

/**
 * Upload validation and tenant-isolated storage.
 *
 * The previous code moved uploads straight into the web root with no checks on
 * extension, type or size, so a .php file uploaded through any of the four
 * upload forms landed somewhere the web server would execute it.
 *
 * @internal
 */
final class FileStoreTest extends CIUnitTestCase
{
    private FileStore $store;
    private string $scratch;

    /** @var list<string> */
    private array $made = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->store   = new FileStore();
        $this->scratch = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'classroom-tests';

        if (! is_dir($this->scratch)) {
            mkdir($this->scratch, 0775, true);
        }
    }

    protected function tearDown(): void
    {
        foreach ($this->made as $path) {
            if (is_file($path)) {
                @unlink($path);
            }
        }
        $this->made = [];

        parent::tearDown();
    }

    /**
     * Build an UploadedFile around a real temp file, as if it had been posted.
     */
    private function upload(string $name, string $contents, string $clientType): UploadedFile
    {
        $path = $this->scratch . DIRECTORY_SEPARATOR . bin2hex(random_bytes(6)) . '-' . $name;
        file_put_contents($path, $contents);
        $this->made[] = $path;

        return new FakeUploadedFile($path, $name, $clientType, filesize($path), UPLOAD_ERR_OK);
    }

    private function pdf(): string
    {
        return "%PDF-1.4\n1 0 obj<</Type/Catalog>>endobj\ntrailer<</Root 1 0 R>>\n%%EOF\n";
    }

    public function testAcceptsAGenuinePdf(): void
    {
        $name = $this->store->store($this->upload('notes.pdf', $this->pdf(), 'application/pdf'), 'assignments', 7);

        $this->assertNotSame('', $name);
        $this->made[] = WRITEPATH . "uploads/7/assignments/{$name}";
        $this->assertNotNull($this->store->path('assignments', $name, 7));
    }

    /**
     * The headline case: a PHP script wearing a .pdf extension.
     */
    public function testRejectsAScriptDisguisedAsAPdf(): void
    {
        $this->expectException(\RuntimeException::class);

        $this->store->store(
            $this->upload('shell.pdf', "<?php echo shell_exec(\$_GET['c']); ?>", 'application/pdf'),
            'assignments',
            7
        );
    }

    public function testRejectsADisallowedExtension(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/Allowed file types/');

        $this->store->store(
            $this->upload('shell.php', '<?php echo 1;', 'application/x-php'),
            'assignments',
            7
        );
    }

    public function testRejectsAnUnknownCategory(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->store->store($this->upload('a.pdf', $this->pdf(), 'application/pdf'), 'not-a-category', 7);
    }

    public function testAvatarsRejectNonImages(): void
    {
        $this->expectException(\RuntimeException::class);

        $this->store->store($this->upload('cv.pdf', $this->pdf(), 'application/pdf'), 'avatars', 7);
    }

    public function testStoresOutsideTheWebRoot(): void
    {
        $name = $this->store->store($this->upload('n.pdf', $this->pdf(), 'application/pdf'), 'notices', 7);
        $path = $this->store->path('notices', $name, 7);
        $this->made[] = $path;

        $this->assertStringContainsString('writable', str_replace('\\', '/', $path));
        $this->assertStringNotContainsString('/public/', str_replace('\\', '/', $path));
    }

    public function testOneOrganisationCannotReachAnothersFile(): void
    {
        $name = $this->store->store($this->upload('n.pdf', $this->pdf(), 'application/pdf'), 'notices', 7);
        $this->made[] = WRITEPATH . "uploads/7/notices/{$name}";

        $this->assertNotNull($this->store->path('notices', $name, 7));
        $this->assertNull($this->store->path('notices', $name, 8), 'file was readable by another organisation');
    }

    /**
     * @dataProvider traversalNames
     */
    public function testRefusesPathTraversal(string $name): void
    {
        $this->assertNull($this->store->path('assignments', $name, 7));
    }

    public static function traversalNames(): array
    {
        return [
            ['../../../.env'],
            ['..\\..\\.env'],
            ['/etc/passwd'],
            ['subdir/file.pdf'],
            ['.env'],
        ];
    }

    public function testDeleteIsSafeWithMissingOrEmptyNames(): void
    {
        $this->store->delete(null, 'notices', 7);
        $this->store->delete('', 'notices', 7);
        $this->store->delete('does-not-exist.pdf', 'notices', 7);

        $this->addToAssertionCount(1);
    }

    public function testDeleteRemovesAStoredFile(): void
    {
        $name = $this->store->store($this->upload('n.pdf', $this->pdf(), 'application/pdf'), 'notices', 7);
        $this->assertNotNull($this->store->path('notices', $name, 7));

        $this->store->delete($name, 'notices', 7);
        $this->assertNull($this->store->path('notices', $name, 7));
    }
}
