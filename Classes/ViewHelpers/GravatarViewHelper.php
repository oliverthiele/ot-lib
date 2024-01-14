<?php

declare(strict_types=1);

namespace OliverThiele\OtLib\ViewHelpers;

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

use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

/**
 * Gravatar ViewHelper
 *
 * @see http://www.gravatar.com/
 * @package OliverThiele\OtBootstrap5\ViewHelpers
 */
class GravatarViewHelper extends AbstractViewHelper
{

    use CompileWithContentArgumentAndRenderStatic;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Initialize arguments.
     *
     * @throws Exception
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('email', 'string', 'Email for gravatar.com');
        $this->registerArgument('size', 'integer', 'gravatar image size in pixel');
        $this->registerArgument('class', 'string', 'gravatar class for the image tag');
    }

    /**
     * Builds the gravatar image tag for a given email
     *
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        if ($arguments['class'] !== '') {
            $additionalClasses = ' ' . $arguments['class'];
        } else {
            $additionalClasses = '';
        }
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($arguments['email'])));
        $url .= '?s=' . $arguments['size'];
        $url = '<img src="' . $url . '" class="gravatar-img' . $additionalClasses . '"';
        $url .= ' />';

        return $url;
    }
}
