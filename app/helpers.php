<?php

/**
 *  Convert route name into CSS class name.
 *
 *  Used to customize CSS for specific view page.
 *
 * @return string
 */
function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}
