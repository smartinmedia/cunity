<?php

namespace Core;

/**
 * Interface ModuleController
 * @package Core
 */
interface ModuleController
{

    /**
     * @param $user
     * @return mixed
     */
    public static function onRegister($user);

    /**
     * @param $user
     * @return mixed
     */
    public static function onUnregister($user);
}
