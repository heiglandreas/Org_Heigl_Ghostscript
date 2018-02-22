<?php
/**
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
 * @author    Andreas Heigl <andreas@heigl.org>
 * @copyright 2008 Andreas Heigl<andreas@heigl.org>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT-License
 */

namespace Org_Heigl\GhostscriptTest;

use Org_Heigl\Ghostscript\Device\DeviceInterface;
use Org_Heigl\Ghostscript\Device\Png;
use Org_Heigl\Ghostscript\Ghostscript;
use PHPUnit\Framework\TestCase;
use Mockery as M;

/**
 * This class tests the Org_Heigl_Ghostscript-Class
 *
 * @author    Andreas Heigl <andreas@heigl.org>
 * @copyright 2008 Andreas Heigl<andreas@heigl.org>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT-License
 */
class GhostscriptTest extends TestCase
{
    public function setup()
    {
        Ghostscript::setGsPath();
    }
    public function testForSetGhostscriptPath()
    {
        $path = Ghostscript::getGsPath();
        $this->assertEquals(exec('which gs'), $path);
    }

    public function testForNonEmptyGhostscriptPath()
    {
        $path = Ghostscript::getGsPath();
        $this->assertNotEquals(null, $path);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingFileWorks()
    {
        $f = new Ghostscript();
        $f->setInputFile(__FILE__);
        $comp = new \SplFileInfo(__FILE__);
        $this->assertEquals($comp, $f->getInputFile());
    }

    public function testSettingOutfileWorks()
    {
        $f = new Ghostscript();
        $f->setOutputFile('test');
        $tmp = sys_Get_Temp_dir();
        $this->assertEquals($tmp . DIRECTORY_SEPARATOR . 'test', $f->getOutputFile());
        $f->setOutputFile('/this/is/a/test');
        $this->assertEquals('/this/is/a/test', $f->getOutputFile());
        $f->setInputFile('/some/other/file');
        $f->setOutputFile('test');
        $this->assertEquals('/some/other/test', $f->getOutputFile());
    }

    /**
     * @dataProvider settingOutfileIsRepresentedInRenderStringProvider
     */
    public function testSettingOutfileIsRepresentedInRenderString($outfile, $expectedResultInRenderString)
    {
        $f = new Ghostscript();
        $f->setDevice(new Png());
        $f->setInputFile(__DIR__ . '/support/test.pdf');
        $f->setOutputFile($outfile);
        $this->assertContains($expectedResultInRenderString, $f->getRenderString());
    }

    public function settingOutfileIsRepresentedInRenderStringProvider()
    {
        return [
            ['test', __DIR__ . '/support/test'],
            ['/this/is/a/test', '/this/is/a/test'],
            ['test.jpeg', __DIR__ . '/support/test.jpeg'],
            ['/this/is/a/test.jpeg', '/this/is/a/test.jpeg'],
        ];
    }

    public function testDefaultDevice()
    {
        $f = new Ghostscript();
        $d = $f->getDevice();
        $this->assertTrue($d instanceof Png);
        $this->assertEquals('pngalpha', $d->getDevice());
    }

    public function testUseCie()
    {
        $f = new Ghostscript();
        $this->assertFalse($f->useCie());
        $f->setUseCie(true);
        $this->assertTrue($f->useCie());
        $f->setUseCie(false);
        $this->assertFalse($f->useCie());
        $f->setUseCie();
        $this->assertTrue($f->useCie());
        $f->setUseCie('test');
        $this->assertTrue($f->useCie());
        $f->setUseCie(0);
        $this->assertFalse($f->useCie());
    }

    public function testResolution()
    {
        $f = new Ghostscript();
        $this->assertEquals('72', $f->getResolution());
        $f->setResolution('92');
        $this->assertEquals('92', $f->getResolution());
        $f->setResolution(12, 14);
        $this->assertEquals('12x14', $f->getResolution());
        $f->setResolution(33, null);
        $this->assertEquals('33', $f->getResolution());
    }

    public function testTextAntiAliasing()
    {
        $f = new Ghostscript();
        $this->assertEquals(0, $f->getTextAntiAliasing());
        $f->setTextAntiAliasing(5);
        $this->assertFalse($f->isTextAntiAliasingSet());
        $this->assertEquals(0, $f->getTextAntiAliasing());
        $f->setTextAntiAliasing(Ghostscript::ANTIALIASING_HIGH);
        $this->assertTrue($f->isTextAntiAliasingSet());
        $this->assertEquals(4, $f->getTextAntiAliasing());
        $f->setTextAntiAliasing(Ghostscript::ANTIALIASING_MEDIUM);
        $this->assertTrue($f->isTextAntiAliasingSet());
        $this->assertEquals(2, $f->getTextAntiAliasing());
        $f->setTextAntiAliasing(Ghostscript::ANTIALIASING_LOW);
        $this->assertTrue($f->isTextAntiAliasingSet());
        $this->assertEquals(2, $f->getTextAntiAliasing());
        $f->setTextAntiAliasing(Ghostscript::ANTIALIASING_NONE);
        $this->assertTrue($f->isTextAntiAliasingSet());
        $this->assertEquals(1, $f->getTextAntiAliasing());
    }

    public function testGraphicsAntiAliasing()
    {
        $f = new Ghostscript();
        $this->assertEquals(0, $f->getGraphicsAntiAliasing());
        $f->setGraphicsAntiAliasing(5);
        $this->assertFalse($f->isGraphicsAntiAliasingSet());
        $this->assertEquals(0, $f->getGraphicsAntiAliasing());
        $this->assertFalse($f->isGraphicsAntiAliasingSet());
        $f->setGraphicsAntiAliasing(Ghostscript::ANTIALIASING_HIGH);
        $this->assertEquals(4, $f->getGraphicsAntiAliasing());
        $this->assertTrue($f->isGraphicsAntiAliasingSet());
        $f->setGraphicsAntiAliasing(Ghostscript::ANTIALIASING_MEDIUM);
        $this->assertEquals(2, $f->getGraphicsAntiAliasing());
        $this->assertTrue($f->isGraphicsAntiAliasingSet());
        $f->setGraphicsAntiAliasing(Ghostscript::ANTIALIASING_LOW);
        $this->assertEquals(2, $f->getGraphicsAntiAliasing());
        $this->assertTrue($f->isGraphicsAntiAliasingSet());
        $f->setGraphicsAntiAliasing(Ghostscript::ANTIALIASING_NONE);
        $this->assertEquals(1, $f->getGraphicsAntiAliasing());
        $this->assertTrue($f->isGraphicsAntiAliasingSet());
    }

    public function testRenderingStrings()
    {
        $f = new Ghostscript();
        $dir = __DIR__ . DIRECTORY_SEPARATOR . 'support' . DIRECTORY_SEPARATOR;
        $filename = $dir . 'test.pdf';
        $path = '"' . Ghostscript::getGsPath() . '"';
        $this->assertEquals('', $f->getRenderString());
        $f->setInputFile($filename);
        $expect = $path
                . ' -dSAFER -dQUIET -dNOPLATFONTS -dNOPAUSE -dBATCH -sOutputFile="'
                . $dir
                . 'output.png" -sDEVICE=pngalpha -r72 "'
                . $filename
                . '"';
        $this->assertEquals($expect, $f->getRenderString());
        $f->setTextAntiAliasing(Ghostscript::ANTIALIASING_HIGH);
        $expect = $path
                . ' -dSAFER -dQUIET -dNOPLATFONTS -dNOPAUSE -dBATCH -sOutputFile="'
                . $dir
                . 'output.png" -sDEVICE=pngalpha -r72 -dTextAlphaBits=4 "'
                . $filename
                . '"';
        $this->assertEquals($expect, $f->getRenderString());
        $f->setGraphicsAntiAliasing(Ghostscript::ANTIALIASING_HIGH);
        $expect = $path
                . ' -dSAFER -dQUIET -dNOPLATFONTS -dNOPAUSE -dBATCH -sOutputFile="'
                . $dir
                . 'output.png" -sDEVICE=pngalpha -r72 -dTextAlphaBits=4 -dGraphicsAlphaBits=4 "'
                . $filename
                . '"';
        $this->assertEquals($expect, $f->getRenderString());
        $f->setTextAntiAliasing(Ghostscript::ANTIALIASING_NONE);
        $expect = $path
                . ' -dSAFER -dQUIET -dNOPLATFONTS -dNOPAUSE -dBATCH -sOutputFile="'
                . $dir
                . 'output.png" -sDEVICE=pngalpha -r72 -dTextAlphaBits=1 -dGraphicsAlphaBits=4 "'
                . $filename
                . '"';
        $this->assertEquals($expect, $f->getRenderString());
        $f->setDevice('jpeg');
        $expect = $path
                . ' -dSAFER -dQUIET -dNOPLATFONTS -dNOPAUSE -dBATCH -sOutputFile="'
                . $dir
                . 'output.jpeg" -sDEVICE=jpeg -dJPEGQ=75 -dQFactor=0.75 -r72 -dTextAlphaBits=1'
                . ' -dGraphicsAlphaBits=4 "'
                . $filename
                . '"';
        $this->assertEquals($expect, $f->getRenderString());
    }

    public function testRendering()
    {
        Ghostscript::setGsPath();
        $f = new Ghostscript();
        $this->assertFalse($f->render());
        $filename = __DIR__ . DIRECTORY_SEPARATOR . 'support' . DIRECTORY_SEPARATOR . 'test.pdf';
        $f->setInputFile($filename);
        $this->assertTrue($f->render());
        unlink(dirname($filename) . DIRECTORY_SEPARATOR . 'output.png');
    }

    public function testSettingPages()
    {
        $f = new Ghostscript();
        $this->assertAttributeEquals(null, 'pageStart', $f);
        $this->assertAttributeEquals(null, 'pageEnd', $f);
        $this->assertEmpty($f->getPageRangeString());

        $f->setPages(2);
        $this->assertAttributeEquals(2, 'pageStart', $f);
        $this->assertAttributeEquals(null, 'pageEnd', $f);
        $this->assertEquals(' -dFirstPage=2 -dLastPage=2', $f->getPageRangeString());

        $f->setPages(3, 4);
        $this->assertAttributeEquals(3, 'pageStart', $f);
        $this->assertAttributeEquals(4, 'pageEnd', $f);
        $this->assertEquals(' -dFirstPage=3 -dLastPage=4', $f->getPageRangeString());
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage No Ghostscript-instance found or running on windows.
     * Please provide Path to the Ghostscript-executable
     */
    public function testSettingDefaultGsPathFails()
    {
        exec('which gs', $output);
        if ($output) {
            $this->markTestSkipped('Can not test due to installed GS');
        }

        $this->setExpectedExceptionFromAnnotation();

        Ghostscript::setGsPath();
    }

    public function testSettingDefaultGsPathWorks()
    {
        exec('which gs', $output);
        if (! $output) {
            $this->markTestSkipped('Can not test due to not installed GS');
        }

        Ghostscript::setGsPath();

        $this->assertAttributeEquals($output[0], 'PATH', Ghostscript::class);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The given file is not executable
     */
    public function testThatSettingPathToNonExecutableFails()
    {
        Ghostscript::setGsPath(__DIR__ . '/_assets/nonExecutable');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage No valid Ghostscript found
     */
    public function testThatSettingPathToNonGsFails()
    {
        Ghostscript::setGsPath(__DIR__ . '/_assets/executableNonGs');
    }

    public function testThatSettingPathToSomethingGsLikeWorks()
    {
        Ghostscript::setGsPath(__DIR__ . '/_assets/executableGs');
        $this->assertAttributeEquals(
            __DIR__ . '/_assets/executableGs',
            'PATH',
            Ghostscript::class
        );
    }

    /** @dataProvider provideRelativePaths */
    public function testThatRelativePathsAreCorrectlyDetected($path, $result)
    {
        $reflection = new \ReflectionClass(Ghostscript::class);
        $method = $reflection->getMethod('isRelative');
        $method->setAccessible(true);

        $this->assertSame($result, $method->invokeArgs(new Ghostscript(), [$path]));
    }

    public function provideRelativePaths()
    {
        return [
            ['/test/foo', false],
            ['a:\foo', false],
            ['bar', true],
            ['\foob', true],
            ['a:/test', true],
        ];
    }

    /** @dataProvider provideOutputFileNames */
    public function testGetOutputFileName($filename, $ending, $expectedFilename)
    {
        $gs = new Ghostscript();

        if ($ending) {
            $device = M::mock(DeviceInterface::class);
            $device->shouldReceive('getFileEnding')->andReturn($ending);
            $gs->setDevice($device);
        }

        $gs->setOutputFile($filename);
        $this->assertEquals($expectedFilename, basename($gs->getOutputFileName()));
    }

    public function provideOutputFileNames()
    {
        return [
            ['test', null, 'test.png'],
            ['test', 'jpeg', 'test.jpeg'],
            ['test.png', null, 'test.png'],
            ['test.abc', null, 'test.abc'],
        ];
    }
}
