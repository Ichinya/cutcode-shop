<?php


if (!function_exists('flash')) {
    function flash(): \App\Support\Flash\Flash
    {
        return app(\App\Support\Flash\Flash::class);
    }

}
