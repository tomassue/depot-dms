<!-- jobOrderLogsModal -->
<div class="modal fade" id="jobOrderLogsModal" tabindex="-1" aria-labelledby="jobOrderLogsModalLabel" aria-hidden="true" data-bs-backdrop="static" wire:ignore.self>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="jobOrderLogsModalLabel">Findings Logs</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Findings/Remarks</th>
                                <th>Updated At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($job_order_logs as $item)
                            @php
                            $oldAttributes = $item->properties['old'] ?? [];
                            $updatedAttributes = $item->properties['attributes'] ?? [];
                            @endphp
                            <tr>
                                <td class="text-capitalize">
                                    {{ $item->status_name ?? 'Not available' }}
                                </td>
                                <td class="text-capitalize">
                                    {{ $updatedAttributes['findings'] ?? '' }}
                                    {{ $updatedAttributes['remarks'] ?? '' }}
                                </td>
                                <td class="text-capitalize">
                                    {{ isset($updatedAttributes['updated_at']) ? \Carbon\Carbon::parse($updatedAttributes['updated_at'])->format('M d, Y h:i A') : 'Not available' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">No logs.</td>
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