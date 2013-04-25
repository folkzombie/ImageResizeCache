<?php

class ImageResizeCache {
	
	private $path;
	private $filename;
	private $new_size;
	
	public function __construct($path, $filename, $new_size) {          
		
		$this->path = $_SERVER["DOCUMENT_ROOT"] . $path; //file path
		$this->filename = $filename; //file name
		$this->new_size = $new_size; //new image size
		
		//new image file name for saving
		$originalName = explode('.', $filename);
		$savedFileName = $originalName[0] . '_' . $new_size . 'x' . $new_size . '.' . $originalName[1];
		$this->new_image = $this->path . $savedFileName;
		
		$this->web_path = $path . $savedFileName; //path for display
		$this->the_image = $this->path . $filename; //path and file for initial creation
		
	}
	
	///////////////////////////////////////////////////////////////GET FILE TYPE
	private function getFileType() {
		$fileType = getimagesize($this->the_image);
		return $fileType['mime'];
	}
	
	///////////////////////////////////////////////////////////////GENEARTE "SOURCE" IMAGE FROM FILE TYPE
	private function generateImage($fileType) {
		switch($fileType) {
			case "image/pjpeg" || "image/jpeg": $source = imagecreatefromjpeg($this->the_image); break;
			case "image/png": $source = imagecreatefrompng($this->the_image); break;
			case "image/gif": $source = imagecreatefromgif($this->the_image); break;
		}
		return $source;	
	}
	
	///////////////////////////////////////////////////////////////RESIZE IMAGE
	private function resizeImage($fileType) {
		
		$source = $this->generateImage($fileType);
		
		//get width and height of original image
		list($width_orig, $height_orig) = getimagesize($this->the_image); 
		
		//get new dimensions
		$width = $this->new_size;
		$height = $this->new_size;
		if($width && ($width_orig < $height_orig)) {
			$width = round(($height / $height_orig) * $width_orig);
		} else {
			$height = round(($width / $width_orig) * $height_orig);
		}
		
		//create new image
		$image = imagecreatetruecolor($width, $height);
		
		//maintain transparency if needed
		imagealphablending($image, false);
		$color = imagecolorallocatealpha($image, 0, 0, 0, 127);
		imagefill($image, 0, 0, $color);
		imagesavealpha($image, true);
		
		//copy/merge the source image and the new image
		imagecopyresampled($image, $source, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);	
		
		$this->image = $image;
		
	}
	
	///////////////////////////////////////////////////////////////SAVE IMAGE
	private function saveImage() {
		imagejpeg($this->image, $this->new_image); 
		imagedestroy($this->image);
	}
	
	///////////////////////////////////////////////////////////////GET IMAGE
	public function getImage() { 
		if(file_exists($this->new_image)) {
			return $this->web_path;
		} else {
			$fileType = $this->getFileType();
			$this->resizeImage($fileType);	
			$this->saveImage();
			return $this->web_path;
		}
	}
	
}

?>