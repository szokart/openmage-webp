<?php
/**
 * Septsite Webp for Magento 1 / OpenMage
 *
 * @package     Septsite_Webp
 * @author      septsite (http://septsite.pl/)
 * @copyright   2019 Septsite <http://septsite.pl/>
 */

/**
 * Webp version
 */
class Septsite_Webp_Block_Adminhtml_System_Config_Field_Gd extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * Override method to output our custom HTML with JavaScript
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return String
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $html = parent::_getElementHtml($element);

        $html .= $this->getCheckLine('GD support for WebP', $this->doesGdInfoIncludeWebp());
        $html .= $this->getCheckLine('Function <code>imagewebp()</code> available', $this->doesImagewebpFunctionExist());

        return $html;
    }

    /**
     * @param $label
     * @param $value
     *
     * @return string
     */
    protected function getCheckLine($label, $value)
    {
        return '<p class="note">' . $label . ' = ' . $this->getCheckLabel($value) . '</p>';
    }

    /**
     * @param $value
     *
     * @return string
     */
    protected function getCheckLabel($value)
    {
        if ($value == true) {
            return '<span style="color:green;">' . $this->__('Yes') . '</span>';
        }

        return '<span style="color:red;">' . $this->__('No') . '</span>';
    }

    /**
     * @return bool
     */
    protected function doesImagewebpFunctionExist()
    {
        if (function_exists('imagewebp')) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function doesGdInfoIncludeWebp()
    {
        if (function_exists('gd_info') == false) {
            return false;
        }

        $gdInfo = gd_info();
        foreach ($gdInfo as $gdLabel => $gdValue) {
            if (stristr($gdLabel, 'webp')) {
                return (bool) $gdValue;
            }
        }

        return false;
    }
}
