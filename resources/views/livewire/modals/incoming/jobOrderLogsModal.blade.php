<!-- jobOrderLogsModal -->
<div class="modal fade" id="jobOrderLogsModal" tabindex="-1" aria-labelledby="jobOrderLogsModalLabel" aria-hidden="true" data-bs-backdrop="static" wire:ignore.self>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="jobOrderLogsModalLabel">Status Logs</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear2"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Field</th>
                                <th>Old Value</th>
                                <th>Updated Value</th>
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
                                <td class="text-capitalize">{{ $item->description }}</td>
                                <td>
                                    <ul>
                                        @foreach (array_keys(array_merge($oldAttributes, $updatedAttributes)) as $key)
                                        @if (!in_array($key, ['updated_at', 'created_at', 'deleted_at']))
                                        <li>{{ ucwords(str_replace('_', ' ', $key)) }}</li>
                                        @endif
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        @foreach (array_keys(array_merge($oldAttributes, $updatedAttributes)) as $key)
                                        @if ($key !== 'updated_at') {{-- Exclude updated_at --}}
                                        <li>{{ $oldAttributes[$key] ?? 'Not available' }}</li>
                                        @endif
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        @foreach (array_keys(array_merge($oldAttributes, $updatedAttributes)) as $key)
                                        @if ($key !== 'updated_at') {{-- Exclude updated_at --}}
                                        <li>{{ $updatedAttributes[$key] ?? 'Not available' }}</li>
                                        @endif
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        @foreach (array_keys(array_merge($oldAttributes, $updatedAttributes)) as $key)
                                        @if ($key === 'updated_at') {{-- Include only updated_at --}}
                                        <li>{{ \Carbon\Carbon::parse($updatedAttributes[$key])->format('M d, Y h:i A') ?? 'Not available' }}</li>
                                        @endif
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td>No logs.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear2">Close</button>
            </div>
        </div>
    </div>
</div>