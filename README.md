### Compatible  ###

* Magento 1.9.x
* OpenMage 1.9.x (https://github.com/OpenMage/magento-lts)

### How to use  ###
to Image:
Mage::helper('webp/webp')->toWebp($image);

to String:
Mage::helper('webp/webp')->toWebpinHtml($string);

### Configuration  ###
Under System> Configuration> Web> WebP Images:

    Value of Enabled
    Value of cwebp Method Enabled
    Value of Path to cwebp
    Value of GD Method Enabled
    Value of Disable with transparent images
    Value of Load WebP CSS
    Information on GD support

What is the location of the cwebp binary on your system?

    Which version of cwebp is it?
    Did you try to manually convert troubling images using this CLI?
