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

/** Org_Heigl_Ghostscript_Device_Abstract */
require_once 'Org/Heigl/Ghostscript/Device/Abstract.php';

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
class Org_Heigl_Ghostscript_Device_AbstractTest extends PHPUnit_Framework_TestCase
{
    public function testCreationOfAbstractClass () {
        $f = new TestClass ();
        $this -> assertTrue ( $f instanceof Org_Heigl_Ghostscript_Device_Abstract );
        $this -> assertEquals ( 'abstract', $f -> getDevice () );
    }

    public function testgettingParamString () {
        $f = new TestClass ();
        $this -> assertEquals ( '', $f -> getParameterString () );
    }


    public function testFileEnding () {
        $f = new TestClass ();
        $this -> assertEquals ( '', $f -> getFileEnding () );
    }
}

class TestClass extends Org_Heigl_Ghostscript_Device_Abstract
{

    public function getDevice () { return 'abstract';}
    public function getFileEnding () {return '';}

}