<?php

/**
 * @param $camel
 * @return string
 */
function cameltotitle($camel) : string
{
    return ucwords(implode(' ', preg_split('/(?=[A-Z])/', $camel)));
}
