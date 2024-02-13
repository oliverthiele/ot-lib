<?php

declare(strict_types=1);

namespace OliverThiele\OtLib\Utility;

/**
 * Copyright notice
 * (c) 2016-2024 Oliver Thiele <mail@oliver-thiele.de>, Web Development Oliver Thiele
 * All rights reserved
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * This copyright notice MUST APPEAR in all copies of the script!
 */

use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class IconUtility
 *
 * @package OliverThiele\OtLib\Utility
 */
class IconUtility
{

    /**
     * Get a svg icon from different directories as string
     *
     * @param string $identifier
     * @param string $size
     * @param string $iconStyle
     * @param string $returnAs
     * @param string $id
     * @param string $additionalClasses
     * @param bool $ariaHidden
     * @param string $ariaLabel
     * @param string $ariaDescription
     * @param string $title
     * @param string $role
     * @return string
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    public static function getIconString(
        string $identifier,
        string $size = '1x',
        string $iconStyle = '',
        string $returnAs = 'inline',
        string $id = '',
        string $additionalClasses = '',
        bool $ariaHidden = false,
        string $ariaLabel = '',
        string $ariaDescription = '',
        string $title = '',
        string $role = '',
    ): string {
        $ariaLabelledBy = '';
        $titleTag = '';
        $descriptionTag = '';
        $titleId = '';
        $descriptionId = '';
        $ariaLabelAndDescription = '';
        $titleAndDescriptionTags = '';

        if ($returnAs === null || $returnAs === '') {
            $returnAs = 'inline';
        }

        if ($additionalClasses !== '') {
            $additionalClasses = ' ' . $additionalClasses;
        }

        if ($ariaHidden === true) {
            $addToSvgTag = ' aria-hidden="true"';
            if(trim($title) !== '') {
                $titleAndDescriptionTags = '<title>' . $title . '</title>';
            }
        } else {
            if ($ariaLabel !== '') {
                $titleId = 'title-' . $id;
                $titleTag = '<title id="' . $titleId . '">' . $ariaLabel . '</title>';
                $ariaLabel = ' aria-label="' . $ariaLabel . '"';
            }

            if ($ariaDescription !== '') {
                $descriptionId = 'description-' . $id;
                $descriptionTag = '<desc id="description-' . $id . '">' . $ariaDescription . '</desc>';
                $ariaDescription = ' aria-description="' . $ariaDescription . '"';
            }

            // If id is set, title tag and desc tag will be used instead of aria-label and aria-description
            if ($id !== '') {
                $ariaLabelledBy = ' aria-labelledby="' . trim($titleId . ' ' . $descriptionId) . '"';
                $titleAndDescriptionTags = $titleTag . $descriptionTag;
                $id = ' id="' . $id . '"';
            } else {
                $ariaLabelAndDescription = $ariaLabel . $ariaDescription;
            }
            $addToSvgTag = $ariaLabelledBy . $ariaLabelAndDescription;
        }

        return match ($returnAs) {
            'inline' => self::getInlineSvgFromFilesystem(
                $identifier,
                $iconStyle,
                $size,
                $id,
                $additionalClasses,
                $addToSvgTag,
                $titleAndDescriptionTags
            ),

            'sprite', 'localstorage', 'localStorage' => '<svg' . $id .
                ' class="ot-localstorage-icon ot-icon-id-' . $identifier . $additionalClasses . '"
                     data-svg-identifier="' . $identifier . '"
                     ' . $addToSvgTag . '>' . $titleAndDescriptionTags .
                '</svg>',

            default => 'Error: returnAs is not defined!',
        };
    }

    /**
     * Nur für inline, sprite macht dies im JS
     *
     * @param string $iconString
     * @param string $title
     * @param string $description
     * @return string
     */
    private static function insertTitleAndDescription(
        string $iconString,
        string $title,
        string $description
    ): string {
        $insertString = '';

        if (trim($title) !== '') {
            $insertString = '<title>' . trim($title) . '</title>';
        }
        if (trim($description) !== '') {
            $insertString = '<desc>' . trim($title) . '</desc>';
        }

        return preg_replace(
            '/\<svg (.*)\>(.*)<\/svg>/mU',
            '<svg ${1}>' . $insertString . '${2}</svg>',
            $iconString
        );
    }

    /**
     * @param string $identifier
     * @param string $iconStyle
     * @param string $size
     * @param string $id
     * @param string $additionalClasses
     * @return string
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    private static function getInlineSvgFromFilesystem(
        string $identifier,
        string $iconStyle,
        string $size,
        string $id = '',
        string $additionalClasses = '',
        string $addToSvgTag = '',
        string $titleAndDescriptionTags = '',
    ): string {
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('ot_siteoliverthiele');

        switch ($size) {
            case 'xs':
                $iconSizeString = ' fa-xs';
                $iconSizeEm = '.75em';
                break;
            case 'sm':
                $iconSizeString = ' fa-sm';
                $iconSizeEm = '.875em';
                break;
            case 'lg':
                $iconSizeString = ' fa-lg';
                $iconSizeEm = '1.33em';
                break;
            case '1x':
                $iconSizeString = ' fa-1x';
                $iconSizeEm = '1em';
                break;
            case '2x':
                $iconSizeString = ' fa-2x';
                $iconSizeEm = '2em';
                break;
            case '3x':
                $iconSizeString = ' fa-3x';
                $iconSizeEm = '3em';
                break;
            case '4x':
                $iconSizeString = ' fa-4x';
                $iconSizeEm = '4em';
                break;
            case '5x':
                $iconSizeString = ' fa-5x';
                $iconSizeEm = '5em';
                break;
            case '6x':
                $iconSizeString = ' fa-6x';
                $iconSizeEm = '6em';
                break;
            case '7x':
                $iconSizeString = ' fa-7x';
                $iconSizeEm = '7em';
                break;
            case '8x':
                $iconSizeString = ' fa-8x';
                $iconSizeEm = '8em';
                break;
            case '9x':
                $iconSizeString = ' fa-9x';
                $iconSizeEm = '9em';
                break;
            case '10x':
                $iconSizeString = ' fa-10x';
                $iconSizeEm = '10em';
                break;
            default:
                $iconSizeString = '';
                $iconSizeEm = '100%';
        }

        if ($additionalClasses !== '') {
            $additionalClasses = ' ' . $additionalClasses;
        }

        if ($iconStyle === '' && isset($extensionConfiguration['fontAwesomeStyle'])) {
            $iconStyle = $extensionConfiguration['fontAwesomeStyle'];
        }

        $svgSubDirectory = self::getSubDirectoryForFontAwesome($iconStyle);

        if (isset($extensionConfiguration['iconPath'])) {
            $iconPath = $extensionConfiguration['iconPath'];

            if (str_starts_with($iconPath, 'EXT:')) {
                $svgDirectory = GeneralUtility::getFileAbsFileName($iconPath);
                if (is_dir($svgDirectory . $svgSubDirectory)) {
                    $imgUrl = $svgDirectory . $svgSubDirectory . $identifier . '.svg';
                    $img = @file_get_contents($imgUrl);

                    // Wenn img === false, dann im brands/ Ordner suchen
                    if ($img === false) {
                        $imgUrl = $svgDirectory . 'brands/' . $identifier . '.svg';
                        $img = @file_get_contents($imgUrl);
                    }

                    if ($img !== false) {
                        $iconString = str_replace(
                            '<svg ',
                            '<svg' . $id . $addToSvgTag . ' class="ot-inline-icon ot-icon-id-' . $identifier . $iconSizeString . $additionalClasses . '" ',
                            $img
                        );
                        return self::insertTitleAndDescriptionTags($iconString, $titleAndDescriptionTags);
                    }
                }
            }
        }
        // SVG not found
        return '<svg class="ot-inline-icon fa-2x" xmlns="http://www.w3.org/2000/svg" style="fill: red;" viewBox="0 0 576 512">
                    <title>SVG with Identifier ' . $identifier . 'not found.</title>
                    <path d="M569.517 440.013C587.975 472.007 564.806 512 527.94 512H48.054c-36.937 0-59.999-40.055-41.577-71.987L246.423 23.985c18.467-32.009 64.72-31.951 83.154 0l239.94 416.028zM288 354c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z"/>
                </svg>';
    }

    /**
     * Get the subdirectory for FontAwesome icon styles
     *
     * @param string $iconStyle l|r|s|t|b|d|s-l|s-r|s-s|s-t
     * @return string
     */
    private static function getSubDirectoryForFontAwesome(string $iconStyle): string
    {
        $directory = match ($iconStyle) {
            'l' => 'light/',
            'r' => 'regular/',
            't' => 'thin/',
            'b' => 'brands/',
            'd' => 'duotone/',
            's-l' => 'sharp-light/',
            's-r' => 'sharp-regular/',
            's-s' => 'sharp-solid/',
            's-t' => 'sharp-thin/',
            default => 'solid/',
        };

        return $directory;
    }

    /**
     * @param string $iconString
     * @param string $titleAndDescriptionTags
     * @return string
     */
    private static function insertTitleAndDescriptionTags(string $iconString, string $titleAndDescriptionTags)
    {
        return preg_replace(
            '/\<svg (.*)\>(.*)<\/svg>/mU',
            '<svg ${1}>' . $titleAndDescriptionTags . '${2}</svg>',
            $iconString
        );
    }

}
