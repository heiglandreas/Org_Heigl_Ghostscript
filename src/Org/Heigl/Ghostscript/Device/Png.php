<?php
/**
 * $Id$
 *
 * Copyright (c) 2008-2009 Andreas Heigl<andreas@heigl.org>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @category   Org_Heigl
 * @package    Org_Heigl_Ghostscript
 * @subpackage Devices
 * @author     Andreas Heigl <a.heigl@wdv.de>
 * @copyright  2008-2009 Andreas Heigl
 * @license    http://www.opensource.org/licenses/mit-license.php MIT-License
 * @version    SVN: $Revision$
 * @since      04.06.2009
 */

/** Org_Heigl_Ghostscript_Device_Abstract */
require_once 'Org/Heigl/Ghostscript/Device/Abstract.php';

/**
 * This class defines interfaces for the PNG-Driver family for Ghostscript
 *
 * @category   Org_Heigl
 * @package    Org_Heigl_Ghostscript
 * @subpackage Devices
 * @author     Andreas Heigl <a.heigl@wdv.de>
 * @copyright  2008-2009 Andreas Heigl
 * @license    http://www.opensource.org/licenses/mit-license.php MIT-License
 * @version    SVN: $Revision$
 * @since      04.06.2009
 */
class Org_Heigl_Ghostscript_Device_Png extends Org_Heigl_Ghostscript_Device_Abstract
{
    /**
     * Exactly what driver shall be used.
     *
     * This can be one of 'pngalpha', 'png16m', 'png256', 'png16', 'pnggray' or
     * 'pngmono'
     *
     * @var string $_device
     */
    protected $_device = 'pngalpha';

    /**
     * Get the name of the device as Ghostscript expects it
     *
     * @return string
     */
    public function getDevice () {
        return $this -> _device;
    }

    /**
     * Set the device
     *
     * It can be one of 'pngalpha', 'png16m', 'png256', 'png16', 'pnggray' or
     * 'pngmono'.
     *
     * If the string does not match we use 'pngalpha' as default
     *
     * @param string  $device
     *
     * @return Org_Heigl_Ghostsccript_Driver_Png
     */
    public function setDevice ( $device ) {

        $device = strtolower ( $device );
        $devices = array (
                    'pngalpha',
                    'png16m',
                    'png256',
                    'png16',
                    'pnggray',
                    'pngmono'
                   );
        if ( ! in_array ( $device, $devices ) ) {
            $this -> _device = 'pngalpha';
        } else {
            $this -> _device = $device;
        }

        return $this;
    }

    /**
     * Get the complete parameter string for this device
     *
     * @return string
     */
    public function getParameterString () {
        $string = ' -sDEVICE=' . $this -> getDevice ();
        if ( ( 'pngalpha' === $this -> getDevice () ) && ( null !== $this -> getBackgroundColor () ) ) {
            $string .= ' -dBackgroundColor=16#' . $this -> getBackgroundColor ();
        }

        return $string;
    }

    /**
     * Set the Background-Color for the Alpha-PNG
     *
     * This can be any HEX-Color-definition WITHOUT the leading '#'
     *
     * @param string $color
     *
     * @return Org_Heigl_Ghostscript_Device_Png
     */
    public function setBackgroundColor ( $color ) {

        if ( ! preg_match ( '/^[a-fA-F0-9]{6}$/', $color ) ) {
            $color = null;
        }

        $this -> _color = $color;

        return $this;
    }

    /**
     * Get the BackgroundColor
     *
     * @return string
     */
    public function getBackgroundColor () {
        return $this -> _color;
    }

    /**
     * The Background-Color of the PNG
     *
     * @var string $_color
     */
    protected $_color = null;
    /**
     * Get the file ending
     *
     * @return string
     */
    public function getFileEnding () {
        return 'png';
    }
}