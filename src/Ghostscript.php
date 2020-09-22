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
 * @copyright Andreas Heigl<andreas@heigl.org>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT-License
 */

namespace Org_Heigl\Ghostscript;

use Org_Heigl\Ghostscript\Device\DeviceInterface;

/**
 * This class contains a wrapper around the Ghostscript-Application.
 *
 * This needs the Ghostscript application to be installed on the server. If the
 * gs-executable is not available the class will not be able to execute anything
 *
 * A working example might look like the following code:
 * <code>
 *
 * // First we describe the output-format
 * $device = new Org_Heigl_Ghostscript_Device_Jpeg ();
 *
 * // Set the JPEG-Quality to 100
 * $device -> setQuality ( 100 );
 *
 * // Next we Create the ghostscript-Wrapper
 * $gs = new Org_Heigl_Ghostscript ();
 *
 * // Set the device
 * $gs -> setDevice ( $device )
 * // Set the input file
 *     -> setInputFile ( 'path/to/my/ps/or/pdf/file' )
 * // Set the output file that will be created in the same directory as the input
 *     -> setOutputFile ( 'output' )
 * // Set the resolution to 96 pixel per inch
 *     -> setResolution ( 96 )
 * // Set Text-antialiasing to the highest level
 *     -> setTextAntiAliasing ( Org_Heigl_Ghostscript::ANTIALIASING_HIGH );
 *
 * // convert the input file to an image
 * if ( true === $gs -> render () ) {
 *     echo 'success';
 * } else {
 *     echo 'some error occured';
 * }
 * </code>
 *
 * Alternatively the example could read as follows
 * <code>
 *
 * // Create the ghostscript-Wrapper
 * $gs = new Org_Heigl_Ghostscript ();
 *
 * // Set the device
 * $gs -> setDevice ( 'jpeg' )
 * // Set the input file
 *     -> setInputFile ( 'path/to/my/ps/or/pdf/file' )
 * // Set the output file that will be created in the same directory as the input
 *     -> setOutputFile ( 'output' )
 * // Set the resolution to 96 pixel per inch
 *     -> setResolution ( 96 )
 * // Set Text-antialiasing to the highest level
 *     -> setTextAntiAliasing ( Org_Heigl_Ghostscript::ANTIALIASING_HIGH );
 *
 * // Set the jpeg-quality to 100
 * $gs -> getDevice () -> setQuality ( 100 );
 *
 * // convert the input file to an image
 * if ( true === $gs -> render () ) {
 *     echo 'success';
 * } else {
 *     echo 'some error occured';
 * }
 * </code>
 *
 * @category  Org_Heigl
 * @package   Org_Heigl_Ghostscript
 * @author    Andreas Heigl <andreas@heigl.org>
 * @copyright 2008 Andreas Heigl<andreas@heigl.org>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT-License
 * @version   SVN: $Revision$
 * @since     03.06.2009
 */
class Ghostscript
{
    /**
     * No Anti-Aliasing
     *
     * @var int
     */
    const ANTIALIASING_NONE   = 1;

    /**
     * Low Anti-Aliasing
     *
     * @var int
     */
    const ANTIALIASING_LOW    = 2;

    /**
     * Medium Anti-Aliasing
     *
     * @var int
     * @deprecated As there is no "Medium" Anti-Aliasing. Only None, low and
     * high
     */
    const ANTIALIASING_MEDIUM = 2;

    /**
     * High Anti-Aliasing
     *
     * @var int
     */
    const ANTIALIASING_HIGH   = 4;

    /**
     * Store the resolution
     *
     * @var string $_resolution
     */
    protected $resolution = 72;

    /**
     * This property stores the file to process.
     *
     * @var SplFileInfo $_infile
     */
    protected $infile = null;

    /**
     * This property stores the output-filename.
     *
     * This is NOT necessarily the filename that can be used for retrieving the
     * file as Ghostscript can use this name for more than one file if a
     * placeholder is defined.
     *
     * @var string $_outfile
     */
    protected $outfile = 'output';

    /**
     * Stores the anti aliasing level
     *
     * @var int $_graphicsAntiAliasing
     */
    protected $graphicsAntiAliasing = 0;

    /**
     * Stores the anti aliasing level
     *
     * @var int $_textAntiAliasing
     */
    protected $textAntiAliasing = 0;

    /**
     * Store whether to use CIE for color conversion or not
     *
     * @var boolean $_useCie
     */
    protected $useCie = false;

    /**
     * Store any default input-profiles
     *
     * @var array $_defaultProfile
     */
    protected $defaultProfile = [];

    /**
     * Store the deviceProfile to use for oputput
     *
     * @var string|null $_deviceProfile
     */
    protected $deviceProfile = null;

    /**
     * Which box shall be used for rendering?
     *
     * @var string|null $_useBox
     */
    protected $useBox = null;

    /**
     * On which page shall we start rendering?
     *
     * If NULL this will be ignored
     *
     * @var int $_pageStart
     */
    protected $pageStart = null;

    /**
     * On which page shall we stop rendering?
     *
     * If NULL, this will be ignored
     *
     * @var int $_pageEnd
     */
    protected $pageEnd = null;

    /**
     * Which MIME-Types are supported
     *
     * @var array $supportedMimeTypes
     */
    private static $supportedMimeTypes = [
        'application/postscript',
        'application/eps',
        'application/pdf',
        'application/ps',
    ];

    /**
     * This property contains the path to the Ghostscript-Application
     *
     * This is set when the class is first loaded
     *
     * @var string PATH
     */
    private static $PATH = null;

    /**
     * Create a new Instance of the Ghostscript wrapper.
     *
     * The new Instance will use a jpeg-device as default
     *
     * @return void
     */
    public function __construct()
    {
        $this->setDevice('png');
    }

    /**
     * Set the path to the gs-executable and return it.
     *
     * This method will be called on load of the class and needs not to be
     * called during normal operation.
     *
     * If you have Ghostscript installed in a non-standard-location that can not
     * be found via the 'which gs' command, you have to set the path manualy
     *
     * @param string|null $path The path to set
     *
     * @return string
     */
    public static function setGsPath($path = null)
    {
        if (null === $path) {
            exec('which gs', $output);
            if (! $output) {
                throw new \UnexpectedValueException(
                    'No Ghostscript-instance found or running on windows. ' .
                    'Please provide Path to the Ghostscript-executable'
                );
            }
            $path = $output[0];
        }

        if (! $path) {
            throw new \UnexpectedValueException('No path found');
        }

        if (! is_executable($path)) {
            throw new \InvalidArgumentException('The given file is not executable');
        }

        @exec('"' . $path . '" -v', $result);
        $content = implode("\n", $result);
        if (false === stripos($content, 'ghostscript')) {
            throw new \InvalidArgumentException('No valid Ghostscript found');
        }

        self::$PATH = $path;

        return self::$PATH;
    }

    /**
     * Get the currently set path for the ghostscript-app
     *
     * @return string
     */
    public static function getGsPath()
    {
        if (! self::$PATH) {
            throw new \InvalidArgumentException('No GS-Path set');
        }

        return self::$PATH;
    }


    /**
     * Set the file that shall be processes
     *
     * This should be a PostScript (ps), Enhanced Postscript (eps) or
     * PortableDocumentformat (pdf) File.
     *
     * @param string|SplFileInfo $file The File to use as input.
     *
     * @throws InvalidArgumentException when the provided file is not supported
     * @return self
     */
    public function setInputFile($file)
    {
        if (! $file instanceof \SplFileInfo) {
            $file = new \SplFileInfo((string) $file);
        }
        if (extension_loaded('fileinfo') && file_exists($file)) {
            $finfo = new \finfo();
            $mime = $finfo->file($file->getPathName(), FILEINFO_MIME);
            $mime = explode(';', $mime);
            if (! in_array($mime[0], self::$supportedMimeTypes)) {
                throw new \InvalidArgumentException('The provided file seems not to be of a supported MIME-Type');
            }
        }
        $this->infile = $file;

        return $this;
    }

    /**
     * Get the file that shall be processed
     *
     * @return SplFileInfo
     */
    public function getInputFile()
    {
        return $this->infile;
    }

    /**
     * Set the name of the output file(s)
     *
     * This name does not need a file-extension as that is set from the output
     * format.
     *
     * The name can contain a placeholder like '%d' or '%02d'. This will be
     * replaced by the pagenumber of the processed page. For more information
     * on the format see the PHP documentation for sprintf
     *
     * @param string $name The filename
     *
     * @return Ghostscript
     */
    public function setOutputFile($name = 'output')
    {
        if ($this->isRelative($name)) {
            $name = $this->getBasePath() . DIRECTORY_SEPARATOR . $name;
        }

        $this->outfile = $name;
        
        return $this;
    }

    /**
     * Get the output filename.
     *
     * This is NOT the name the file can be retrieved with as Ghostscript can
     * modify the filename, but the returned string containes the directory the
     * file(s) reside in.
     *
     * @return string
     */
    public function getOutputFile()
    {
        if ($this->isRelative($this->outfile)) {
            return $this->getBasePath() . DIRECTORY_SEPARATOR . $this->outfile;
        }

        return $this->outfile;
    }

    /**
     * Get the basepath of the execution.
     *
     * Thisis set to the directory containing <var>$_infile</var>.
     *
     * If <var>$_infile</var> is not set, it is set to the systems default
     * tmp-directory.
     *
     * @return string
     */
    public function getBasePath()
    {
        if (null !== $this->infile) {
            return dirname($this->infile);
        }
        return sys_get_temp_dir();
    }

    /**
     * Render the input file via Ghostscript
     *
     * @return bool
     */
    public function render()
    {
        $renderString = $this->getRenderString();

        // We can't render anything without a render string
        if ('' == $renderString) {
            return false;
        }

        exec($renderString, $returnArray, $returnValue);

        if (0 !== $returnValue) {
            return false;
        }

        return true;
    }

    /**
     * Get the command-line that can be executed via exec
     *
     * @return string
     */
    public function getRenderString()
    {
        if (null === $this->getInputFile()) {
            return '';
        }
        $string  = '"' . self::getGsPath() . '"';
        $string .= ' -dSAFER -dQUIET -dNOPLATFONTS -dNOPAUSE -dBATCH';
        $string .= ' -sOutputFile="' . $this->getOutputFileName() . '"';
        $string .= $this->getDevice()->getParameterString();
        $string .= ' -r' . $this->getResolution();
        if ($this->isTextAntiAliasingSet()) {
            $string .= ' -dTextAlphaBits=' . $this->getTextAntiAliasing();
        }
        if ($this->isGraphicsAntiAliasingSet()) {
            $string .= ' -dGraphicsAlphaBits=' . $this->getGraphicsAntiAliasing();
        }


        if (true === $this->useCie()) {
            $string .= ' -dUseCIEColor';
        }

        // Set the Rendered Box.
        $box = $this->getBox();
        if (null !== $box) {
            $string .= ' -dUse' . ucfirst($box) . 'Box';
        }

        // Set files for ColorManagement.
        // As of GS 8.71 there should be a different way to do that.
        if ($this->defaultProfile) {
            foreach ($this->defaultProfile as $profile) {
                $string .= ' "' . $profile . '"';
            }
        }
        $deviceProfile = $this->getDeviceProfile();
        if (false !== $deviceProfile) {
            $string .= ' "' . $deviceProfile . '"';
        }

        $string .= $this->getPageRangeString();

        $string .= ' "' . $this->getInputFile() . '"';
        return $string;
    }

    public function getPageRangeString()
    {
        if (null === $this->pageStart) {
            return '';
        }

        $string = ' -dFirstPage=%d -dLastPage=%d';

        $pageStart = $this->pageStart;
        $pageEnd = $this->pageEnd;
        if (null === $this->pageEnd) {
            $pageEnd = $this->pageStart;
        }

        return sprintf($string, $pageStart, $pageEnd);
    }

    /**
     * Check whether Anti ALiasing for graphics is set
     *
     * @return boolean
     */
    public function isGraphicsAntiAliasingSet()
    {
        if (0 < $this->graphicsAntiAliasing) {
            return true;
        }

        return false;
    }

    /**
     * Set graphics-AntiAliasing
     *
     * @param int $level The AntiaAliasing level to set.
     *
     * @return self
     */
    public function setGraphicsAntiAliasing($level)
    {
        if ($level === 0 || $level === 1 || $level === 2 || $level === 4) {
            $this->graphicsAntiAliasing = $level;
        }

        return $this;
    }



    /**
     * Get the text-AntiAliasing level
     *
     * @return int
     */
    public function getGraphicsAntiAliasing()
    {
        return $this->graphicsAntiAliasing;
    }


    /**
     * Check whether Anti ALiasing for text is set
     *
     * @return boolean
     */
    public function isTextAntiAliasingSet()
    {
        if (0 < $this->textAntiAliasing) {
            return true;
        }

        return false;
    }

    /**
     * Set text-AntiAliasing
     *
     * @param int $level The AntiaAliasing level to set.
     *
     * @return self
     */
    public function setTextAntiAliasing($level)
    {
        if ($level === 0 || $level === 1 || $level === 2 || $level === 4) {
            $this->textAntiAliasing = $level;
        }

        return $this;
    }

    /**
     * Get the text-AntiAliasing level
     *
     * @return int
     */
    public function getTextAntiAliasing()
    {
        return $this->textAntiAliasing;
    }

    /**
     * Set the resolution for the rendering
     *
     * @param int The horizontal resolution to set
     * @param int The vertical resolution to set
     *
     * @return self
     */
    public function setResolution($horizontal, $vertical = null)
    {
        if (null !== $vertical) {
            $this->resolution = $horizontal . 'x' . $vertical;
        } else {
            $this->resolution = $horizontal;
        }

        return $this;
    }

    /**
     * Get the resolution
     *
     * @return string
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * Set the output-device
     *
     * @param DeviceInterface|string $device
     *
     * @return self
     */
    public function setDevice($device)
    {
        if (! $device instanceof DeviceInterface) {
            $classname = 'Org_Heigl\\Ghostscript\\Device\\' . ucfirst(strtolower($device));
            $device = new $classname();
        }
        $this->device = $device;

        return $this;
    }

    /**
     * Get the device-object
     *
     * @return DeviceInterface
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * Set whether to use the CIE-Map for conversion between CMYK and RGB or not
     *
     * @param boolean $useCIE
     *
     * @return self
     */
    public function setUseCie($useCie = true)
    {
        $this->useCie = (bool) $useCie;

        return $this;
    }

    /**
     * Shall we use the CIE map for color-conversions?
     *
     * @return boolean
     */
    public function useCie()
    {
        return (bool) $this->useCie;
    }

    /**
     * Which Box shall be used to generate the output from.
     *
     * This can be one of
     *  - crop
     *  - media
     *
     *  @param string $box The box to use
     *
     *  @return self
     */
    public function useBox($box)
    {
        $box = strtolower($box);
        switch ($box) {
            case 'crop':
            case 'media':
            case 'trim':
                $this->useBox = $box;
                break;
            default:
                $this->useBox = null;
                break;
        }

        return $this;
    }

    /**
     * Get the name of the box to be used for rendering
     *
     * This returns either 'crop' or 'media' if one of these boxes shall be
     * rendered or NULL if the switch shall not be set.
     *
     * @return string|null
     */
    public function getBox()
    {
        return $this->useBox;
    }

    /**
     * Add the given Profile for Color-Management as Input-Profile.
     *
     * The Profile will be added as CSA-File to perform the translation of
     * Colors from the Input-File to the Internal ProcessColosSpace.
     *
     * The CSA-File can be created via the OpenSource-Tool icc2ps from the
     * littleCMS-Package available at http://www.littlecms.org
     *
     * The CSA-File can be generated via the following command from any
     * icc-file:
     * <code>
     * icc2ps -i <input.icc> > output.csa
     * </code>
     * This gerneated CSA-File has to be adapted according to the following
     * example:
     * <code>
     * currentglobal true setglobal
     * /DefaultCMYK
     * [ /CIEBasedDEFG
     * <<
     *  ...
     *  ...
     * >>
     * ] /ColorSpace defineresource pop
     * setglobal
     * </code>
     * where the Part in the brackets is the part that is generated from the
     * icc2ps-tool.
     *
     * For more Information on Color-Conversion and Color-Management refer to
     * the Homepage of ghostscript, the ICC or have a look at a Search-Engine.
     *
     * @param string $profile The Name of the CSA-Profile to use or the complete
     * path to an appropriate CSA-File.
     * @param string $space   The Color-Space to set the profile for. This can
     * be one of 'rgb', 'cmyk' or 'gray'. This parameter is currently not
     * supported!
     *
     * @see http://www.littlecms.org
     * @see http://www.ghostscript.com
     * @return self
     */
    public function setDefaultProfile($profile, $space = null)
    {
        $space = strtolower($space);
        if (! in_array($space, [ 'cmyk', 'rgb', 'gray' ])) {
            $space = 'cmyk';
        }
        if (file_exists($profile)) {
            $this->defaultProfile[$space] = $profile;
        }

        return $this;
    }

    /**
     * Get the default Input-Profile
     *
     * @return string|false
     */
    public function getDefaultProfile($space = 'cmyk')
    {
        if (isset($this->defaultProfile[$space])) {
            return $this->defautProfile[$space];
        }

        return false;
    }

    /**
     * Add the given Profile for Color-Management as Device-Output-Profile.
     *
     * The Profile will be added as CRD-File to perform the translation of
     * Colors from the Internal ProcessColorSpace to the Output-File.
     *
     * The CRD-File can be created via the OpenSource-Tool icc2ps from the
     * littleCMS-Package available at http://www.littlecms.org
     *
     * The CRD-File can be generated via the following command from any
     * icc-file:
     * <code>
     * icc2ps -o <input.icc> > output.crd
     * </code>
     * This gerneated CRD-File has to be adapted by appeding the following
     * line to it:
     * <code>
     * /Current /ColorRendering findresource setcolorrendering
     * </code>
     *
     * For more Information on Color-Conversion and Color-Management refer to
     * the Homepage of ghostscript, the ICC or have a look at a Search-Engine.
     *
     * @param string $profile The Name of the CRD-Profile to use or the complete
     * path to an appropriate CRD-File.
     *
     * @see http://www.littlecms.org
     * @see http://www.ghostscript.com
     * @return self
     */
    public function setDeviceProfile($profile)
    {
        if (file_exists($profile)) {
            $this->deviceProfile = $profile;
        }

        return $this;
    }

    /**
     * Get the currently set device-Profile
     *
     * @return string|false
     */
    public function getDeviceProfile()
    {
        if (null === $this->deviceProfile) {
            return false;
        }

        return $this->deviceProfile;
    }

    /**
     * Set the page to start rendering
     *
     * @param int $page
     *
     * @return self
     */
    public function setPageStart($page)
    {
        if (null !== $page) {
            $page = (int) $page;
        }
        $this->pageStart = $page;

        return $this;
    }

    /**
     * Set the page to stop rendering
     *
     * @param int $page
     *
     * @return self
     */
    public function setPageEnd($page)
    {
        if (null !== $page) {
            $page = (int) $page;
        }
        $this->pageEnd = $page;

        return $this;
    }

    /**
     * Set a page-Range
     *
     * @param $startPage
     * @param $endPage
     *
     * @return self
     */
    public function setPages($startPage, $endPage = null)
    {
        $this->pageStart = (int) $startPage;

        if (null !== $endPage) {
            $this->pageEnd = (int) $endPage;
        }

        return $this;
    }

    public function getOutputFileName()
    {
        $basename = $this->getOutputFile();
        $lastDot = strrpos(basename($basename), '.');
        if (false === $lastDot) {
            return $basename . '.' . $this->getDevice()->getFileEnding();
        }

        return $basename;
    }

    private function isRelative($path)
    {
        if (0 === strpos($path, DIRECTORY_SEPARATOR)) {
            return false;
        }

        if (1 === strpos($path, ':\\') && preg_match('/^[A-Za-z]/', $path)) {
            return false;
        }

        return true;
    }
}

try {
    Ghostscript::setGsPath();
} catch (\UnexpectedValueException $e) {
}
