<?php

namespace Tests\App\Support;

use CodeIgniter\HTTP\Files\UploadedFile;

/**
 * An UploadedFile backed by an ordinary temp file.
 *
 * CodeIgniter 4.1's UploadedFile gates isValid() and move() behind
 * is_uploaded_file()/move_uploaded_file(), which can only ever be true during a
 * real multipart request, and this version of the framework ships no mock for
 * it. Only those two SAPI-level calls are replaced here; everything the tests
 * actually exercise — the client extension, the sniffed MIME type, the size,
 * the random name — is the framework's own behaviour.
 */
class FakeUploadedFile extends UploadedFile
{
    public function isValid(): bool
    {
        return $this->error === UPLOAD_ERR_OK && is_file($this->path);
    }

    public function move(string $targetPath, ?string $name = null, bool $overwrite = false)
    {
        $targetPath = rtrim($targetPath, '/\\') . DIRECTORY_SEPARATOR;

        if ($this->hasMoved) {
            throw new \RuntimeException('The file has already been moved.');
        }

        $name        = $name ?? $this->getName();
        $destination = $targetPath . $name;

        if (! rename($this->path, $destination)) {
            throw new \RuntimeException("Could not move the file to {$destination}.");
        }

        $this->hasMoved = true;
        $this->path     = $targetPath;
        $this->name     = basename($destination);

        return true;
    }
}
