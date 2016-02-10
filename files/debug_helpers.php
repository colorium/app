<?php

/**
 * Vardump
 *
 * @param $param
 * @param $params
 */
function vd($param, ...$params)
{
    var_dump($param, ...$params);
}



/**
 * Vardump and die
 *
 * @param $param
 * @param ...$params
 */
function dd($param, ...$params)
{
    var_dump($param, ...$params);
    exit;
}