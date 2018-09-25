<?php

namespace App\Helpers;

class Dxdiag
{
    /**
     * Parse the dxdiag from file into an array.
     *
     * @param  string $hash
     * @return array
     */
    public function parse($hash)
    {
        $path = sprintf('%s/%s.txt', $_SERVER['DOCUMENT_ROOT'], $hash);

        if (is_file($path) && !empty(file($path))) {
            $dxdiag = fread(fopen($path, 'r'), filesize($path));

            preg_match_all('/^([A-Za-z\s]+)?:\s(.+)$/m', $dxdiag, $matches);

            $output = array();

            for ($i=0; $i < count($matches[0]); $i++) {
                $output[trim($matches[1][$i])] = trim($matches[2][$i]);
            }

            return $output;
        }

        return false;
    }
}
