# Org_Heigl\Ghostscript

[![Build Status](https://travis-ci.org/heiglandreas/Org_Heigl_Ghostscript.svg?branch=master)](https://travis-ci.org/heiglandreas/Org_Heigl_Ghostscript)
[![Coverage Status](https://coveralls.io/repos/github/heiglandreas/Org_Heigl_Ghostscript/badge.svg?branch=master)](https://coveralls.io/github/heiglandreas/Org_Heigl_Ghostscript?branch=master)

A PHP-wrapper to the Ghostscript-CLI

## Installation:                                                              |

This package is best installed using [composer](https://getcomposer.org).

    composer require org_heigl/ghostscript

 ## Usage:                                                              |
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

