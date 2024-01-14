# EXT:ot_lib

This library provides ViewHelper and DataProcessors to my various TYPO3 sitepackages.

## Requirements

* TYPO3 v12.4 LTS
* Additional database fields in the sitepackage for some of the DataProcessors / ViewHelpers.


## ViewHelpers

### AgeViewHelper

Mit diesem ViewHelper kann ein Datum automatisch in Texte umgewandelt werden.

todo: Lokalisierung muss noch eingebaut werden

### GravatarViewHelper

Mit dem ViewHelper kann mit einer E-Mail der Link zu dem Gravatar-Bilder erstellt werden.

todo: Ein Gravatar-Bild Proxy wäre aus Datenschutzsicht besser.

### JsonViewHelper

Enkodiert oder dekodiert JSON

### DivisionViewHelper

Dividiert Dividend / Divisor in einem Try/Catch Block

### VideoViewHelper

Holt sich das Poster-Bild zu einem Video

* Video: fileadmin/user_uploads/Videos/myVideo.mp4
* Poster image: fileadmin/user_uploads/Videos/myVideo.mp4.jpg

### HeaderViewHelper

Wenn der CKeditor für Überschriften aktiviert wird, um mehrzeilige Überschriften mit `<b>`
als zusätzliche Formatierung zu nutzen, dann kann der HTML-Code vom RTE bereinigt werden.

### ParamsViewHelper

Extrahiert per Default den Titel aus einem TypoLink-String, kann aber auch andere Parameter extrahieren.

### Nl2ArrayViewHelper

Returns all rows of a text as array of lines


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
