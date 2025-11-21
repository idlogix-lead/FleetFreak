<?php
    // define('CURRENCY_POSITION','pre');
    
    define('CURRENCY_POSITION','post');
    function is_active_route($path) {
        return call_user_func_array('Request::is', (array)$path) ? 'true' : 'false';
    }
    function active_class($path, $active = 'active') {
        return call_user_func_array('Request::is', (array)$path) ? $active : '';
    }
    function show_class($path) {
        return call_user_func_array('Request::is', (array)$path) ? 'show' : '';
    }

    function current_currency(){
        return App\Models\Currency::current_currency();
    }
    function apply_currency_symbol($value){
        $symbol = App\Models\Currency::current_symbol();
        if(CURRENCY_POSITION == 'pre'){
            return $symbol.' '.$value;
        }else if(CURRENCY_POSITION == 'post'){
            return $value.' '.$symbol;
        }
    }
    function apply_currency_code($value){
        $code = App\Models\Currency::current_code();
        if(CURRENCY_POSITION == 'pre'){
            return $code.' '.$value;
        }else if(CURRENCY_POSITION == 'post'){
            return $value.' '.$code;
        }
    }
