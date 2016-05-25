# Org_Heigl\Ghostscript

A wrapper to the Ghostscript-CLI

The main reason to create this library was to create images from PDF- or
Postscript-files. As those Files can be either in RGB- or in CMYK-Colorspace the
library tries to handle colors so that the original file and the image are close
in color-impression. This is especially important as f.e. PNG is RGB-only while
JPEG can be either in RGB or CMYK.

Multipaged PDF-Files will create multiple images! To not overwrite your image-files
the filename will be passed to sprintf to create a filename containing the
number of the page. The one and only parameter will be the number of the page.

## Installation

This library is best installed using [composer](https://getcomposer.org)

## Further Documentation

 * [API (DocBlock)](api/)
 * [Code-Coverage](https://coveralls.io/github/heiglandreas/Org_Heigl_Ghostscript)