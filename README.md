//include the class
include("ImageResizeCache/ImageResizeCache.class.php");

//initialize 
$image = new ImageResizeCache('/PATH/TO/IMAGE/', 'IMAGE', IMAGE-SIZE);

//get image
$image = $image->getImage();

//display the image
<img src="<?=image;?>" />