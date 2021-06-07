<?php declare(strict_types=1);
namespace {
  use \VtSoftware\Utils\FileHandle;

  class File {
    public static function read(String $path): String {
      return (new FileHandle($path))->read();
    }

    public static function write(String $path, String $data): void {
      (new FileHandle($path))->write($data);
    }
  }
}
