<?php


if (!function_exists('flash')) {
    function flash(): \Support\Flash\Flash
    {
        return app(\Support\Flash\Flash::class);
    }

}
