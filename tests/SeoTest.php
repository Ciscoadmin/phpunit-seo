<?php
/**
 * Test changing some seo meta tags on the website
 *
 *
 */
require './vendor/autoload.php';
require_once __DIR__ . '/../src/Godzill/CSVChecker.php';
require_once __DIR__ . '/../src/Godzill/Utils.php';

//use PHPUnit\Framework\TestCase;
use Godzill\CSVChecker;
use Godzill\Utils;

class SeoTest extends  PHPUnit_Framework_TestCase
{
    /**
     * @var string source for data provider
     */
  //  private   $csvFileName =  "/../resources/data.csv"; // format - URL, Meta Title, Meta Description

    /**
     * @var string target file in which meta tag's data
     */
//    private   $metaFileName =  "meta.html";

    /**
     * @var string path to website folder
     */
//    private   $pathToMetaFile = "C:\Users\dbolgarov\Documents\Kitematic\hello-world-nginx\website_files"; // path to target file

    /**
     * @var array with original content of meta tag's data from  $metaFileName
     */
    private   $backupMetaData;

    /**
     * @var string Necessary for restore meta tag's data
     */
    private   $metaTitle; //we should know which values we are changed

    /**
     * @var string Necessary for restore meta tag's data
     */
    private   $metaDescription; //we should know which values we are changed


    /**
     * @var string  Name of current method. Using in log()
     */
    private $method = __METHOD__;



    /**
     * backup meta tag's data from web server file system
     */
    protected function setUp()
    {
        $this->method = __METHOD__;
        chdir(pathToMetaFile);
        $this->log(" Starting backup meta tag's data from resource - ".metaFileName);
        $this->backupMetaData=$this->getMetaTagFromResource(metaFileName);
        print_r($this->backupMetaData);
        //PS. Of course we can simply copy file and then restore, but I chose not the easiest way
        $this->log(" Finished backup meta tag's data ");
    }


    /**
     *
     * @return array
     * @throws
     */
    public function csvDataProvider()
    {
//        $csvFullContent = file_get_contents(__DIR__ .$this->csvFileName);
        $csvFullContent = file_get_contents(__DIR__ .csvFileName);
        $csvContent= CSVChecker::retrievingCSVData($csvFullContent);
        //Generate data for the test
        foreach ( $csvContent as $line )
        {
            $data[] = explode(',', trim($line));
        }
        return $data;
    }

    /**
     * The main check flow
     *
     * @dataProvider csvDataProvider
     */
    public function testSeoTagsData($url,$metaTitle,$metaDescription)
    {
        $this->method = __METHOD__;  //Necessary for using own logging function
        $this->metaTitle=$metaTitle; //Necessary for restore meta tag's data - we should know which values we are changed
        $this->metaDescription=$metaDescription; //Necessary for restore meta tag's data - we should know which values we are changed

//        $this->log( "=====================================");
//        $this->log( CSVChecker::$metaTagsFromCSVHeader[0]);
//        $this->log( CSVChecker::$metaTagsFromCSVHeader[1]);

        $this->changeSeoData(metaFileName,$metaTitle,$metaDescription ); //preparation step. Changing meta tags data in target resource

        $allTags = get_meta_tags($url); //Grab meta tags from the site

        $this->checkingSeoData($url,$allTags,$metaTitle,CSVChecker::$metaTagsFromCSVHeader[0]);
        $this->checkingSeoData($url,$allTags,$metaDescription,CSVChecker::$metaTagsFromCSVHeader[1]);
    }

    /**
     * restore meta tag's data to web server file system
     */
    protected function tearDown()
    {
        $this->method = __METHOD__;
        $this->log(" Starting restoring meta tag's data to resource - ".metaFileName);
        Utils::replaceStringInFile(metaFileName, $this->metaTitle,$this->backupMetaData[CSVChecker::$metaTagsFromCSVHeader[0]]);
        Utils::replaceStringInFile(metaFileName, $this->metaDescription,$this->backupMetaData[CSVChecker::$metaTagsFromCSVHeader[1]]);
        print_r($this->getMetaTagFromResource(metaFileName));
        $this->log("Finished restoring  meta tag's data ");
    }


    public function changeSeoData($sourceOfMetaTags,$metaTitle,$metaDescription)
    {
        $metaTagsFromFile=$this->getMetaTagFromResource($sourceOfMetaTags);
        Utils::replaceStringInFile($sourceOfMetaTags, $metaTagsFromFile[CSVChecker::$metaTagsFromCSVHeader[0]], $metaTitle);
        Utils::replaceStringInFile($sourceOfMetaTags, $metaTagsFromFile[CSVChecker::$metaTagsFromCSVHeader[1]], $metaDescription);
    }



    public function getMetaTagFromResource($resourceOfMetaTags){
        $allTags = get_meta_tags($resourceOfMetaTags);
        $metaTagsDataFromResource= array (
            CSVChecker::$metaTagsFromCSVHeader[0]=>$allTags[CSVChecker::$metaTagsFromCSVHeader[0]],
            CSVChecker::$metaTagsFromCSVHeader[1]=>$allTags[CSVChecker::$metaTagsFromCSVHeader[1]]
         );
        return $metaTagsDataFromResource;
    }


    /**
     * Compare SEO Meta Tag's Data between website and CSV values
     *
     *
     */
    public function checkingSeoData($url,$allTags,$metaTagData, $tagName)
    {
        $this->log("==============================================================");
        $this->log("Checking META $tagName tag ");
        $this->log("==============================================================");
        $this->log("input values: ");
        $this->log("URL - {$url} ");
        $this->log("Meta $tagName Data - {$metaTagData} ");
        $this->log("==============================================================");
        $this->assertEquals($metaTagData, $allTags[$tagName],"There is no {$metaTagData} in Meta Title tag on {$url}");
        $this->log("Found the following tag's content on the website - {$allTags[$tagName]} ");
    }


    /**
     * Simple Logging function
     *
     * @var string $line string for logging
     * @var string Using private variable $method for logging name of the method
     */
    private function log($line)
    {
        echo Utils::getDate() . "  ". $this->method .  "  " . $line ."\n";
    }


}
