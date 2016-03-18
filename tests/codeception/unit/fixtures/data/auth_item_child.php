<?php
return [
    [
        'parent' => 'admin',
        'child' => 'manager',
    ],
    [
        'parent' => 'admin',
        'child' => 'user.admin',
    ],
    [
        'parent' => 'guest',
        'child' => 'issue.access',
    ],
    [
        'parent' => 'guest',
        'child' => 'project.access',
    ],
    [
        'parent' => 'manager',
        'child' => 'issue.delete',
    ],
    [
        'parent' => 'manager',
        'child' => 'member',
    ],
    [
        'parent' => 'manager',
        'child' => 'project.create',
    ],
    [
        'parent' => 'manager',
        'child' => 'project.delete',
    ],
    [
        'parent' => 'member',
        'child' => 'issue.update',
    ],
    [
        'parent' => 'member',
        'child' => 'project.update',
    ],
    [
        'parent' => 'member',
        'child' => 'user',
    ],
    [
        'parent' => 'user',
        'child' => 'guest',
    ],
    [
        'parent' => 'user',
        'child' => 'issue.create',
    ],
    // [
    //     'parent' => '',
    //     'child' => '',
    // ],
];
