<?php
namespace nd\utils;

use nd;
use php\io\IOException;
use gui;
use framework;
use std;

use php\lib\fs;
use php\io\File;
use nd\modules\IDE;

class FileUtils 
{
    /**
     * @param $pathDir
     * @return bool
     */
    public static function delete($pathDir)
    {
        $dir = new File($pathDir);
        if ($dir->findFiles()) {
            foreach ($dir as $path)
                $list[] = $path->getPath();
            
            if (isset($list)) {
                foreach ($list as $file)
                    (fs::isDir($file)) ? self::delete($file): fs::delete($file);
            }
        }
        return fs::delete($pathDir);
    }

    /**
     * @param string $fromDir
     * @param string $toDir
     * @return bool
     */
    public static function copy(string $fromDir, string $toDir)
    {
        $fromDir = fs::abs($fromDir);
        $toDir   = fs::abs($toDir);

        if (!fs::exists($fromDir))
        {
            echo 'Dir from copy not found' . "\n";
            return false;
        }

        if (!fs::exists($toDir))
        {
            echo 'Dir to copy not found' . "\n";
            return false;
        }

        $dir = new File($fromDir);
        if ($dir->findFiles()) {
            foreach ($dir->findFiles() as $file)
            {
                $path = fs::abs($toDir . '/' . fs::name($file));
                echo 'Copy from -> ' . $file . ', to -> ' . $path . "\n";
                if (fs::isDir($file)) {
                    fs::makeDir($path);
                    self::copy($file, $path);
                } else fs::copy($file, $path);
            }
            return true;
        }
    }

    /**
     * @param string $fromDir
     * @param string $toDir
     */
    public static function move(string $fromDir, string $toDir)
    {
        return fs::move($fromDir, $toDir);
    }

    /**
     * @param $path
     * @param $name
     * @param null $content
     * @return string|void
     */
    public static function createFile($path, $name, $content = null)
    { 
        if ($name == null || !fs::valid($name) || !fs::nameNoExt($name)) 
        {
            IDE::dialog("Не верное имя файла.");
            return;
        }
        
        if (fs::isDir($path))
            $path .= "/" . $name;
        else 
            $path = fs::parent($path) . "/" . $name;
        try {
            Stream::putContents($path, $content);
        }
        catch (IOException $e) {
            IDE::dialog("Не удалось создать файл.");
        }
            
        return $path;
    }
}
