<?php

namespace OliverThiele\OtLib\ViewHelpers\Format;

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

use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

/**
 * This ViewHelper is used for formatting the header text, if the header has the RTE enabled (TCA)
 * It replaces <p> tags with <br>, removes unnecessary white spaces and empty lines.
 *
 * Examples
 * ========
 *
 * Example::
 *
 * <otl:format.header><p>Header</p><p><strong>other style</strong></p><p>2. row</p></otl:format.header>
 *
 * Inline notation::
 *
 *    {data.header -> otl:format.header() -> f:format.raw()}
 *    {data.header -> otl:format.header()}
 *
 */
class HeaderViewHelper extends AbstractViewHelper
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
        $this->registerArgument('value', 'string', 'string to format');
    }

    /**
     * Change the order of a string with one comma
     *
     * @return string
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        $value = $arguments['value'];
        if ($value === null) {
            $value = $renderChildrenClosure();
        }

        // Replace <p> and </p> with <br>
        // Replace &nbsp; with a regular space
        $value = str_replace(array('<p>', '</p>', '&nbsp;'), array('<br>', '<br>', ' '), $value);

        // Split into lines based on <br>
        $lines = preg_split('/<br\s*\/?>/', $value);

        // Remove empty and whitespace-only lines
        $lines = array_filter($lines, static function ($line) {
            return trim($line) !== '';
        });

        // Trim all remaining lines
        $lines = array_map('trim', $lines);

        // Join the cleaned-up lines with <br>
        return implode('<br>', $lines);
    }
}
