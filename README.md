# Org_Heigl\Ghostscript

[![CI](https://github.com/heiglandreas/Org_Heigl_Ghostscript/actions/workflows/ci.yml/badge.svg)](https://github.com/heiglandreas/Org_Heigl_Ghostscript/actions/workflows/ci.yml)
[![Coverage Status](https://coveralls.io/repos/github/heiglandreas/Org_Heigl_Ghostscript/badge.svg?branch=master)](https://coveralls.io/github/heiglandreas/Org_Heigl_Ghostscript?branch=master)
[![Latest Stable Version](http://poser.pugx.org/org_heigl/ghostscript/v)](https://packagist.org/packages/org_heigl/ghostscript)
[![Total Downloads](http://poser.pugx.org/org_heigl/ghostscript/downloads)](https://packagist.org/packages/org_heigl/ghostscript)
[![Latest Unstable Version](http://poser.pugx.org/org_heigl/ghostscript/v/unstable)](https://packagist.org/packages/org_heigl/ghostscript)
[![License](http://poser.pugx.org/org_heigl/ghostscript/license)](https://packagist.org/packages/org_heigl/ghostscript)
[![PHP Version Require](http://poser.pugx.org/org_heigl/ghostscript/require/php)](https://packagist.org/packages/org_heigl/ghostscript)

A PHP-wrapper to the Ghostscript-CLI

The main reason to create this library was to create images from PDF- or
Postscript-files. As those Files can be either in RGB- or in CMYK-Colorspace the
library tries to handle colors so that the original file and the image are close
in color-impression. This is especially important as f.e. PNG is RGB-only while
JPEG can be either in RGB or CMYK.

Multipaged PDF-Files will create multiple images! To not overwrite your image-files
the filename will be passed to sprintf to create a filename containing the
number of the page. The one and only parameter will be the number of the page.

## Why another Ghostscript-Wrapper?

There are currently 2 other wrappers around that both didn't meet the requirements
I had:

 * [gravitymedia/ghostscript](https://packagist.org/packages/gravitymedia/ghostscript)
 * [alchemy/ghostscript](https://packagist.org/packages/alchemy/ghostscript)

While the gravitymedia library concentrates more on creating PDFs from input-media
the alchemy library is not easily extendable and doesn't handle the parameters to
finetune Ghostscript the way I needed them.

So this library tries to handle most of the important switches to ghostscript and
also allows you to extend the library by implementing your own driver using the
```DriverInterface```. More information on that will be shown in the
[Documentation](https://heiglandreas.github.io/Org_Heigl_Ghostscript)

## Installation

This package is best installed using [composer](https://getcomposer.org).

    composer require org_heigl/ghostscript

## Documentation

You can find the documentation for the library at https://heiglandreas.github.io/Org_Heigl_Ghostscript

## Usage

```
<?php
use Org_Heigl\Ghostscript\Ghostscript;

// Create the Ghostscript-Wrapper
$gs = new Ghostscript ();

// Set the output-device
$gs->setDevice('jpeg')
// Set the input file
   ->setInputFile('path/to/my/ps/or/pdf/file')
// Set the output file that will be created in the same directory as the input
   ->setOutputFile('output')
// Set the resolution to 96 pixel per inch
   ->setResolution(96)
// Set Text-antialiasing to the highest level
   ->setTextAntiAliasing(Ghostscript::ANTIALIASING_HIGH);
// Set the jpeg-quality to 100 (This is device-dependent!)
   ->getDevice()->setQuality(100);
// convert the input file to an image
if (true === $gs->render()) {
    echo 'success';
} else {
    echo 'some error occured';
}
```

