<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => 'app.js',
        'entrypoint' => true,
    ],
    'admin' => [
        'path' => 'admin.js',
        'entrypoint' => true,
    ],
    'jquery' => [
        'version' => '3.7.1',
    ],
    'popper.js' => [
        'version' => '1.16.1',
    ],
    'bootstrap/js/dist/alert' => [
        'version' => '4.6.2',
    ],
    'bootstrap/js/dist/collapse' => [
        'version' => '4.6.2',
    ],
    'bootstrap/js/dist/dropdown' => [
        'version' => '4.6.2',
    ],
    'bootstrap/js/dist/tab' => [
        'version' => '4.6.2',
    ],
    'bootstrap/js/dist/modal' => [
        'version' => '4.6.2',
    ],
    '@fortawesome/fontawesome-free/css/all.css' => [
        'version' => '6.5.1',
        'type' => 'css',
    ],
    'bootstrap/dist/css/bootstrap.min.css' => [
        'version' => '5.3.3',
        'type' => 'css',
    ],
    'datatables' => [
        'version' => '1.10.18',
    ],
    'datatables.net-dt' => [
        'version' => '2.1.4',
    ],
    'datatables.net' => [
        'version' => '2.1.4',
    ],
    'datatables.net-dt/css/dataTables.dataTables.min.css' => [
        'version' => '2.1.4',
        'type' => 'css',
    ],
];
