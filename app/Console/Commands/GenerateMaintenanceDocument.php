<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\MaintenanceController;
use App\Models\Company;
use App\Models\InvoiceDocumentType;

use App\Models\Invoice;
use App\Models\InvoiceLine;

class GenerateMaintenanceDocument extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-maintenance-document';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $companies = Company::get();
        foreach($companies as $company){

            $template = MaintenanceController::document_generator_template($company->id);

            $document_type_id = 1; //for maintenance

            // date interval in vehicle
            // oilchange km  interval in vehicle

            $date = '21-11-2024';
            $vehicle_id = 1;
            $vendor_id = 1;

            $created_by = 1;

            $data = [
                'vehicle_id' => $vehicle_id,
                'date' => $date,
                'business_partner_id' => $vendor_id,
                'description' => "System Generated",
                'total_amount' => 0,
                'grand_total_amount' => 0,
                'created_by' => $created_by,
                'document_status' => 'draft',
                'document_type_id' => $document_type_id,
                'company_id' => $company->id,
                'document_no' => Invoice::generate_document_no1($company->id, InvoiceDocumentType::find($document_type_id)),
            ];

            foreach($template[1]?->activityLines??[] as $activity){
                $data['row'][] = [
                    'is_checked' => 1,
                    'activity_line_id' => $activity->id,
                    'is_service_charge' => 0,
                    'description' => "",
                    'quantity' => 1,
                    'rate' => 0,
                    'line_amount' => 0,
                ];
            }
            $data['row'][] = [
                'is_checked' => 1,
                'activity_line_id' => null,
                'is_service_charge' => 1,
                'description' => "Service Charges",
                'quantity' => 1,
                'rate' => 0,
                'line_amount' => 0,
            ];

        }
    }
}
