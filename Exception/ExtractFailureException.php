<?php

/**
 * This file is part of the ZipManager package
 *
 * Copyright (c) 2016 Mickaël Andrieu
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ZipManager\Exception;

use \ZipArchive;

class ExtractFailureException extends \Exception
{
    public static function create($zipFile, $destination, $error)
    {
        $message = sprintf(
            'The extraction of %s in %s has failed.',
            $filename,
            $destination
        );

        if (!empty($error['message'])) {
            $message .= sprintf('Error (%s): %s in file %s at line %s.',
                $error['type'],
                $error['message'],
                $error['file'],
                $error['line']
            );
        }

        return new static($message);
    }
}