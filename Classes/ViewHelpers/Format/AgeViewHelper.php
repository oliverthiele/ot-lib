<?php

declare(strict_types=1);

namespace OliverThiele\OtLib\ViewHelpers\Format;

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
use DateTime;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

class AgeViewHelper extends AbstractViewHelper
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
        $this->registerArgument('date', 'mixed', 'Datetime or Unix Timestamp to format');
        $this->registerArgument('intervalFormat', 'string', 'Format if the interval', false);
    }

    /**
     * Parser for the page footer
     *
     * @param array<int|string, mixed> $arguments
     * @param Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        $return = '';
        $date = $arguments['date'];

        if ($date === null) {
            $date = $renderChildrenClosure();
        }

        if (is_int($date)) {
            $date = DateTime::createFromFormat('U', (string)$date);
        }

        if ($date instanceof DateTime) {
            $now = new DateTime();
            $interval = date_diff($date, $now);

            if (isset($arguments['intervalFormat'])) {
                return $interval->format($arguments['intervalFormat']);
            }

            $days = (int)$interval->format('%a');
            if ($days < 1) {
                return $interval->format('heute');
            }
            if ($days === 1) {
                return $interval->format('vor einem Tag');
            }
            if ($days > 1 && $days < 7) {
                return $interval->format('vor ' . $days . ' Tagen');
            }
            if ($days >= 7 && $days < 14) {
                return $interval->format('vor einer Woche');
            }
            if ($days >= 7) {
                return $date->format('d.m.Y');
            }
        }

        return $return;
    }
}
