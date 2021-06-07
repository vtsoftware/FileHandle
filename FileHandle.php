<?php declare(strict_types=1);
namespace VtSoftware\Utils;

class FileHandle {
  private $fileInstance;

  public String $path = '';

  public bool $autoread = false;
  public bool $autosave = false;

  public int $dirPermissions = 0770;
  public bool $locked = false;

  public int $unlockWaitIterations = 3;
  public float $unlockCheckTimeout = 0.5;

  function __construct(public String $dir, public ?String $file = null, public bool $creator = false, public bool $locking = false) {
    if ($this->file === null) {
      $this->dir = dirname($dir);
      $this->file = basename($dir);
    }
    
    $this->path = rtrim($this->dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$this->file;
    
    if ($this->creator === true) {
      $createFile = false;

      if (!is_dir($this->dir)) {
        mkdir($this->dir, $this->dirPermissions, true);
        $createFile = true;
      } else {
        $createFile = !file_exists($this->path);
      }

      if ($createFile) {
        touch($this->path);
      }
    }

    $this->fileInstance = fopen($this->path, 'rw+');
  }

  public function write(String $data): void {
    $locked = file_exists($this->path.'.lock');

    if ($locked) {
      for($i = $this->unlockWaitIterations; $i > 0; $i--) {
        if (!file_exists($this->path.'.lock')) {
          break;
        }

        usleep($this->unlockCheckTimeout * 1000000);
      }
    }

    fwrite($this->fileInstance, $data);

    if ($locked) {
      unlink($this->path.'.lock');
    }
  }

  public function read(): String {
    return fread($this->fileInstance, filesize($this->path));
  }

  public function __destruct() {
    fclose($this->fileInstance);
  }
}
