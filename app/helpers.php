<?php
    // Every definition is guarded: this file sits in the PSR-4 root (app/), so anything that asks the autoloader for a
    // class named App\helpers (an editor scanning app/*.php for models, for one) includes it a second time.

    // define('CURRENCY_POSITION','pre');

    if (! defined('CURRENCY_POSITION')) {
        define('CURRENCY_POSITION','post');
    }
    if (! function_exists('is_active_route')) {
        function is_active_route($path) {
            return call_user_func_array('Request::is', (array)$path) ? 'true' : 'false';
        }
    }
    if (! function_exists('active_class')) {
        function active_class($path, $active = 'active') {
            return call_user_func_array('Request::is', (array)$path) ? $active : '';
        }
    }
    if (! function_exists('show_class')) {
        function show_class($path) {
            return call_user_func_array('Request::is', (array)$path) ? 'show' : '';
        }
    }

    if (! function_exists('current_currency')) {
        function current_currency(){
            return App\Models\Currency::current_currency();
        }
    }
    if (! function_exists('apply_currency_symbol')) {
        function apply_currency_symbol($value){
            $symbol = App\Models\Currency::current_symbol();
            if(CURRENCY_POSITION == 'pre'){
                return $symbol.' '.$value;
            }else if(CURRENCY_POSITION == 'post'){
                return $value.' '.$symbol;
            }
        }
    }
    if (! function_exists('apply_currency_code')) {
        function apply_currency_code($value){
            $code = App\Models\Currency::current_code();
            if(CURRENCY_POSITION == 'pre'){
                return $code.' '.$value;
            }else if(CURRENCY_POSITION == 'post'){
                return $value.' '.$code;
            }
        }
    }
