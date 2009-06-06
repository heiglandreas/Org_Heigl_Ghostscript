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
 * @category  Org_Heigl
 * @package   Org_Heigl_Ghostscript
 * @author    Andreas Heigl <andreas@heigl.org>
 * @copyright 2008 Andreas Heigl<andreas@heigl.org>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT-License
 * @version   SVN: $Revision$
 * @since     03.06.2009
 */

/** Org_Heigl_Ghostscript_Device_Png */
require_once 'Org/Heigl/Ghostscript/Device/Jpeg.php';

/** PHPUnit_Framework_TestCase */
require_once 'PHPUnit/Framework/TestCase.php';

/**
 * This class tests the Org_Heigl_Ghostscript-Class
 *
 * @category  Org_Heigl
 * @package   Org_Heigl_Ghostscript
 * @author    Andreas Heigl <andreas@heigl.org>
 * @copyright 2008 Andreas Heigl<andreas@heigl.org>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT-License
 * @version   SVN: $Revision$
 * @since     03.06.2009
 */
class Org_Heigl_Ghostscript_Device_JpegTest extends PHPUnit_Framework_TestCase
{
    public function testCreationOfJpegClass () {
        $f = new Org_Heigl_Ghostscript_Device_Jpeg ();
        $this -> assertTrue ( $f instanceof Org_Heigl_Ghostscript_Device_Jpeg );
        $this -> assertEquals ( 'jpeg', $f -> getDevice ());
    }

    public function testSettingQuality () {
        $f = new Org_Heigl_Ghostscript_Device_Jpeg ();
        $this -> assertEquals ( 75, $f -> getQuality () );
        $f -> setQuality ( 50 );
        $this -> assertEquals ( 50, $f -> getQuality () );
        $f -> setQuality ( '200' );
        $this -> assertEquals ( 100, $f -> getQuality () );
        $f -> setQuality ( 200 );
        $this -> assertEquals ( 100, $f -> getQuality () );
        $f -> setQuality ( -10 );
        $this -> assertEquals ( 0, $f -> getQuality () );

    }

    public function testgettingParamString () {
        $f = new Org_Heigl_Ghostscript_Device_Jpeg ();
        $this -> assertEquals ( ' -sDEVICE=jpeg -dJPEGQ=75 -dQFactor=0.75', $f -> getParameterString () );
        $f -> setQuality ( 50 );
        $this -> assertEquals ( ' -sDEVICE=jpeg -dJPEGQ=50 -dQFactor=0.5', $f -> getParameterString () );
        $f -> setQuality ( 200 );
        $this -> assertEquals ( ' -sDEVICE=jpeg -dJPEGQ=100 -dQFactor=1', $f -> getParameterString () );
        $f -> setQuality ( -5 );
        $this -> assertEquals ( ' -sDEVICE=jpeg -dJPEGQ=0 -dQFactor=0', $f -> getParameterString () );

    }

    public function testFileEnding () {
        $f = new Org_Heigl_Ghostscript_Device_Jpeg ();
        $this -> assertEquals ( 'jpeg', $f -> getFileEnding () );
    }
}