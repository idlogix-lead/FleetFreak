<?php

namespace App\Http\Controllers\Jasper\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Jasper\JasperController;
use App\Http\Controllers\Jasper\JasperReportsController;

class trial_balance_two_column_FFReportController extends JasperReportsController
{
    static $ignores = [];
    static $role_module_id = 48;

    function __construct(){
        $this->middleware('RolePermissions');
    }
    //reports -----------------------------------------------------------
    static function boot($input, $output, $options, $template, $ext, $user)
    {
        $options["format"] = [$ext];
        $options["params"] = self::read_filter_fields();
        $options["params"]["client_id"] =auth()->user()->client_id;
        $options["params"]["company_id"] = auth()->user()->active_company();



        // dd($options["params"]);

        return JasperController::jasper($input, $output, $options, $template, $ext);
    }
}
