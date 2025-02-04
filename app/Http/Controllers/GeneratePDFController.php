<?php

namespace App\Http\Controllers;

use App\Models\RefMechanicsModel;
use App\Models\RefSignatoriesModel;
use App\Models\TblJobOrderModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GeneratePDFController extends Controller
{
    //* This function is from the Mechanics page
    public function generateMechanicsListPDF()
    {
        try {
            if (!request()->hasValidSignature()) {
                abort(401);
            }

            // $date = request()->route('date');
            // $filter_section = request()->route('section');
            // $filter_sub_section = request()->route('sub_section');
            // $search = request()->route('search');

            // Retrieve parameters from route or query string
            $date = request()->route('date') ?? request()->query('date');
            $filter_section = request()->route('filter_section') ?? request()->query('filter_section');
            $filter_sub_section = request()->route('filter_sub_section') ?? request()->query('filter_sub_section');
            $search = request()->route('search') ?? request()->query('search');

            // $mechanics = RefMechanicsModel::select(
            //     'id',
            //     'name',
            //     DB::raw("IF(deleted_at IS NULL, 'Active', 'Inactive') as status"),
            //     DB::raw("(SELECT COUNT(*)
            //             FROM tbl_job_order
            //             WHERE ref_status_id = 1
            //                 AND JSON_CONTAINS(tbl_job_order.ref_mechanics, JSON_QUOTE(CAST(ref_mechanics.id AS CHAR)))
            //         ) as pending_jobs"),
            //     DB::raw("(SELECT COUNT(*)
            //             FROM tbl_job_order
            //             WHERE ref_status_id = 2
            //                 AND JSON_CONTAINS(tbl_job_order.ref_mechanics, JSON_QUOTE(CAST(ref_mechanics.id AS CHAR)))
            //         ) as completed_jobs"),
            //     DB::raw("(SELECT COUNT(*)
            //             FROM tbl_job_order
            //                 WHERE JSON_CONTAINS(tbl_job_order.ref_mechanics, JSON_QUOTE(CAST(ref_mechanics.id AS CHAR)))
            //         ) as total_jobs")
            // )
            //     ->when($date != NULL, function ($query) use ($date) {
            //         if (str_contains($date, ' to ')) {
            //             [$startDate, $endDate] = array_map('trim', explode(' to ', $date));
            //             $query->whereBetween('created_at', [
            //                 Carbon::parse($startDate)->startOfDay(),
            //                 Carbon::parse($endDate)->endOfDay()
            //             ]);
            //         } else {
            //             $query->whereDate('created_at', Carbon::parse($date));
            //         }
            //     })
            //     ->get();

            $mechanics = RefMechanicsModel::with(['section', 'sub_section'])
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
                ) as total_jobs"),
                    'ref_sections_mechanic_id',
                    'ref_sub_sections_mechanic_id'
                )
                ->when(!empty($search), function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })
                ->when(!empty($filter_section), function ($query) use ($filter_section) {
                    $query->where('ref_sections_mechanic_id', $filter_section);
                })
                ->when(!empty($filter_sub_section), function ($query) use ($filter_sub_section) {
                    $query->where('ref_sub_sections_mechanic_id', $filter_sub_section);
                })
                ->when(!empty($date), function ($query) use ($date) {
                    if (str_contains($date, ' to ')) {
                        [$startDate, $endDate] = array_map('trim', explode(' to ', $date));
                        $query->whereBetween('created_at', [
                            Carbon::parse($startDate)->startOfDay(),
                            Carbon::parse($endDate)->endOfDay()
                        ]);
                    } else {
                        $query->whereDate('created_at', Carbon::parse($date));
                    }
                })
                ->get();

            // Group by sections and subsections
            $groupedSections = $mechanics->groupBy(function ($mechanic) {
                return $mechanic->section->name ?? 'Unassigned Section';
            })->map(function ($sectionGroup) {
                return $sectionGroup->groupBy(function ($mechanic) {
                    return $mechanic->sub_section->name ?? null; // If no subsection, group under null
                });
            });

            $cdo_full = public_path('assets/images/cdo-seal.png');
            $rise_logo = public_path('assets/images/risev2.png');
            $watermark = public_path('assets/images/compressed_city_depot_logo.png');

            $data = [
                'cdo_full' => base64_encode(file_get_contents($cdo_full)),
                'rise_logo' => base64_encode(file_get_contents($rise_logo)),
                'watermark' => base64_encode(file_get_contents($watermark)),
                'date' => $date ?? '-',
                'mechanics' => $mechanics,
                'groupedSections' => $groupedSections
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

            $date = request()->route('date');

            $mechanics = RefMechanicsModel::withTrashed()
                ->select(
                    'id',
                    'name',
                    DB::raw("IF(deleted_at IS NULL, 'Active', 'Inactive') as status")
                )
                ->when($date != NULL, function ($query) use ($date) {
                    if (str_contains($date, ' to ')) {
                        [$startDate, $endDate] = array_map('trim', explode(' to ', $date));
                        $query->whereBetween('created_at', [
                            Carbon::parse($startDate)->startOfDay(),
                            Carbon::parse($endDate)->endOfDay()
                        ]);
                    } else {
                        $query->whereDate('created_at', Carbon::parse($date));
                    }
                })
                ->get();

            $cdo_full = public_path('assets/images/cdo-seal.png');
            $rise_logo = public_path('assets/images/risev2.png');
            $watermark = public_path('assets/images/compressed_city_depot_logo.png');

            $data = [
                'cdo_full' => base64_encode(file_get_contents($cdo_full)),
                'rise_logo' => base64_encode(file_get_contents($rise_logo)),
                'watermark' => base64_encode(file_get_contents($watermark)),
                'date' => $date ?? '-',
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
            $date_range = request()->route('date');

            $mechanic = RefMechanicsModel::with(['section', 'sub_section'])
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
                ) as total_jobs"),
                    'ref_sections_mechanic_id',
                    'ref_sub_sections_mechanic_id'
                )
                ->withTrashed()
                ->findOrFail($mechanic_id);

            // Query to get job orders where this mechanic is listed in the 'ref_mechanics' JSON array
            // Cast mechanic_id to string to avoid the JSON_QUOTE error
            $mechanic_jobs = TblJobOrderModel::with(['category', 'status', 'type_of_repair'])
                ->whereRaw("JSON_CONTAINS(ref_mechanics, JSON_QUOTE(?))", [(string) $mechanic_id])
                ->when($date_range != NULL, function ($query) use ($date_range) {
                    if (str_contains($date_range, ' to ')) {
                        [$startDate, $endDate] = array_map('trim', explode(' to ', $date_range));
                        $query->whereBetween('created_at', [
                            Carbon::parse($startDate)->startOfDay(),
                            Carbon::parse($endDate)->endOfDay()
                        ]);
                    } else {
                        $query->whereDate('created_at', Carbon::parse($date_range));
                    }
                })
                ->get();

            $mechanic_jobs->each(function ($mechanic_job) {
                $mechanic_job->append('sub_category_names');
                $mechanic_job->append('category_names');
            });

            $cdo_full = public_path('assets/images/cdo-seal.png');
            $rise_logo = public_path('assets/images/risev2.png');
            $watermark = public_path('assets/images/compressed_city_depot_logo.png');

            $data = [
                'cdo_full' => base64_encode(file_get_contents($cdo_full)),
                'rise_logo' => base64_encode(file_get_contents($rise_logo)),
                'watermark' => base64_encode(file_get_contents($watermark)),
                'date' => $date_range ?? '-',
                'mechanic' => $mechanic,
                'mechanic_jobs' => $mechanic_jobs
            ];

            $pdf = Pdf::loadView('livewire.pdf.job_orders_pdf', $data)
                ->setPaper('a4', 'landscape'); // Load the PDF to the view

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

            $cdo_full = public_path('assets/images/cdo-seal.png');
            $rise_logo = public_path('assets/images/risev2.png');
            $watermark = public_path('assets/images/compressed_city_depot_logo.png');

            $job_order_id = request()->route('id');

            $job_order = TblJobOrderModel::findOrFail($job_order_id);
            $division_chief = RefSignatoriesModel::where('is_division_chief', '1')->first();

            $data = [
                'cdo_full' => base64_encode(file_get_contents($cdo_full)),
                'rise_logo' => base64_encode(file_get_contents($rise_logo)),
                'watermark' => base64_encode(file_get_contents($watermark)),
                'job_order' => $job_order,
                'division_chief' => $division_chief
            ];

            activity()
                ->causedBy(Auth::user()) // The user who printed the job order
                ->performedOn($job_order) // The job order being printed
                ->withProperties([
                    'job_order_id' => $job_order->id
                ])
                ->event('printed job order release form')
                ->log("Release form of Job Order #{$job_order->id} is printed.");

            $pdf = Pdf::loadView('livewire.pdf.release_form_pdf', $data); // Load the PDF to the view

            return $pdf->stream('release_form_pdf'); // Stream the PDF to the browser
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 404);
        }
    }
}
