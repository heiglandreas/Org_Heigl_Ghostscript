# Basic usage

## Convert a PDF- or Postscript-File to a JPEG

This is the basic way of creating a JPEG from a PDF-File

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