<?php
/**
 * Septsite Webp for Magento / OpenMage
 *
 * @package     Septsite_Webp
 * @author      septsite (http://septsite.pl/)
 * @copyright   2019 Septsite <http://septsite.pl/>
 */

/**
 * Webp helper
 */
class Septsite_Webp_Helper_Webp extends Mage_Core_Helper_Abstract
{
    /**
     * @var Septsite_Webp_Helper_Data
     */
    protected $helper;
    /**
     * @var Septsite_Webp_Helper_File
     */
    protected $fileHelper;

    /**
     * check browser
     * @return int support or not
     */
    public function getBrowser()
    {
        if (Mage::getSingleton('core/session')->getWebp()) {
            $support = Mage::getSingleton('core/session')->getWebp();
        } else {
            if (isset($_SERVER['HTTP_USER_AGENT'])) {
                $u_agent = $_SERVER['HTTP_USER_AGENT'];

                $bname = 'Unknown';
                $platform = 'Unknown';
                $version = "";

                //First get the platform?
                if (preg_match('/linux/i', $u_agent)) {
                    $platform = 'linux';
                } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
                    $platform = 'mac';
                } elseif (preg_match('/windows|win32/i', $u_agent)) {
                    $platform = 'windows';
                } elseif (preg_match('/iPhone|iPad|iPod/i', $u_agent)) {
                    $platform = 'os';
                }


                // Next get the name of the useragent yes seperately and for good reason
                $ub = "";
                if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
                    $bname = 'Internet Explorer';
                    $ub = "MSIE";
                } elseif (preg_match('/Firefox/i', $u_agent)) {
                    $bname = 'Firefox';
                    $ub = "Firefox";
                } elseif (preg_match('/Chrome/i', $u_agent)) {
                    $bname = 'Chrome';
                    $ub = "Chrome";
                } elseif (preg_match('/Safari/i', $u_agent)) {
                    $bname = 'Apple Safari';
                    $ub = "Safari";
                } elseif (preg_match('/Opera/i', $u_agent)) {
                    $bname = 'Opera';
                    $ub = "Opera";
                } elseif (preg_match('/Netscape/i', $u_agent)) {
                    $bname = 'Netscape';
                    $ub = "Netscape";
                }

                // finally get the correct version number
                $known = array('Version', $ub, 'other');
                $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
                if (!preg_match_all($pattern, $u_agent, $matches)) {
                    // we have no matching number just continue
                }

                // see how many we have
                $i = count($matches['browser']);
                if ($i != 1) {
                    //we will have two since we are not using 'other' argument yet
                    //see if version is before or after the name
                    if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                        $version = $matches['version'][0];
                    } else {
                        if (isset($matches['version'][1])) {
                            $version = $matches['version'][1];
                        }
                    }
                } else {
                    $version = $matches['version'][0];
                }

                // check if we have a number
                if ($version == null || $version == "") {
                    $version = "?";
                }

                $support = 0;
                if (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false || $bname == 'Chrome' || $bname == 'Opera') {
                    $support = 1;
                }
                if ($bname == 'Firefox' || $version >= 65) {
                    $support = 1;
                }

                if ($bname == 'Apple Safari' || $bname == 'Unknown') {
                    $support = 0;
                }
            } else {
                $support = 0;
            }
            Mage::getSingleton('core/session')->setWebp($support);
        }


        return $support;
    }

    /**
     * change image to webp format
     * @method toWebp
     * @param  string $image
     * @return string
     * @use Mage::helper('webp/webp')->toWebp($image);
     */
    public function toWebp($image)
    {
        $webp_tmp = null;
        $support = $this->getBrowser();
        //$support = 0;

        if ($support == 1) {
            // webp is supported!
            $arrayjpg = array('.png', '.gif', '.jpg', '.jpeg', '?___SID=U');
            $arraywebp = array('.webp', '.webp', '.webp', '.webp', '');
            $webp_tmp = str_replace($arrayjpg, $arraywebp, $image);
        }
        //Mage::log('$image:'.$image, null, 'webpUrl.log', true);
        if (!empty($webp_tmp)) {
            //Mage::log('$webp_tmp:'.$webp_tmp, null, 'webpUrl.log', true);
        }

        //Mage::log('$_SERVER[DOCUMENT_ROOT]:'.$_SERVER['DOCUMENT_ROOT'], null, 'webpUrl.log', true);
        //Mage::log('$_SERVER[HTTP_HOST]:'.$_SERVER['HTTP_HOST'], null, 'webpUrl.log', true);
        if ($webp_tmp != null) {
            $check_img = str_replace('http://', '', $webp_tmp);
            $check_img = str_replace('https://', '', $check_img);
            $check_img = str_replace($_SERVER['HTTP_HOST'], $_SERVER['DOCUMENT_ROOT'], $check_img);
            //Mage::log('$check_img:'.$check_img, null, 'webpUrl.log', true);
        }

        if ($webp_tmp != null && $webp_tmp != '') {
            if (file_exists($check_img)) {
                //Mage::log('ok exist', null, 'webpUrl.log', true);
                $image = $webp_tmp;
            } else {
                //  Mage::log('try create', null, 'webpUrl.log', true);
                $this->helper = Mage::helper('webp');
                $this->fileHelper = Mage::helper('webp/file');
                $this->convertImageUrlToWebp($image);

                if (!copy($wejscie, $check_img)) {
                    Mage::log('I can\'t copy: ' . $wejscie, null, 'webpurl.log', true);
                };
                if (file_exists($check_img)) {
                    $image = $webp_tmp;
                }
            }
        }

        return $image;
    }

    /**
     * find image in the string and convert them to webp
     * @method toWebpinHtml
     * @param string $string e.g. description
     * @return string e.g. description
     * @use Mage::helper('webp/webp')->toWebpinHtml($string);
     */
    public function toWebpinHtml($string)
    {
        $support = $this->getBrowser();

        if ($support == 1) {
            $string = str_replace('?___SID=U', '', $string);

            preg_match_all('~<img.*?src=["\']+(.*?)["\']+~', $string, $urls);
            foreach ($urls as $urlx) {
                if (is_array($urlx)) {
                    foreach ($urlx as $urlxx) {
                        $test = substr($urlxx, 0, 4);
                        if ($test == 'http') {
                            $urls_webp = Mage::helper('webp/webp')->toWebp($urlxx);
                            $string = str_replace($urlxx, $urls_webp, $string);
                        }
                    }
                } else {
                    $test = substr($urlx, 0, 4);
                    if ($test == 'http') {
                        $urls_webp = Mage::helper('webp/webp')->toWebp($urlx);
                        $string = str_replace($urlx, $urls_webp, $string);
                    }
                }
            }
        }

        return $string;
    }

    protected function convertImageUrlToWebp($imageUrl)
    {
        $imagePath = $this->getImagePathFromUrl($imageUrl);
        if (empty($imagePath)) {
            return false;
        }
        if ($this->fileHelper->exists($imagePath) == false) {
            return false;
        }
        // Construct the new WebP image-name
        $webpPath = $this->helper->convertToWebp($imagePath);
        if (empty($webpPath)) {
            return false;
        }
        if ($this->fileHelper->exists($webpPath) == false) {
            return false;
        }
        // Convert the path back to a valid URL
        $webpUrl = $this->getImageUrlFromPath($webpPath);
        //Mage::log('$webpUrl:'.$webpUrl, null, 'webpUrl.log', true);
        if (empty($webpUrl)) {
            return false;
        }

        return $webpUrl;
    }

    protected function getImagePathFromUrl($imageUrl)
    {
        $systemPaths = $this->helper->getSystemPaths();
        if (preg_match('/^http/', $imageUrl)) {
            foreach ($systemPaths as $systemPath) {
                if (strstr($imageUrl, $systemPath['url'])) {
                    return str_replace($systemPath['url'], $systemPath['path'], $imageUrl);
                }
            }
        }
    }

    protected function getImageUrlFromPath($imagePath)
    {
        $systemPaths = $this->helper->getSystemPaths();
        foreach ($systemPaths as $systemPath) {
            if (strstr($imagePath, $systemPath['path'])) {
                return str_replace($systemPath['path'], $systemPath['url'], $imagePath);
            }
        }
    }
}
