<?php
/**
 * Septsite Webp for Magento OpenMage
 *
 * @package     Septsite_Webp
 * @author      septsite (http://septsite.pl/)
 * @copyright   2019 Septsite <http://septsite.pl/>
 */

/**
 * Webp helper
 */
class Septsite_Webp_Lib_Io_File extends Varien_Io_File
{
    public function setIwd($iwd)
    {
        $this->_iwd = $iwd;
    }

    public function setCwd($cwd)
    {
        $this->_cwd = $cwd;
    }
}
