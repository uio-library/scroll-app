<?php

$basedir = dirname(__dir__) ;
return [
    'chains' => [
        Despark\ImagePurify\Chains\JpegChain::class => [
            'commands' => [
                'mozJpeg' => [
                    'bin' => $basedir . '/node_modules/.bin/mozjpeg',
                    'arguments' => ['-optimize', '-progressive'],
                    'customClass' => Despark\ImagePurify\Commands\MozJpeg::class,
                ],
            ],
            'first_only' => false,
        ],
        Despark\ImagePurify\Chains\PngChain::class => [
            'commands' => [
                'pngQuant' => [
                    'bin' => $basedir . '/node_modules/.bin/pngquant',
                    'arguments' => ['-f', '--skip-if-larger', '--strip'],
                    'customClass' => Despark\ImagePurify\Commands\PngQuant::class,
                ],
                'optiPng' => [
                    'bin' => $basedir . '/node_modules/.bin/optipng',
                    'arguments' => ['-i0', '-o2', '-quiet'],
                ],
            ],
            'first_only' => false,
        ],
        Despark\ImagePurify\Chains\GifChain::class => [
            'commands' => [
                'giflossy' => [
                    'bin' => $basedir . '/node_modules/.bin/gifsicle',
                    'arguments' => ['-b', '-O3', '--lossy=100', '--no-extensions'],
                ],
            ],
        ],
    ],
    'suppress_errors' => false,
];
