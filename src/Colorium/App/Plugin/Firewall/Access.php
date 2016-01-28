<?php

namespace Colorium\App\Plugin\Firewall;

class Access
{

    /** @var bool */
    public $granted = true;

    /** @var int */
    public $level = 0;

    /** @var bool */
    public $auth = false;

    /** @var object */
    public $user;


    /**
     * Define access
     *
     * @param bool $granted
     * @param int $level
     * @param bool $auth
     * @param object $user
     */
    public function __construct($granted, $level, $auth = false, $user = null)
    {
        $this->granted = $granted;
        $this->level = $level;
        $this->auth = $auth;
        $this->user = $user;
    }

}