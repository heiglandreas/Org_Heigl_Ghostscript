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
 * This abstract class defines interfaces for Ghostscript devices
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
class Org_Heigl_Ghostscript_Device_Jpeg extends Org_Heigl_Ghostscript_Device_Abstract
{
    /**
     * Get the name of the device as Ghostscript expects it
     *
     * @return string
     */
    public function getDevice () {
        return 'jpeg';
    }

    /**
     * Get the complete parameter string for this device
     *
     * @return string
     */
    public function getParameterString () {
        $string = '';
        $string .= ' -sDEVICE=' . $this -> getDevice ();
        $string .= ' -dJPEGQ=' . $this -> getQuality ();
        $string .= ' -dQFactor=' . 1 / 100 * $this -> getQuality ();

        return $string;
    }

    /**
     * Set the Quality of the JPEG
     *
     * This can be any integer from 0 to 100. It defaults to 75
     *
     * @param int $quality
     *
     * @return Org_Heigl_Ghostscript_Device_Jpeg
     */
    public function setQuality ( $quality ) {
        $quality = (int) $quality;

        if ( 100 < $quality ) {
            $quality = 100;
        }

        if ( 0 > $quality ) {
            $quality = 0;
        }

        $this -> _quality = $quality;

        return $this;
    }

    /**
     * Get the Quality of the JPEG
     *
     * @return int
     */
    public function getQuality () {
        return $this -> _quality;
    }

    /**
     * The quality of the JPEG
     *
     * @var int $_quality
     */
    protected $_quality = 75;

    /**
     * Get the file ending
     *
     * @return string
     */
    public function getFileEnding () {
        return 'jpeg';
    }
}