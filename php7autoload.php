<?php
class PHP7Converter
{
    private $results = [];
    
    public function __construct($folder)
    {
        $this->scan($folder);
        $this->save();
        echo "COMPLETE!";
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
                              
                $returnType = "void";
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
                    preg_match("/function\s+([^\(]+)\((.*)\)/", $line, $matches);
                    if($matches) {
                        $parameters = "";
                        if(!empty($matches[2])) {
                            $matches1 = [];
                            $parameters = $matches[2];
                            preg_match_all("/([a-zA-Z0-9\\\]+\s+)?\\$([a-zA-Z0-9\_]+)/", $matches[2], $matches1);
                            if($matches1[0]) {
                                foreach($matches1[2] as $i=>$variable) {
                                    $variable = '$'.$variable;
                                    if(!isset($parameterTypes[$variable])) {
                                        die($filePath.": ".$matches[1]." misses ".$variable);
                                    }
                                    if (empty($matches1[1][$i])) {
                                        $parameters = str_replace($matches1[0][$i], $parameterTypes[$variable]." ".$matches1[0][$i], $parameters);
                                    }
                                }
                            }
                        }
                        
                        $this->results[$filePath][$matches[1]]["parameters"] = $parameters;
                        $this->results[$filePath][$matches[1]]["returns"] = $returnType;
                        
                        $returnType = "void";
                        $parameterTypes = [];
                    }
                }
                fclose($handle);
            }
        }
    }
    
    private function save() {
        foreach($this->results as $fileName=>$info) {
            file_put_contents ($fileName, preg_replace_callback("/function\s+([^\(]+)\((.*)\)/", function($matches) use($info) {
                return "function ".$matches[1]."(".$info[$matches[1]]["parameters"]."): ".$info[$matches[1]]["returns"];
            }, file_get_contents($fileName)));
        }
    }
    
    private function getType($filePath, $string) {
        $type = "";
        if ($type=="void" || !$string) {
            $type = "void";
        } else if($string=="boolean") {
            $type = "bool";
        } else if($string=="integer") {
            $type = "int";
        } else if($string=="double") {
            $type = "float";
        }else if(in_array($string, ["array", "string"])) {
            $type = $string;
        }else if (strpos($string, "[")!==false) {
            $type = "array";
        } else if (ctype_upper($string[0])) {
            $type = $string;
        } else {
            die($filePath.": ".$string." is invalid");
        }
        return $type;
    }
}

class AutoloadConverter 
{
    private $results = [];
    
    public function __construct($folder, $namespace)
    {
        $this->scan($folder, $namespace);
        $this->save();
        echo "COMPLETE!";
    }
    
    private function scan($folder, $namespace) {
        $files = scandir($folder);
        foreach($files as $file){
            if(in_array($file, [".", ".."])) {
                continue;
            } else if(is_dir($folder."/".$file)) {
                // recurse
                $this->scan($folder."/".$file, $namespace."\\".$file);
            } else if(strpos($file, ".php")) {
                // open
                $filePath = $folder."/".$file;
                $contents = file_get_contents($filePath);
            }
        }
    }
    
    private function save() {
    }
}
// new PHP7Converter("drivers");
