<?php
/**
 * Septsite Webp for Magento / OpenMage
 *
 * @package     Septsite_Webp
 * @author      septsite (http://septsite.pl/)
 * @copyright   2019 Septsite <http://septsite.pl/>
 */

/**
 * Webp file helper
 */
class Septsite_Webp_Helper_File extends Mage_Core_Helper_Abstract
{
    /**
     * Method to check to see if a file exists or not
     *
     * @param string $file
     * @return bool
     * @throws Zend_Validate_Exception
     */
    public function exists($file)
    {
        $file = $this->stripInvalidCharacters($file);

        $validator = new Zend_Validate_File_Exists();
        $validator->addDirectory(dirname($file));

        $filter = new Zend_Filter_BaseName;
        if ($validator->isValid($filter->filter($file))) {
            return true;
        }

        return false;
    }

    /**
     * Method to check to see if a file is writable or not
     *
     * @param $file
     *
     * @return bool
     */
    public function isWritable($file)
    {
        $fileIo = new Varien_Io_File;

        return $fileIo->isWriteable($file);
    }

    /**
     * Method to check to see if a file is writable or not
     *
     * @param $file
     *
     * @return bool
     */
    public function isWritableDir($file)
    {
        $fileIo = new Varien_Io_File;
        $fileHandler = new Varien_File_Object($file);

        return $fileIo->isWriteable($fileHandler->getDirName());
    }

    /**
     * Method to return the modification time of a file
     *
     * @param $file
     *
     * @return int
     */
    public function getModificationTime($file)
    {
        $fileHandler = new Varien_File_Object($file);

        return $fileHandler->getCTime();
    }

    /**
     * Method to check if a $file1 is newer than a $file2
     *
     * @param $file1
     * @param $file2
     *
     * @return bool
     */
    public function isNewerThan($file1, $file2)
    {
        $file1ModificationTime = $this->getModificationTime($file1);
        $file2ModificationTime = $this->getModificationTime($file2);

        if ($file1ModificationTime > $file2ModificationTime) {
            return true;
        }

        return false;
    }

    /**
     * @param $fileName
     * @return string
     */
    private function stripInvalidCharacters($fileName)
    {
        $fileName = preg_replace('/([^a-zA-Z0-9\.\-\_\/]+)/', '', $fileName);
        return $fileName;
    }
}
