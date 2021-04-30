<?php

/**
 * LoaderOne for coding better
 * 
 * (c) Johnbeanlee <278636108@qq.com>
 * 
 */

namespace LoaderOne;

class Autoloader
{
    private $basePath;
    
    private $findPaths = [];
    
    private $namspace2dirCacheMissing = [];
    
    private $namspace2dirCacheFile = 'class_mapping.php';
    
    private static $namspace2dir = [];
    
    private static $namspace2dirCache = null;
    
    public function __construct()
    {
        $this->findPaths = $this->include("find_path.php");
        
        $this->namspace2dirCacheFilePath = __DIR__ . DIRECTORY_SEPARATOR . $this->namspace2dirCacheFile;
    }
    
    public function setBasePath($path)
    {
        $this->basePath = $path;
    }
    
    public function run($class)
    {
        $pathInfos = explode("\\", $class);
        array_pop($pathInfos);
        $classNamespace = implode("\\", $pathInfos);
        
        if ($namespaceBasePath = $this->getClassMappingCache($classNamespace)) {
            if ($namespaceBasePath === DIRECTORY_SEPARATOR) {
                $filePath = $this->basePath . DIRECTORY_SEPARATOR . $class . '.php';
            } else {
                $filePath = $this->basePath . DIRECTORY_SEPARATOR . $namespaceBasePath . DIRECTORY_SEPARATOR . $class . '.php';
            }
            
            return $this->include($filePath);
        } else {
            $this->namspace2dirCacheMissing[] = $classNamespace; 
        }
        
        $namespaceBasePath = DIRECTORY_SEPARATOR;
        $filePath = $this->basePath . DIRECTORY_SEPARATOR . $class . '.php';
        
        if ( ! is_file($filePath) ) {
            foreach ($this->findPaths as $path) {
                $namespaceBasePath = $path;
                
                $filePath = $this->basePath . DIRECTORY_SEPARATOR . $namespaceBasePath . DIRECTORY_SEPARATOR . $class . '.php';
                
                if (is_file($filePath)) {
                    self::$namspace2dir[$classNamespace] = $namespaceBasePath;
                    break;
                }
            }
            
        } else {
            self::$namspace2dir[$classNamespace] = $namespaceBasePath;
        }
        
        if ( ! is_file($filePath)) {
            throw new \Exception("Class File : `{$class}` not be found, please check.");
        }
        
        return $this->include($filePath);
    }
    
    private function getClassMappingCache($namespace)
    {
        if ( ! file_exists($this->namspace2dirCacheFilePath)) return null;
        
        if ( ! self::$namspace2dirCache) self::$namspace2dirCache = $this->include($this->namspace2dirCacheFilePath);
        
        if (isset(self::$namspace2dirCache[$namespace])) return self::$namspace2dirCache[$namespace];
        
        return null;
    }
    
    public function __destruct()
    {
        if ( ! $this->namspace2dirCacheMissing) return;
        
        file_put_contents(
            $this->namspace2dirCacheFilePath, 
            "<?php \r\n return " . var_export(self::$namspace2dir, true) . ';'
        );
    }
    
    function include($file)
    {
        return include_once($file);
    }
    
}

$autoloader = new Autoloader();
spl_autoload_register([$autoloader, 'run']);

return $autoloader;