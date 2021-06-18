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
    
    private static $namspace2dirCache = [];
    
    private static $_instance;
    
    public function __construct()
    {
        self::$_instance = $this;
        
        $this->namspace2dirCacheFilePath = __DIR__ . DIRECTORY_SEPARATOR . $this->namspace2dirCacheFile;
    }
    
    public function setBasePath($path)
    {
        $this->basePath = $path;
        
        $this->findPaths = $this->include("find_path.php");
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
            throw new \Exception("Class File : `{$class}` not be found at '{$filePath}', please check.");
        }
        
        return $this->include($filePath);
    }
    
    private function getClassMappingCache($namespace)
    {
        if ( ! file_exists($this->namspace2dirCacheFilePath)) return [];
        
        if ( ! self::$namspace2dirCache) self::$namspace2dirCache = $this->include($this->namspace2dirCacheFilePath);
        
        if (isset(self::$namspace2dirCache[$namespace])) return self::$namspace2dirCache[$namespace];
        
        return [];
    }
    
    public function __destruct()
    {
        if ( ! $this->namspace2dirCacheMissing) return;
        
        $cacheContent = array_merge(self::$namspace2dirCache, self::$namspace2dir);
        file_put_contents(
            $this->namspace2dirCacheFilePath, 
            "<?php \r\n return " . var_export( $cacheContent, true) . ';'
        );
    }
    
    function include($file)
    {
        return include_once($file);
    }
    
    public function basePath()
    {
        return $this->basePath;
    }
    
    public static function getInstance()
    {
        if ( ! self::$_instance) self::$_instance = new self();
        
        return self::$_instance;
    }
}

$autoLoader = Autoloader::getInstance();

spl_autoload_register([$autoLoader, 'run']);

return $autoLoader;
