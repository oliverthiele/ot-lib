# EXT:ot_lib

This library provides ViewHelper and DataProcessors to my various TYPO3 sitepackages.

## Requirements

* TYPO3 v11.5 LTS
* Additional database fields in the sitepackage

## Data processors

### ImageConfigDataProcessor

The ImageConfigDataProcessor provides additional information about the images in a
TYPO3 content element.

1. The cropVariants are returned with inheritance of the setting to the larger screen sizes.
2. If all crop variants are equal for an image, `imageConfig['equalCropVariants']` is set to **true**.
   If the value is set to **true**, an img tag with srcset should be used instead of a picture tag.
   In addition, the [Bootstrap CSS](https://getbootstrap.com/docs/5.1/helpers/ratio/#aspect-ratios) classes for 
   aspect ratio (e.g. `ratio ratio-16x9`) can also be used.

#### Requirements for the TYPO3 DB:

```mysql
CREATE TABLE tt_content
(
	crop_variant_xs   varchar(10)   default '' not null,
	crop_variant_sm   varchar(10)   default '' not null,
	crop_variant_md   varchar(10)   default '' not null,
	crop_variant_lg   varchar(10)   default '' not null,
	crop_variant_xl   varchar(10)   default '' not null,
	crop_variant_xxl  varchar(10)   default '' not null,
);
```

## Ideas

* Try to get the position of the content element / image.
  Lazy loading is not recommended if the image is output at the top of the web page. 
