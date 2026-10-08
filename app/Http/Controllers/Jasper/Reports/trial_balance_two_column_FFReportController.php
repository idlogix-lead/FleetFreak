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
        $options["params"] = self::params(request()->query('filter_date_range'));

        return JasperController::jasper($input, $output, $options, $template, $ext);
    }

    // PHPJasper writes every param into a shell command without escaping, so only these four values, built here,
    // may reach it (read_filter_fields() passed every query-string key and value through).
    static function params($dateRange): array
    {
        [$start, $end] = self::dateRange($dateRange);

        return [
            'client_id' => (int) auth()->user()->client_id,
            'company_id' => (int) auth()->user()->active_company(),
            'start_date' => $start,
            'end_date' => $end,
        ];
    }

    // "YYYY-MM-DD - YYYY-MM-DD" from the date-range filter; anything else (including no filter) is this year.
    static function dateRange($dateRange): array
    {
        if (is_string($dateRange) && preg_match('/^(\d{4}-\d{2}-\d{2}) - (\d{4}-\d{2}-\d{2})$/', $dateRange, $m)
            && self::isDate($m[1]) && self::isDate($m[2]) && $m[1] <= $m[2]) {
            return [$m[1], $m[2]];
        }

        return [now()->startOfYear()->toDateString(), now()->endOfYear()->toDateString()];
    }

    private static function isDate(string $date): bool
    {
        [$year, $month, $day] = array_map('intval', explode('-', $date));

        return checkdate($month, $day, $year);
    }
}
