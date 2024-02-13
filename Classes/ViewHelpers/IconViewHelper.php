<?php

declare(strict_types=1);

namespace OliverThiele\OtLib\ViewHelpers;

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

use Closure;
use OliverThiele\OtLib\Utility\IconUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

/**
 * Class IconViewHelper
 * The IconViewHelper can generate svg and img tags
 * (This is the reason for not using the AbstractTagBasedViewHelper)
 *
 * @package OliverThiele\OtLib\ViewHelpers
 * @todo https://css-tricks.com/accessible-svgs/
 */
class IconViewHelper extends AbstractViewHelper
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

        // todo replace all color values with css classes
        $this->registerArgument('color', 'string', 'deprecated!');

        // Icon file / style
        $this->registerArgument('identifier', 'string', 'string with the icon identifier', true);
        $this->registerArgument('size', 'string', 'string with size of the icon. (2x, 3x, …)');
        $this->registerArgument('iconStyle', 'string', 'string with icon style (s/r/l/b/d/...)');
        $this->registerArgument(
            'returnAs',
            'string',
            'Return SVG as inline SVG, LocalStorage sprite, img tag, i tag [inline,localStorage,img]',
            false,
            'inline'
        );

        // CSS / JS
        $this->registerArgument('id', 'string', 'Attribute id');
        $this->registerArgument('additionalClasses', 'string', 'string with additional CSS classes');

        // Accessible SVG
        $this->registerArgument('aria-hidden', 'bool', 'When true, aria-hidden="true" is added.');
        $this->registerArgument('aria-label', 'string', 'The label of the SVG.');
        $this->registerArgument('aria-description', 'string', 'The description of the SVG.');

        // todo add role, if not "img"
        $this->registerArgument('role', 'string', 'The role of the img.', false, 'img');

        $this->registerArgument('title', 'string', 'if aria-hidden is true, the title can be set for :hover');
    }

    /**
     * @param array $arguments
     * @param Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        $identifier = $arguments['identifier'];

        if ($identifier === null) {
            $identifier = $renderChildrenClosure();
        }

        return IconUtility::getIconString(
            (string)$identifier,
            (string)$arguments['size'],
            (string)$arguments['iconStyle'],
            (string)$arguments['returnAs'],
            (string)$arguments['id'],
            (string)$arguments['additionalClasses'],
            (bool)$arguments['aria-hidden'],
            (string)$arguments['aria-label'],
            (string)$arguments['aria-description'],
            (string)$arguments['title'],
            (string)$arguments['role'],
        );
    }
}
