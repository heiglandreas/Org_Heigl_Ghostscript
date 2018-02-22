<?php
/**
 * Copyright (c) Andreas Heigl<andreas@heigl.org>
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

namespace Org_Heigl\GhostscriptTest\Device;

use Org_Heigl\Ghostscript\Device\Png;
use PHPUnit\Framework\TestCase;

/**
 * This class tests the Org_Heigl_Ghostscript-Class
 *
 * @author    Andreas Heigl <andreas@heigl.org>
 * @copyright 2008 Andreas Heigl<andreas@heigl.org>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT-License
 */
class PngTest extends TestCase
{
    public function testCreationOfPngClass()
    {
        $f = new Png();
        $this->assertTrue($f instanceof Png);
        $this->assertEquals('pngalpha', $f->getDevice());
    }

    public function testSettingDifferentDevice()
    {
        $f = new Png();
        $this->assertEquals('pngalpha', $f->getDevice());
        $f->setDevice('PNG16m');
        $this->assertEquals('png16m', $f->getDevice());
        $f->setDevice('jpeg');
        $this->assertEquals('pngalpha', $f->getDevice());
    }

    public function testSettingBackgroundColor()
    {
        $f = new Png();
        $this->assertNull($f->getBackgroundColor());
        $f->setBackgroundColor('abcd34');
        $this->assertEquals('abcd34', $f->getBackgroundColor());
        $f->setBackgroundColor('orange');
        $this->assertNull($f->getBackgroundColor());
    }

    public function testgettingParamString()
    {
        $f = new Png();
        $this->assertEquals(' -sDEVICE=pngalpha', $f->getParameterString());
        $f->setDevice('png16');
        $this->assertEquals(' -sDEVICE=png16', $f->getParameterString());
        $f->setBackgroundColor('abcd45');
        $this->assertEquals(' -sDEVICE=png16', $f->getParameterString());
        $f->setDevice('png');
        $this->assertEquals(' -sDEVICE=pngalpha -dBackgroundColor=16#abcd45', $f->getParameterString());
    }


    public function testFileEnding()
    {
        $f = new Png();
        $this->assertEquals('png', $f->getFileEnding());
    }
}
