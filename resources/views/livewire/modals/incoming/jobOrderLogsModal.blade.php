<!-- jobOrderLogsModal -->
<div class="modal fade" id="jobOrderLogsModal" tabindex="-1" aria-labelledby="jobOrderLogsModalLabel" aria-hidden="true" data-bs-backdrop="static" wire:ignore.self>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="jobOrderLogsModalLabel">Logs</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="16.67%">Action</th>
                                <th width="16.67%">Field/s</th>
                                <th width="16.67%">Old Value/s</th>
                                <th width="16.67%">Updated Value/s</th>
                                <th width="16.67%">Updated At</th>
                                <th width="16.67%">Updated By</th>
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
                            // 'ref_status_id' => 'Status',
                            'ref_type_of_repair_id' => 'Type of repair',
                            'ref_mechanics' => 'Mechanics',
                            'issue_or_concern' => 'Issue/Concern',
                            'findings' => 'Findings',
                            'date_and_time_out' => 'Date & Time (OUT)',
                            'total_repair_time' => 'Total repair time',
                            'claimed_by' => 'Claimed by',
                            'remarks' => 'Remarks',
                            // 'ref_signatories_id' => 'Signatory'
                            ];
                            @endphp
                            <tr>
                                <td class="text-capitalize">
                                    {{ $item->description }}
                                </td>
                                <td>
                                    @foreach (array_keys(array_merge($oldAttributes, $updatedAttributes)) as $key)
                                    @if (!in_array($key, ['id', 'job_order_no', 'reference_no', 'ref_signatories_id', 'ref_status_id', 'updated_at', 'created_at', 'deleted_at']))
                                    <div>
                                        {{ $fieldLabels[$key] ?? ucwords(str_replace('_', ' ', $key)) }}
                                    </div>
                                    @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach (array_keys(array_merge($oldAttributes, $updatedAttributes)) as $key)
                                    @if (!in_array($key, ['id', 'job_order_no', 'reference_no', 'ref_signatories_id', 'ref_status_id', 'updated_at', 'created_at', 'deleted_at']))
                                    <div>
                                        @if ($key === 'ref_location_id' && isset($oldAttributes[$key]))
                                        {{ optional(\App\Models\RefLocationModel::find($oldAttributes[$key]))->name ?? 'Not available' }}
                                        @elseif ($key === 'ref_category_id' && isset($oldAttributes[$key]))
                                        {{ optional(\App\Models\RefCategoryModel::find($oldAttributes[$key]))->name ?? 'Not available' }}
                                        @elseif ($key === 'ref_sub_category_id' && isset($oldAttributes[$key]))
                                        {{ optional(\App\Models\RefSubCategoryModel::find($oldAttributes[$key]))->name ?? 'Not available' }}
                                        @elseif ($key === 'ref_type_of_repair_id' && isset($oldAttributes[$key]))
                                        {{ optional(\App\Models\RefTypeOfRepairModel::find($oldAttributes[$key]))->name ?? 'Not available' }}
                                        @elseif ($key === 'ref_mechanics' && isset($oldAttributes[$key]))
                                        @php
                                        // Get the mechanic IDs from the old attributes (it's a JSON-encoded array)
                                        $mechanicIds = json_decode($oldAttributes[$key], true);

                                        // Retrieve the mechanics based on the IDs
                                        $mechanics = \App\Models\RefMechanicsModel::whereIn('id', $mechanicIds)->get();
                                        @endphp
                                        {{ implode(', ', $mechanics->pluck('name')->toArray()) ?? 'Not available' }}
                                        @else
                                        {{ $oldAttributes[$key] ?? 'Not available' }}
                                        @endif
                                    </div>
                                    @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach (array_keys(array_merge($oldAttributes, $updatedAttributes)) as $key)
                                    @if (!in_array($key, ['id', 'job_order_no', 'reference_no', 'ref_signatories_id', 'ref_status_id', 'updated_at', 'created_at', 'deleted_at']))
                                    <div>
                                        @if ($key === 'ref_location_id')
                                        {{ $item->subject->location->name ?? 'Not available' }}
                                        @elseif ($key === 'ref_category_id')
                                        {{ $item->subject->category->name ?? 'Not available' }}
                                        @elseif ($key === 'ref_sub_category_id')
                                        {{ $item->subject->sub_category->name ?? 'Not available' }}
                                        @elseif ($key === 'ref_type_of_repair_id')
                                        {{ $item->subject->type_of_repair->name ?? 'Not available' }}
                                        @elseif ($key === 'ref_mechanics')
                                        @php
                                        // Get the mechanic IDs from the old attributes (it's a JSON-encoded array)
                                        $mechanicIds = json_decode($updatedAttributes[$key], true);

                                        // Retrieve the mechanics based on the IDs
                                        $mechanics = \App\Models\RefMechanicsModel::whereIn('id', $mechanicIds)->get();
                                        @endphp
                                        {{ implode(', ', $mechanics->pluck('name')->toArray()) ?? 'Not available' }}
                                        @else
                                        {{ $updatedAttributes[$key] ?? 'Not available' }}
                                        @endif
                                    </div>
                                    @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach (array_keys(array_merge($oldAttributes, $updatedAttributes)) as $key)
                                    @if ($key === 'updated_at')
                                    {{ \Carbon\Carbon::parse($updatedAttributes[$key])->setTimezone('Asia/Manila')->format('M d, Y h:i A') ?? 'Not available' }}
                                    @endif
                                    @endforeach
                                </td>
                                <td>
                                    {{ optional($item->causer)->name ?? 'N/A' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No logs.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>