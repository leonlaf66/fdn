<?php
namespace common\helper;

require_once dirname(__FILE__).'/media/Image.php';

class Media
{
	public $type = null;
	public $baseDir = null;
	public $baseUrl = null;
	public $placeholder = 'placeholder.jpg';

	protected function __construct($type) 
	{
		$this->type = $type;

		//init config
		$this->baseDir = media_file();
		$this->baseUrl = media_url();
	}

	public static function init($type)
	{
		static $instances = array();
		if(! isset($instances[$type])) {
			$instances[$type] = new self($type);
		}
		return $instances[$type];
	}

	public function setPlaceholder($placeholder)
	{
		$this->placeholder = $placeholder;
		return $this;
	}

	public function getDir()
	{
		return $this->baseDir.'/'.$this->type;
	}

	public function getFile($image)
	{
		return $this->getDir().'/'.$image;
	}

	public function getCacheDir()
	{
		return $this->baseDir.'/cache/'.$this->type; 
	}

	public function getCacheUrl()
	{
		return $this->baseUrl.'/cache/'.$this->type; 
	}

	public function getUrl($image)
	{
		$rawFile = $this->getFile($image);
		if(! file_exists($rawFile)) {
			$image = $this->placeholder;
		}
		return $this->baseUrl.'/'.$this->type.'/'.$image;
	}

	public function getObject($image)
	{
		$file = $this->getFile($image);
		if(! file_exists($file) || ! is_file($file)) {
			$file = $this->getFile($this->placeholder);
		}
		return new ImageObject($file, $this->getCacheDir(), $this->getCacheUrl());
	}

	public function getImageInstance($image)
	{
		return $this->getObject($image);
	}

	public function getUploader()
	{
		return new Uploader($this->getDir());
	}
}

class Uploader 
{
	public $dir = null;

	public function __construct($dir) 
	{
		$this->dir = $dir;
	}

	public function uploadAsURLData($fileName, $fileData)
	{
		list($toFile, $subPath) = $this->_createFilePath($fileName);
		
		$data = explode(",", $fileData);
        $data[1] = str_replace(' ', '+', $data[1]);
        $fh = fopen($toFile, "w");
        fwrite($fh, base64_decode($data[1]));
        fclose($fh);

        return array(
        	'fullFilePath'=>$toFile,
        	'subFilePath'=>$subPath
        );
	}

	public function uploadAsFormData(\yii\web\UploadedFile $uploader)
	{
		list($toFile, $subPath) = $this->_createFilePath($uploader->name); 
		$result = $uploader->saveAs($toFile);
		return $subPath;
	}

	private function _createFilePath($fileName)
	{
		$hash = md5($fileName . ':' . microtime(true) . ':' . mt_rand());
		$ext = pathInfo($fileName, PATHINFO_EXTENSION);
		$filePath = sprintf('%s/%s/%s/%s.%s',
            substr($hash,0,2), substr($hash, 4, 2), substr($hash, 6, 2),
            substr($hash, 8), $ext);
		
		$pathInfo = pathinfo($this->dir.'/'.$filePath);
		$pathDir = $pathInfo['dirname'];

		if(! is_dir($pathDir))
			@mkdir($pathDir, 0777, true);

		if(! is_dir($pathDir)) {
			throw new Exception("Create a directory is failed", 1);
		}

		return array(
			$pathInfo['dirname'] . '/' . $pathInfo['basename'],
			$filePath
		);
	}
}

class ImageObject
{
	private $_file = null;
	private $_cacheDir = null;
	private $_cacheUrl = null;

	private $_width = null;
	private $_height = null;
	private $_quality = 100;

	public function __construct($file, $cacheDir, $cacheUrl)
	{
		$this->_file = $file;
		$this->_cacheDir = $cacheDir;
		$this->_cacheUrl = $cacheUrl;
	}

	public function resize($w, $h=null)
	{
		$this->_width = $w;
		$this->_height = $h;

		return $this;
	}

	public function setQuality($q) 
	{
		$this->_quality = $q;
		return $this;
	}

	public function getUrl()
	{
		if(! file_exists($this->_file)) return '';

		$cacheFileExt = pathInfo($this->_file, PATHINFO_EXTENSION);

        $hash = md5($this->_file . serialize(array('w'=>$this->_width, 'h'=>$this->_height)));
        $cachePath = sprintf('%s/%s/%s/%s', substr($hash, 0, 2), substr($hash, 3, 2), substr($hash, 5, 2), substr($hash, 7, 2));
        $cacheFile = sprintf('%s.%s', substr($hash, 9), $cacheFileExt);

        $webCacheFile = $this->_cacheDir . '/' . $cachePath . '/' . $cacheFile;

        // Return URL to the cache image
        if (file_exists($webCacheFile)) {
            return $this->_cacheUrl.'/'.$cachePath . '/' . $cacheFile;
        }
        if(! is_dir($this->_cacheDir . '/' . $cachePath)) {
        	mkdir($this->_cacheDir . '/' . $cachePath, 0777, true);
        }

        $image = \Image::factory($this->_file);
		$image->resize($this->_width, $this->_height);
		$image->save($webCacheFile, $this->_quality);
		$image->background('#222');

		return $this->_cacheUrl.'/'.$cachePath.'/'.$cacheFile;
	}

	public function __toString()
	{
		try {
			return $this->getUrl();
		} catch(\Exception $e) {
			return '404.png';
		}
	}
}