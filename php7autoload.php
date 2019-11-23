<?php
class PHP7Converter {
    private $results = [];
    
    public function __construct($folder)
    {
        $this->scan($folder);
        var_dump($this->results);
        echo "complete";
    }
    
    private function scan($folder) {
        $files = scandir($folder);
        foreach($files as $file){
            if(in_array($file, [".", ".."])) {
                continue;
            } else if(is_dir($folder."/".$file)) {
                // recurse
                $this->scan($folder."/".$file);
            } else if(strpos($file, ".php")) {
                // open
                $filePath = $folder."/".$file;
                              
                $returnType = "";
                $parameterTypes = [];
                $handle = fopen($filePath, "r");
                while(!feof($handle)) {
                    $line = fgets($handle);
                    
                    $matches = [];
                    preg_match("/@return\s+([^\s]+)/", $line, $matches);
                    if($matches) {
                        $returnType = $this->getType($filePath, $matches[1]);
                    }
                    
                    $matches = [];
                    preg_match("/@param\s+([^\s]+)\s+([^\s]+)/", $line, $matches);
                    if($matches) {
                        $parameterTypes[$matches[2]] = $this->getType($filePath, $matches[1]);
                    }
                    
                    $matches = [];
                    preg_match("/function\s+([^\(]+)\(([^\)]+)\)/", $line, $matches);
                    if($matches) {
                        $matches1 = [];
                        $parameters = $matches[2];
                        preg_match_all("/([a-zA-Z0-9\\\]+\s+)?\\$([a-zA-Z0-9\_]+)/", $matches[2], $matches1);
                        if($matches1[0]) {
                            foreach($matches1[2] as $i=>$variable) {
                                $variable = '$'.$variable;
                                if(!isset($parameterTypes[$variable])) {
                                    die($filePath.": ".$matches[1]." misses ".$variable);
                                }
                                $parameters = str_replace($matches1[0][$i], $parameterTypes[$variable]." \$".$parameterTypes[$variable], $parameters);
                            }
                        }
                        
                        $this->results[$filePath]["methods"][$matches[0]]["parameters"] = $parameters;
                        $this->results[$filePath]["methods"][$matches[0]]["returns"] = $returnType;
                        
                        $returnType = "";
                        $parameterTypes = [];
                    }
                }
                fclose($handle);
            }
        }
    }
    
    private function getType($filePath, $string) {
        $type = "";
        if ($type=="void" || !$string) {
            $type = "";
        } else if(in_array($string, ["boolean", "integer", "double", "array", "string"])) {
            $type = $string;
        } else if (strpos($string, "[")!==false) {
            $type = "array";
        } else if (ctype_upper($string[0])) {
            $type = $string;
        } else {
            die($filePath.": ".$string." is invalid");
        }
        return $type;
    }
}

new PHP7Converter("src");