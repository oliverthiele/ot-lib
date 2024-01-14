<?php

declare(strict_types=1);

namespace OliverThiele\OtLib\ViewHelpers\Video;

/**
 * Copyright notice
 * (c) 2016-2024 Oliver Thiele <mailYYYY@oliver-thiele.de>, Web Development Oliver Thiele
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

use Closure;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

/**
 * Class DivisionViewHelper
 * @package TYPO3\CMS\Fluid\ViewHelpers\Format
 */
class PosterViewHelper extends AbstractViewHelper
{
    use CompileWithContentArgumentAndRenderStatic;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Initialize arguments.
     *
     * @throws \TYPO3Fluid\Fluid\Core\ViewHelper\Exception
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('videoPath', 'string', 'Path to the video, e.g. /fileadmin/Videos/MyVideo.mp4');
        $this->registerArgument('fallback', 'string', 'Path to fallback image, e.g. /fileadmin/Videos/Fallback.jpg');
    }

    /**
     * This ViewHelper will check for an image with the same filename, but with the file extension jpg instead of mp4
     * to get the poster image for a video
     *
     * Example:
     * Video: fileadmin/user_uploads/Videos/myVideo.mp4
     * Poster image: fileadmin/user_uploads/Videos/myVideo.mp4.jpg
     *
     * Usage:
     * <f:alias map="{poster: '{otl:video.poster(
     *     videoPath: \'{file.originalFile.publicUrl}\'
     *     fallback: \'/fileadmin/path/fallbackImage.jpg\'
     * )}'}" >
     * <f:if condition="{poster}">
     *     <f:variable name="posterString">poster="{f:uri.image(src: '{poster}')}"</f:variable>
     * </f:if>
     * <video id="video-ce-{data.uid}" src="{file.originalFile.publicUrl}" {posterString -> f:format.raw()} ...>
     *     ...
     * </video>
     * </f:alias>
     *
     * Returns the path to the poster/fallback image or if no image file is found "false"
     *
     * @param array $arguments
     * @param Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string|false
     */
    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): bool|string {
        $videoPath = $_ENV['TYPO3_PATH_WEB'] . $arguments['videoPath'];
        $fallback = $arguments['fallback'];

        if (is_file($videoPath)) {
            $imagePath = $_ENV['TYPO3_PATH_WEB'] . $arguments['videoPath'] . '.jpg';
            if (is_file($imagePath)) {
                return str_replace($_ENV['TYPO3_PATH_WEB'], '', $imagePath);
            }
        }

        if ($_ENV['TYPO3_PATH_WEB'] . $fallback) {
            return $fallback;
        }

        // todo: Direct return with poster"..." (but will don't work with webp extension!)

        return false;
    }
}
