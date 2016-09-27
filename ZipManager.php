<?php

/**
 * This file is part of the ZipManager package
 *
 * Copyright (c) 2016 Mickaël Andrieu
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ZipManager;

use \ZipArchive;
use ZipManager\Exception\ExtractFailureException;
use ZipManager\Exception\OpenFailureException;

class ZipManager
{
    public function openArchive($filename, $options = ZipArchive::CHECKCONS)
    {
        $zip = new ZipArchive();

        $success = $zip->open($filename, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        if (true !== $success) {
            $errorCode = $success;
            throw new OpenFailureException($errorCode);
        }

        return $zip;
    }

    public function createArchive($filename, $folder)
    {
        $zip = $this->openArchive($filename, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($folder),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filename, strlen($folder) + 1);

                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();
    }
    
    public function extractArchive($zipFile, $destination, $files = [])
    {
        $zip = $this->openArchive($zipFile);

        $error = error_get_last();
        $success = $zip->extractTo($destination, $files);
        
        if (!$success) {
            throw new ExtractFailureException($zipFile, $destination, $error); 
        }
    }
}