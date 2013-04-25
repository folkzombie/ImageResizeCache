//include the class

include("ImageResizeCache/ImageResizeCache.class.php");

//initialize 

$image = new ImageResizeCache('/PATH/TO/IMAGE/', 'IMAGE', IMAGE-SIZE);

//get image

$image = $image->getImage();

To display the image "echo" $image as the src of HTML img tag.