<?php
return [
    'POST v1/auth/login' => 'v1/user/login',
    'POST v1/auth/signup' => 'v1/user/signup',

    'POST v1/posts' => 'v1/post/create',
    'GET v1/posts' => 'v1/post/get-all',
    'GET v1/user/posts' => 'v1/user/get-current-user-posts',
];
