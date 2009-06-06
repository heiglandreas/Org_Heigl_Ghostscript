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

/** Org_Heigl_Ghostscript */
require_once 'Org/Heigl/Ghostscript.php';

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
class Org_Heigl_GhostscriptTest extends PHPUnit_Framework_TestCase
{
    public function setup () {
        Org_Heigl_Ghostscript::setGsPath();
    }
    public function testForSetGhostscriptPath () {
        $path = Org_Heigl_Ghostscript::getGsPath ();
        $this -> assertEquals ( exec ( 'which gs'), $path );
    }

    public function testForNonEmptyGhostscriptPath () {
        $path = Org_Heigl_Ghostscript::getGsPath ();
        $this -> assertNotEquals ( null, $path );
    }

    public function testSettingOfGhostscriptPath () {
        Org_Heigl_Ghostscript::setGsPath();
        $this -> assertEquals(exec('which gs'), Org_Heigl_Ghostscript::getGsPath());
        Org_Heigl_Ghostscript::setGsPath('This/is/a/fake');
        $this -> assertEquals('This/is/a/fake', Org_Heigl_Ghostscript::getGsPath());
    }

    /**
     * @setExpectedException InvalidArgumentException
     */
    public function testSettingFileWorks(){
        $f = new Org_Heigl_Ghostscript();
        $f -> setInputFile(__FILE__);
        $comp = new SplFileInfo ( __FILE__);
        $this -> assertEquals ($comp, $f -> getInputFile());
    }

    public function testSettingOutfileWorks () {
        $f = new Org_Heigl_Ghostscript();
        $f -> setOutputFile ( 'test' );
        $tmp = sys_Get_Temp_dir ();
        $this -> assertEquals ( $tmp . DIRECTORY_SEPARATOR . 'test', $f -> getOutputFile () );
        $f -> setOutputFile ( '/this/is/a/test' );
        $this -> assertEquals ( '/this/is/a/test', $f -> getOutputFile () );
        $f -> setInputFile ( '/some/other/file' );
        $f -> setOutputFile ( 'test' );
        $this -> assertEquals ( '/some/other/test', $f -> getOutputFile () );
    }

    public function testDefaultDevice () {
        $f = new Org_Heigl_Ghostscript ();
        $d = $f -> getDevice ();
        $this -> assertTrue ( $d instanceof Org_Heigl_Ghostscript_Device_Png );
        $this -> assertEquals ( 'pngalpha', $d -> getDevice () );
    }

    public function testUseCie () {
        $f = new Org_Heigl_Ghostscript ();
        $this -> assertFalse ( $f -> useCie () );
        $f -> setUseCie ( true );
        $this -> assertTrue ( $f -> useCie () );
        $f -> setUseCie ( false );
        $this -> assertFalse ( $f -> useCie () );
        $f -> setUseCie ();
        $this -> assertTrue ( $f -> useCie () );
        $f -> setUseCie ('test');
        $this -> assertTrue ( $f -> useCie () );
        $f -> setUseCie ( 0 );
        $this -> assertFalse ( $f -> useCie () );
    }

    public function testResolution () {
        $f = new Org_Heigl_Ghostscript ();
        $this -> assertEquals ( '72', $f -> getResolution () );
        $f -> setResolution ( '92' );
        $this -> assertEquals ( '92', $f -> getResolution () );
        $f -> setResolution ( 12, 14 );
        $this -> assertEquals ( '12x14', $f -> getResolution () );
        $f -> setResolution ( 33, null );
        $this -> assertEquals ( '33', $f -> getResolution () );
    }

    public function testTextAntiAliasing () {
        $f = new Org_Heigl_Ghostscript ();
        $this -> assertEquals ( 0, $f -> getTextAntiAliasing () );
        $f -> setTextAntiAliasing ( 5 );
        $this -> assertFalse ( $f -> isTextAntiAliasingSet () );
        $this -> assertEquals ( 0, $f -> getTextAntiAliasing () );
        $f -> setTextAntiAliasing ( Org_Heigl_Ghostscript::ANTIALIASING_HIGH );
        $this -> assertTrue ( $f -> isTextAntiAliasingSet () );
        $this -> assertEquals ( 4, $f -> getTextAntiAliasing () );
        $f -> setTextAntiAliasing ( Org_Heigl_Ghostscript::ANTIALIASING_MEDIUM );
        $this -> assertTrue ( $f -> isTextAntiAliasingSet () );
        $this -> assertEquals ( 2, $f -> getTextAntiAliasing () );
        $f -> setTextAntiAliasing ( Org_Heigl_Ghostscript::ANTIALIASING_LOW );
        $this -> assertTrue ( $f -> isTextAntiAliasingSet () );
        $this -> assertEquals ( 1, $f -> getTextAntiAliasing () );
        $f -> setTextAntiAliasing ( Org_Heigl_Ghostscript::ANTIALIASING_NONE );
        $this -> assertFalse ( $f -> isTextAntiAliasingSet () );
        $this -> assertEquals ( 0, $f -> getTextAntiAliasing () );
    }

    public function testGraphicsAntiAliasing () {
        $f = new Org_Heigl_Ghostscript ();
        $this -> assertEquals ( 0, $f -> getGraphicsAntiAliasing () );
        $f -> setGraphicsAntiAliasing ( 5 );
        $this -> assertFalse ( $f -> isGraphicsAntiAliasingSet () );
        $this -> assertEquals ( 0, $f -> getGraphicsAntiAliasing () );
        $this -> assertFalse ( $f -> isGraphicsAntiAliasingSet () );
        $f -> setGraphicsAntiAliasing ( Org_Heigl_Ghostscript::ANTIALIASING_HIGH );
        $this -> assertEquals ( 4, $f -> getGraphicsAntiAliasing () );
        $this -> assertTrue ( $f -> isGraphicsAntiAliasingSet () );
        $f -> setGraphicsAntiAliasing ( Org_Heigl_Ghostscript::ANTIALIASING_MEDIUM );
        $this -> assertEquals ( 2, $f -> getGraphicsAntiAliasing () );
        $this -> assertTrue ( $f -> isGraphicsAntiAliasingSet () );
        $f -> setGraphicsAntiAliasing ( Org_Heigl_Ghostscript::ANTIALIASING_LOW );
        $this -> assertEquals ( 1, $f -> getGraphicsAntiAliasing () );
        $this -> assertTrue ( $f -> isGraphicsAntiAliasingSet () );
        $f -> setGraphicsAntiAliasing ( Org_Heigl_Ghostscript::ANTIALIASING_NONE );
        $this -> assertEquals ( 0, $f -> getGraphicsAntiAliasing () );
        $this -> assertFalse ( $f -> isGraphicsAntiAliasingSet () );

    }

    public function testRenderingStrings () {
        $f = new Org_Heigl_Ghostscript ();
        $dir = dirname (__FILE__) . DIRECTORY_SEPARATOR . 'Ghostscript' . DIRECTORY_SEPARATOR . 'support' . DIRECTORY_SEPARATOR;
        $filename = $dir . 'test.pdf';
        $path = Org_Heigl_Ghostscript::getGsPath ();
        $this -> assertEquals ( '', $f -> getRenderString () );
        $f -> setInputFile ( $filename );
        $expect = $path . ' -dSAFER -dQUIET -dNOPLATFONTS -dNOPAUSE -dBATCH -sOutputFile="' . $dir  . 'output.png" -sDEVICE=pngalpha -r72 "' .$filename . '"';
        $this -> assertEquals ( $expect, $f -> getRenderString () );
        $f -> setTextAntiAliasing ( Org_Heigl_Ghostscript::ANTIALIASING_HIGH );
        $expect = $path . ' -dSAFER -dQUIET -dNOPLATFONTS -dNOPAUSE -dBATCH -sOutputFile="' . $dir  . 'output.png" -sDEVICE=pngalpha -r72 -dTextAlphaBits=4 "' .$filename . '"';
        $this -> assertEquals ( $expect, $f -> getRenderString () );
        $f -> setGraphicsAntiAliasing ( Org_Heigl_Ghostscript::ANTIALIASING_HIGH );
        $expect = $path . ' -dSAFER -dQUIET -dNOPLATFONTS -dNOPAUSE -dBATCH -sOutputFile="' . $dir  . 'output.png" -sDEVICE=pngalpha -r72 -dTextAlphaBits=4 -dGraphicsAlphaBits=4 "' .$filename . '"';
        $this -> assertEquals ( $expect, $f -> getRenderString () );
        $f -> setTextAntiAliasing ( Org_Heigl_Ghostscript::ANTIALIASING_NONE );
        $expect = $path . ' -dSAFER -dQUIET -dNOPLATFONTS -dNOPAUSE -dBATCH -sOutputFile="' . $dir  . 'output.png" -sDEVICE=pngalpha -r72 -dGraphicsAlphaBits=4 "' .$filename . '"';
        $this -> assertEquals ( $expect, $f -> getRenderString () );
        $f -> setDevice ( 'jpeg' );
        $expect = $path . ' -dSAFER -dQUIET -dNOPLATFONTS -dNOPAUSE -dBATCH -sOutputFile="' . $dir  . 'output.jpeg" -sDEVICE=jpeg -dJPEGQ=75 -dQFactor=0.75 -r72 -dGraphicsAlphaBits=4 "' .$filename . '"';
        $this -> assertEquals ( $expect, $f -> getRenderString () );
    }

    public function testRendering () {
        $f = new Org_Heigl_Ghostscript ();
        $this -> assertFalse ( $f -> render () );
        $filename = dirname (__FILE__) . DIRECTORY_SEPARATOR . 'Ghostscript' . DIRECTORY_SEPARATOR . 'support' . DIRECTORY_SEPARATOR . 'test.pdf';
        $f -> setInputFile ( $filename );
        $this -> assertTrue ( $f -> render () );
        unlink ( dirname ( $filename ) . DIRECTORY_SEPARATOR . 'output.png' );
    }

}