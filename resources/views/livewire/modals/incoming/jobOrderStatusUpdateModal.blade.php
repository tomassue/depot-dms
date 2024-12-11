<!-- statusUpdate -->
<div class="modal fade" id="statusUpdate" tabindex="-1" aria-labelledby="statusUpdateLabel" aria-hidden="true" data-bs-backdrop="static" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="statusUpdateLabel">Status Update</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear3"></button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" wire:submit="updateJobOrder" novalidate>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="inputDateTime">Date & Time (Out)</label>
                            <div wire:ignore>
                                <input class="form-control date_and_time_out">
                            </div>
                            @error('date_and_time_out')
                            <div class="custom-invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group" style="display: {{ $ref_status_id == 2 ? '' : 'none' }}">
                        <div class="col-md-12">
                            <label for="inputTotalRepairTime">Total repair time</label>
                            <input type="text" class="form-control @error('total_repair_time') is-invalid @enderror" id="inputTotalRepairTime" wire:model="total_repair_time">
                            @error('total_repair_time')
                            <div class="custom-invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="inputClaimedBy">{{ $ref_status_id == 2 ? 'Claimed by' : 'Referred to' }}</label>
                            <input type="text" class="form-control @error('claimed_by') is-invalid @enderror" id="inputClaimedBy" wire:model="claimed_by" value="sadasdasd">
                            @error('claimed_by')
                            <div class="custom-invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="inputRemarks">Remarks</label>
                            <input type="text" class="form-control @error('remarks') is-invalid @enderror" id="inputRemarks" wire:model="remarks">
                            @error('remarks')
                            <div class="custom-invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear3">Close</button>
                <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>