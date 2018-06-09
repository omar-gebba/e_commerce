<?php 

function lang($phrase) {
    static $lang = array(
        'brand'         => 'Web Site',
        'sections'      => 'Sections',
        'items'         => 'Items',
        'members'       => 'Members',
        'comments'       => 'Comments',
        'statstics'     => 'Statstics',
        'logs'          => 'Logs',
        'edit profile'  => 'Edit Profile',
        'setting'       => 'Setting',
        'log out'       => 'Log Out',
        'default'       => 'Default',
        'dashboard'     => 'Dashboard',
        'Categories'    => 'Categories',
    );
    return $lang[$phrase];
}