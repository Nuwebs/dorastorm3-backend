<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    /**
     * The order of the roles matters. The higher the role, the lower the hierarhy (0 is top priority)
     */
    'roles_structure' => [
        'superadmin' => [
            'users' => 'c,r,u,d',
            'posts' => 'c,r,u,d',
            'roles' => 'c,r,u,d',
            'quotations' => 'r,d',
            'profile' => 'r,u'
        ],
        'admin' => [
            'users' => 'c,r,u,d',
            'posts' => 'c,r,u,d',
            'roles' => 'r',
            'quotations' => 'r,d',
            'profile' => 'r,u'
        ],
        'editor' => [
            'posts' => 'c,r,u,d',
            'profile' => 'r,u',
        ],
        config('laratrust.most_basic_role_name') => [
            'profile' => 'r,u',
        ],
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
