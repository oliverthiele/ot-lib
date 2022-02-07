<?php

declare(strict_types=1);

namespace OliverThiele\OtLib\DataProcessing;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * Class for data processing for the content elements with images
 */
class ImageConfigDataProcessor implements DataProcessorInterface
{
    /**
     * Process data for the content elements with images
     *
     * @param  ContentObjectRenderer  $cObj  The data of the content element or page
     * @param  array<mixed>  $contentObjectConfiguration  The configuration of Content Object
     * @param  array<mixed>  $processorConfiguration  The configuration of this processor
     * @param  array<mixed>  $processedData  Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array<mixed> the processed data as key/value store
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        // Bootstrap 4 + 5
        $xs = (string)$processedData['data']['crop_variant_xs'];
        $sm = (string)$processedData['data']['crop_variant_sm'];
        $md = (string)$processedData['data']['crop_variant_md'];
        $lg = (string)$processedData['data']['crop_variant_lg'];
        $xl = (string)$processedData['data']['crop_variant_xl'];

        // Bootstrap 5
        $xxl = '';
        if (isset($processedData['data']['crop_variant_xxl'])) {
            $xxl = (string)$processedData['data']['crop_variant_xxl'];
        }

        if ($sm === '') {
            $sm = $xs;
        }
        if ($md === '') {
            $md = $sm;
        }
        if ($lg === '') {
            $lg = $md;
        }
        if ($xl === '') {
            $xl = $lg;
        }
        if ($xxl === '') {
            $xxl = $xl;
        }

        // no crop
        if ($sm === 'org') {
            $sm = '';
        }
        if ($md === 'org') {
            $md = '';
        }
        if ($lg === 'org') {
            $lg = '';
        }
        if ($xl === 'org') {
            $xl = '';
        }
        if ($xxl === 'org') {
            $xxl = '';
        }

        $cropVariants = [
            'xs' => $xs,
            'sm' => $sm,
            'md' => $md,
            'lg' => $lg,
            'xl' => $xl,
            'xxl' => $xxl,
        ];

        $equalCropVariants = false;
        if (end($cropVariants) !== false && count(array_flip($cropVariants)) === 1) {
            $equalCropVariants = true;
        }
        $additionalProcessedData = [
            'imageConfig' => [
                'equalCropVariants' => $equalCropVariants,
                'cropVariants' => $cropVariants,
                'imagecols' => $processedData['data']['imagecols']
            ]
        ];

        return array_merge_recursive($processedData, $additionalProcessedData);
    }
}
