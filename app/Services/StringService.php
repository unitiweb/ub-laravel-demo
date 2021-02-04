<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Service class to aid with string tasks
 *
 * @package App\Services
 */
class StringService
{
    /**
     * Strip out all characters except numbers
     *
     * @param string $string The string to process
     *
     * @return string
     */
    public function digitsOnly(string $string): string
    {
        return preg_replace('/[^a-zA-Z]/','', $string);
    }

    /**
     * Create a match slug used for things like matching transactions with entries
     *
     * @param mixed ...$args An array of arguments
     *
     * @return string
     */
    public function makeSlug(...$args): string
    {
        $parts = [];

        foreach ($args as $arg) {
            $parts[] = Str::slug($this->digitsOnly($arg));
    }

        return implode('->', $parts);
    }

    /**
     * Take in a base 64 encoded image and parse out the various parts
     *
     * @param string $base64Image The base 65 encoded string
     *
     * @return Collection
     */
    public function parseBase64Image(string $base64Image): ?Collection
    {
        $data = [
            'image' => '',
            'encoding' => '',
            'mimeType' => '',
            'extension' => '',
            'imageName' => '',
            'path' => '',
        ];

        // Example base64Image
        // data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAZAAAAGQCAYAAACAvzbMAAAgAE...
        $parts = explode(',', $base64Image);
        if (count($parts) === 2) {
            $data['image'] = $parts[1];
            $typeParts = explode(';', $parts[0]);
            if (count($typeParts) === 2) {
                $data['encoding'] = $typeParts[1];
                $imageParts =  explode(':', $typeParts[0]);
                if (count($imageParts) === 2) {
                    $data['mimeType'] = $imageParts[1];
                    $mimeParts = explode('/', $data['mimeType']);
                    if (count($mimeParts) === 2) {
                        $data['extension'] = $mimeParts[1];
                    } else {
                        return null;
                    }
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } else {
            return null;
        }

        $imageName = Str::uuid();
        $data['filename'] = "{$imageName}.{$data['extension']}";

        return collect($data);
    }
}
