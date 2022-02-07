<?php

declare(strict_types=1);

defined('TYPO3') or die();

call_user_func(function () {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['otl'][] =
        'OliverThiele\OtLib\ViewHelpers';
});
