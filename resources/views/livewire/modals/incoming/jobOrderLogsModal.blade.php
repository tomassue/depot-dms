<!-- jobOrderLogsModal -->
<style>
    /* Custom styles for the changes table */
    .changes-table th.field-column,
    .changes-table td.field-column {
        width: 150px !important;
    }

    .changes-table th.value-column,
    .changes-table td.value-column {
        width: 45% !important;
    }

    .changes-table th,
    .changes-table td {
        padding: 8px 12px;
    }

    .changes-table .text-wrap {
        white-space: normal;
        overflow-wrap: break-word;
    }
</style>

<div class="modal fade" id="jobOrderLogsModal" tabindex="-1" aria-labelledby="jobOrderLogsModalLabel" aria-hidden="true" data-bs-backdrop="static" wire:ignore.self>
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="jobOrderLogsModalLabel">Logs</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="10%">Action</th>
                            <th width="65%">Changes</th>
                            <th width="10%">Updated At</th>
                            <th width="15%">Updated By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($job_order_logs as $item)
                        @php
                        $oldAttributes = $item->properties['old'] ?? [];
                        $updatedAttributes = $item->properties['attributes'] ?? [];
                        $fieldLabels = [
                        'job_order_no' => 'Job Order No.',
                        'reference_no' => 'Reference No.',
                        'date_and_time_in' => 'Date & Time (IN)',
                        'ref_category_id' => 'Category',
                        'ref_sub_category_id' => 'Sub-category',
                        'mileage' => 'Mileage',
                        'ref_location_id' => 'Location',
                        'person_in_charge' => 'Person in charge',
                        'contact_numnber' => 'Contact No.',
                        'ref_type_of_repair_id' => 'Type of repair',
                        'ref_mechanics' => 'Mechanics',
                        'issue_or_concern' => 'Issue/Concern',
                        'findings' => 'Findings',
                        'date_and_time_out' => 'Date & Time (OUT)',
                        'total_repair_time' => 'Total repair time',
                        'claimed_by' => 'Claimed by',
                        'remarks' => 'Remarks',
                        'ref_status_id' => 'Status',
                        ];
                        @endphp
                        <tr>
                            <td class="text-capitalize align-top">
                                {{ $item->description }}
                            </td>
                            <td class="align-top">
                                <table class="table-bordered changes-table" style="border-color: #e5e7eb;">
                                    <thead>
                                        <tr>
                                            <th class="field-column">Fields</th>
                                            <th class="value-column">Old Values</th>
                                            <th class="value-column">New Values</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (array_keys(array_merge($oldAttributes, $updatedAttributes)) as $key)
                                        @if (!in_array($key, ['id', 'job_order_no', 'reference_no', 'ref_signatories_id', 'updated_at', 'created_at', 'deleted_at']))
                                        <tr>
                                            <td class="field-column align-top">{{ $fieldLabels[$key] ?? ucwords(str_replace('_', ' ', $key)) }}</td>
                                            <td class="value-column align-top">
                                                <div class="text-wrap">
                                                    @if ($key === 'ref_location_id' && isset($oldAttributes[$key]))
                                                    {{ optional(\App\Models\RefLocationModel::find($oldAttributes[$key]))->name ?? 'Not available' }}
                                                    @elseif ($key === 'ref_category_id' && isset($oldAttributes[$key]))
                                                    {{ optional(\App\Models\RefCategoryModel::find($oldAttributes[$key]))->name ?? 'Not available' }}
                                                    @elseif ($key === 'ref_sub_category_id' && isset($oldAttributes[$key]))
                                                    @php
                                                    $subCategoryIds = json_decode($oldAttributes[$key], true);
                                                    $subCategory = \App\Models\RefSubCategoryModel::whereIn('id', $subCategoryIds)->get();
                                                    @endphp
                                                    {{ implode(', ', $subCategory->pluck('name')->toArray()) ?? 'Not available' }}
                                                    @elseif ($key === 'ref_type_of_repair_id' && isset($oldAttributes[$key]))
                                                    {{ optional(\App\Models\RefTypeOfRepairModel::find($oldAttributes[$key]))->name ?? 'Not available' }}
                                                    @elseif ($key === 'ref_mechanics' && isset($oldAttributes[$key]))
                                                    @php
                                                    $mechanicIds = json_decode($oldAttributes[$key], true);
                                                    $mechanics = \App\Models\RefMechanicsModel::whereIn('id', $mechanicIds)->get();
                                                    @endphp
                                                    {{ implode(', ', $mechanics->pluck('name')->toArray()) ?? 'Not available' }}
                                                    @elseif ($key === 'files' && isset($oldAttributes[$key]))
                                                    @php
                                                    $filesIds = json_decode($oldAttributes[$key], true);
                                                    $files = \App\Models\FileDataModel::whereIn('id', $filesIds)->get();
                                                    @endphp
                                                    {{ implode(', ', $files->pluck('name')->toArray()) ?? 'Not available' }}
                                                    @elseif ($key === 'ref_status_id' && isset($oldAttributes[$key]))
                                                    {{ optional(\App\Models\RefStatusModel::find($oldAttributes[$key]))->name ?? 'Not available' }}
                                                    @else
                                                    {{ $oldAttributes[$key] ?? 'Not available' }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="value-column align-top">
                                                <div class="text-wrap">
                                                    @if ($key === 'ref_location_id')
                                                    {{ $item->subject->location->name ?? 'Not available' }}
                                                    @elseif ($key === 'ref_category_id')
                                                    {{ $item->subject->category->name ?? 'Not available' }}
                                                    @elseif ($key === 'ref_sub_category_id')
                                                    @php
                                                    $subCategoryIds = json_decode($updatedAttributes[$key], true);
                                                    $subCategory = \App\Models\RefSubCategoryModel::whereIn('id', $subCategoryIds)->get();
                                                    @endphp
                                                    {{ implode(', ', $subCategory->pluck('name')->toArray() ?? 'Not available') }}
                                                    @elseif ($key === 'ref_type_of_repair_id')
                                                    {{ $item->subject->type_of_repair->name ?? 'Not available' }}
                                                    @elseif ($key === 'ref_mechanics')
                                                    @php
                                                    $mechanicIds = json_decode($updatedAttributes[$key], true); // Get the mechanic IDs from the old attributes (it's a JSON-encoded array)
                                                    $mechanics = \App\Models\RefMechanicsModel::whereIn('id', $mechanicIds)->get(); // Retrieve the mechanics based on the IDs
                                                    @endphp
                                                    {{ implode(', ', $mechanics->pluck('name')->toArray()) ?? 'Not available' }}
                                                    @elseif ($key === 'files')
                                                    @php
                                                    $filesIds = json_decode($updatedAttributes[$key], true);
                                                    $files = \App\Models\FileDataModel::whereIn('id', $filesIds)->get();
                                                    @endphp
                                                    {{ implode(', ', $files->pluck('name')->toArray()) ?? 'Not available' }}
                                                    @elseif ($key === 'ref_status_id')
                                                    {{ optional(\App\Models\RefStatusModel::find($updatedAttributes[$key]))->name ?? 'Not available' }}
                                                    @else
                                                    {{ $updatedAttributes[$key] ?? 'Not available' }}
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                            <td class="align-top">
                                {{ \Carbon\Carbon::parse($item->updated_at)->setTimezone('Asia/Manila')->format('M d, Y h:i A') ?? 'Not available' }}
                            </td>
                            <td class="align-top">
                                {{ optional($item->causer)->name ?? 'N/A' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No logs.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>