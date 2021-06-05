<?php declare(strict_types=1);
namespace VtSoftware\Utils;

class FileHandle {
  private String $path = '';
  public int $dirPermissions = 0770;
  public bool $locked = false;

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
  }
}
