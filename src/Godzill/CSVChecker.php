<?php
namespace Godzill;

    use Exception;
    use  Godzill\Utils;

    class CSVChecker
    {

        /**
         * @var array  Meta Tags name from  Header  of CSV file
         */
        public static $metaTagsFromCSVHeader;

        /**
         * @var array  All possible Meta Tags names
         */
        private static $allPossibleMetaTags = array("keywords", "title", "description", "author", "copyright", "robots", "url", "viewport", "revisit", "resource-type", "document-state");


        /**
         * @param $csvContentHeader array  only the first line from CSV
         * @throws Exception in case CSV file is incorrect
         */
        static public  function  checkingCSVHeader($csvContentHeader){

            if (  count($csvContentHeader) == 3 and  strripos($csvContentHeader[0], 'rl' ))  //Checking header's fields count and the  first field
            {
//               print_r ($csvContentHeader);

                for ($i=1;$i<count($csvContentHeader);$i++){   // checking all others fields
                    if (strripos($csvContentHeader[$i], ' ')){
                        $metaTempAr= explode(' ', (string)($csvContentHeader[$i]));
                        self::$metaTagsFromCSVHeader[$i-1]=trim((string)$metaTempAr[1]);
                    }
                    else
                        self::$metaTagsFromCSVHeader[$i-1]=trim((string)$csvContentHeader[$i]);
//                    Utils::log( CSVChecker::$metaTagsFromCSVHeader[$i-1]);
                }
//                print_r ( CSVChecker::$metaTagsFromCSVHeader);
                foreach (self::$metaTagsFromCSVHeader as $item) {
                    if (!in_array($item, self::$allPossibleMetaTags)) {
                        throw new Exception( "At least one  header's field (  {$item} ) in CSV file is not recognize as  META tag. Please be sure that you are using the following format - URL, Meta Title, Meta Description");
                    }
                }

                Utils::log ("CSV header structure is correct");
            }
            else {
                throw new Exception( "CSV header structure is corrupted. Please be sure that you are using the following format - URL, Meta Title, Meta Description");
            }
        }

        /**
         * @param $csvFullContent string  Full content of  CSV
         * @return array Return pure data  - full content except header
         */
        static public  function  retrievingCSVData($csvFullContent){
            $csvContent=explode("\n", $csvFullContent,-1);
            $csvContentHeader= array_splice($csvContent, 0,1); //divided it
//        $csvContentHeader= explode(',', (string)($csvContentHeader[0]));
//        $this->checkingCSVHeader($csvContentHeader);
            self::checkingCSVHeader(explode(',', (string)($csvContentHeader[0])));
            return $csvContent;
        }

    }

