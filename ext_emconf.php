<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'EXT:ot_lib',
    'description' => 'Library of ViewHelpers and DataProcessors',
    'category' => 'frontend',
    'state' => 'stable',
    'author' => 'Oliver Thiele',
    'author_email' => 'mail@oliver-thiele.de',
    'author_company' => 'Web Development Oliver Thiele',
    'version' => '2.0.0-dev',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-13.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
