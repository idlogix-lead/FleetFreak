<?php

namespace App\Http\Controllers\Jasper\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Jasper\JasperController;
use App\Http\Controllers\Jasper\JasperReportsController;

class testingusersReportController extends JasperReportsController
{
    static $ignores = [];
    static $role_module_id = 47;

    function __construct(){
        $this->middleware('RolePermissions');
    }
    //reports -----------------------------------------------------------
    static function boot($input, $output, $options, $template, $ext, $user)
    {
        $options["format"] = [$ext];
        $options["params"] = self::read_filter_fields();

        return JasperController::jasper($input, $output, $options, $template, $ext);
    }
}
