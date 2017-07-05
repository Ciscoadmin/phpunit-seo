<?php

namespace Godzill;


use DateTime;

class Utils
{


    /**
     * @var string Format of date. Using in log()
     */
    static private $dateFormat = DateTime::RFC2822;


    /**
     * Current date
     *
     * @return string
     */
    static public function getDate()
    {
        return (new DateTime())->format(Utils::$dateFormat);
    }


    /**
     * Replace data in file
     *
     * @var $filename - target file name
     * @var  string $stringToReplace
     * @var  string $replaceWith
     *
     */
   static  public function replaceStringInFile($filename, $stringToReplace, $replaceWith){
        $content=file_get_contents($filename);
        $contentChunks=explode($stringToReplace, $content);
        $content=implode($replaceWith, $contentChunks);
        file_put_contents($filename, $content);
    }

    /**
     * Simple Logging function
     *
     * @var string $line string for logging
     * @var string Using private variable $method for logging name of the method
     */
    static public function log($line)
    {
        echo self::getDate() . "  " .  "  " . $line ."\n";
    }


}