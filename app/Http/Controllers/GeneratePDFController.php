<?php

namespace App\Http\Controllers;

use App\Models\RefMechanicsModel;
use App\Models\TblJobOrderModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneratePDFController extends Controller
{
    public function generateMechanicsListPDF()
    {
        try {
            if (!request()->hasValidSignature()) {
                abort(401);
            }

            $mechanics = RefMechanicsModel::select(
                'id',
                'name',
                DB::raw("IF(deleted_at IS NULL, 'Active', 'Inactive') as status"),
                DB::raw("(SELECT COUNT(*)
                        FROM tbl_job_order
                        WHERE ref_status_id = 1
                            AND JSON_CONTAINS(tbl_job_order.ref_mechanics, JSON_QUOTE(CAST(ref_mechanics.id AS CHAR)))
                    ) as pending_jobs"),
                DB::raw("(SELECT COUNT(*)
                        FROM tbl_job_order
                        WHERE ref_status_id = 2
                            AND JSON_CONTAINS(tbl_job_order.ref_mechanics, JSON_QUOTE(CAST(ref_mechanics.id AS CHAR)))
                    ) as completed_jobs"),
                DB::raw("(SELECT COUNT(*)
                        FROM tbl_job_order
                            WHERE JSON_CONTAINS(tbl_job_order.ref_mechanics, JSON_QUOTE(CAST(ref_mechanics.id AS CHAR)))
                    ) as total_jobs")
            )
                ->get();

            $cdo_full = public_path('assets/images/compressed_cdofull.png');
            $rise_logo = public_path('assets/images/risev2.png');
            $watermark = public_path('assets/images/CITY DEPOT LOGO.png');

            $data = [
                'cdo_full' => base64_encode(file_get_contents($cdo_full)),
                'rise_logo' => base64_encode(file_get_contents($rise_logo)),
                'watermark' => base64_encode(file_get_contents($watermark)),
                'mechanics' => $mechanics
            ];

            $pdf = Pdf::loadView('livewire.pdf.mechanics_list_pdf', $data); // Load the PDF to the view
            return $pdf->stream('mechanics_list_pdf'); // Stream the PDF to the browser
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 404);
        }
    }

    //* This function is from the Settings > Mechanics.php
    public function generateMechanicsPDF()
    {
        try {
            if (!request()->hasValidSignature()) {
                abort(403, 'Invalid or expired URL');
            }

            $mechanics = RefMechanicsModel::withTrashed()
                ->select(
                    'id',
                    'name',
                    DB::raw("IF(deleted_at IS NULL, 'Active', 'Inactive') as status")
                )
                ->get();

            $cdo_full = public_path('assets/images/compressed_cdofull.png');
            $rise_logo = public_path('assets/images/risev2.png');
            $watermark = public_path('assets/images/CITY DEPOT LOGO.png');

            $data = [
                'cdo_full' => base64_encode(file_get_contents($cdo_full)),
                'rise_logo' => base64_encode(file_get_contents($rise_logo)),
                'watermark' => base64_encode(file_get_contents($watermark)),
                'mechanics' => $mechanics
            ];

            $pdf = Pdf::loadView('livewire.pdf.mechanics_pdf', $data); // Load the PDF to the view
            return $pdf->stream('mechanics_pdf'); // Stream the PDF to the browser
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 404);
        }
    }

    //* This function is from the Mechanics Details page
    public function generateJobOrdersPDF()
    {
        try {
            if (!request()->hasValidSignature()) {
                abort(403, 'Invalid or expired URL');
            }

            $mechanic_id = request()->route('id');

            $mechanic = RefMechanicsModel::withTrashed()
                ->select(
                    'id',
                    'name',
                    DB::raw("IF(deleted_at IS NULL, 'Active', 'Inactive') as status"),
                    DB::raw("(SELECT COUNT(*)
                    FROM tbl_job_order
                    WHERE ref_status_id = 1
                        AND JSON_CONTAINS(tbl_job_order.ref_mechanics, JSON_QUOTE(CAST(ref_mechanics.id AS CHAR)))
                ) as pending_jobs"),
                    DB::raw("(SELECT COUNT(*)
                    FROM tbl_job_order
                    WHERE ref_status_id = 2
                        AND JSON_CONTAINS(tbl_job_order.ref_mechanics, JSON_QUOTE(CAST(ref_mechanics.id AS CHAR)))
                ) as completed_jobs"),
                    DB::raw("(SELECT COUNT(*)
                    FROM tbl_job_order
                        WHERE JSON_CONTAINS(tbl_job_order.ref_mechanics, JSON_QUOTE(CAST(ref_mechanics.id AS CHAR)))
                ) as total_jobs")
                )
                ->findOrFail($mechanic_id);

            // Query to get job orders where this mechanic is listed in the 'ref_mechanics' JSON array
            // Cast mechanic_id to string to avoid the JSON_QUOTE error
            $mechanic_jobs = TblJobOrderModel::with(['category', 'status', 'type_of_repair'])
                ->whereRaw("JSON_CONTAINS(ref_mechanics, JSON_QUOTE(?))", [(string) $mechanic_id])
                ->get();

            $mechanic_jobs->each(function ($mechanic_job) {
                $mechanic_job->append('sub_category_names');
            });

            $cdo_full = public_path('assets/images/compressed_cdofull.png');
            $rise_logo = public_path('assets/images/risev2.png');
            $watermark = public_path('assets/images/CITY DEPOT LOGO.png');

            $data = [
                'cdo_full' => base64_encode(file_get_contents($cdo_full)),
                'rise_logo' => base64_encode(file_get_contents($rise_logo)),
                'watermark' => base64_encode(file_get_contents($watermark)),
                'mechanic' => $mechanic,
                'mechanic_jobs' => $mechanic_jobs
            ];

            $pdf = Pdf::loadView('livewire.pdf.job_orders_pdf', $data); // Load the PDF to the view
            return $pdf->stream('job_orders_pdf'); // Stream the PDF to the browser
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 404);
        }
    }

    public function generateReleaseForm()
    {
        try {
            if (!request()->hasValidSignature()) {
                abort(403, 'Invalid or expired URL');
            }

            $job_order_id = request()->route('id');

            //TODO: Add the necessary query to get the job order details
            $pdf = Pdf::loadView('livewire.pdf.release_form_pdf'); // Load the PDF to the view
            return $pdf->stream('release_form_pdf'); // Stream the PDF to the browser
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 404);
        }
    }
}
