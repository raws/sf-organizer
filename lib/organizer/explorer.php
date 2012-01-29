<?php
namespace organizer;

class Explorer {
    private $paths;
    private $pattern;
    
    private $iterators;
    private $entries;
    
    public function __construct($paths, $pattern) {
        $this->paths = $paths;
        $this->pattern = $pattern;
    }
    
    private function construct_iterators() {
        foreach ($this->paths as $path) {
            $iterator = new \RecursiveDirectoryIterator($path);
            $iterator = new \RecursiveIteratorIterator($iterator);
            $iterator = new \RegexIterator($iterator, $this->pattern, \RecursiveRegexIterator::GET_MATCH);
            $this->iterators[] = $iterator;
        }
    }
    
    public function scan() {
        foreach ($this->paths as $path) {
            $iterator = new \RecursiveDirectoryIterator($path);
            $iterator = new \RecursiveIteratorIterator($iterator);
            $iterator = new \RegexIterator($iterator, $this->pattern, \RecursiveRegexIterator::GET_MATCH);
            
            foreach ($iterator as $entry) {
                $this->entries[] = $entry[0];
            }
        }
        
        return $this->entries;
    }
    
    public function get_iterators($construct = false) {
        if (!isset($this->iterators)) {
            $this->iterators = array();
        }
        
        if ($construct === true || empty($this->iterators)) {
            $this->construct_iterators();
        }
        
        return $this->iterators;
    }
    
    public function get_entries($limit = 25, $scan = false) {
        if (!isset($this->entries)) {
            $this->entries = array();
        }
        
        if ($scan === true || empty($this->entries)) {
            $counter = 0;
            foreach ($this->get_iterators() as $iterator) {
                foreach ($iterator as $entry) {
                    $this->entries[] = $entry[0];
                    if (++$counter >= $limit) break;
                }
            }
        }
        
        return $this->entries;
    }
}
?>