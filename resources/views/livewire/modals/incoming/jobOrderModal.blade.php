<!-- jobOrderModal -->
<div class="modal fade" id="jobOrderModal" tabindex="-1" aria-labelledby="jobOrderModalLabel" aria-hidden="true" data-bs-backdrop="static" wire:ignore.self>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="jobOrderModalLabel">{{ $editMode ? 'Update' : 'Add' }} Request Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear2"></button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" wire:submit="{{ $editMode ? 'updateJobOrder' : 'createJobOrder' }}">
                    <p class="card-description">
                        Request Details
                    </p>
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="inputType">Type</label>
                            <input type="text" class="form-control disabled_input" id="inputType" wire:model="ref_types_id">
                        </div>
                        <div class="col-md-6">
                            <label for="inputModel">Model</label>
                            <input type="text" class="form-control disabled_input" id="inputModel" wire:model="ref_models_id_2">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="inputNumber">Number</label>
                            <input type="text" class="form-control disabled_input" id="inputNumber" wire:model="number">
                        </div>
                        <div class="col-md-6">
                            <label for="inputMileage">Mileage / Odometer Reading</label>
                            <input type="text" class="form-control disabled_input" id="inputMileage" wire:model="mileage">
                        </div>
                    </div>
                    <p class="card-description">
                        Equipment Details
                    </p>
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="inputJobOrder">Job Order</label>
                            <input type="text" class="form-control disabled_input" id="inputJobOrder" wire:model="job_order_no">
                        </div>
                        <div class="col-md-6" style="display: {{ $editMode ? '' : 'none'}}">
                            <label for="selectModel">Status</label>
                            <div id="status-select" wire:ignore></div>
                            @error('ref_status_id')
                            <div class="custom-invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="inputDriverInCharge">Driver in charge</label>
                            <input type="text" class="form-control @error('driver_in_charge') is-invalid @enderror" id="inputDriverInCharge" wire:model="driver_in_charge">
                            @error('driver_in_charge')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="inputContactNumber">Contact Number</label>
                            <input type="text" class="form-control @error('contact_number') is-invalid @enderror" data-ddg-inputtype="identities.contactNumber" id="inputContactNumber" maxlength="11" oninput="this.value = '09' + this.value.slice(2).replace(/\D/g, '');" placeholder="09XXXXXXXXX" wire:model="contact_number">
                            @error('contact_number')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="selectCategory">Category</label>
                            <div id="category-select" wire:ignore></div>
                            @error('ref_category_id')
                            <div class="custom-invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="selectTypeOfRepair">Type of Repair</label>
                            <div id="type-of-repair-select" wire:ignore></div>
                            @error('ref_type_of_repair_id')
                            <div class="custom-invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="selectSubCategory">Sub-category</label>
                            <div id="sub-category-select" wire:ignore></div>
                            @error('ref_sub_category_id')
                            <div class="custom-invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="selectMechanics">Mechanics Assigned</label>
                            <div id="mechanics-select" wire:ignore></div>
                            @error('ref_mechanics')
                            <div class="custom-invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="selectLocation">Location</label>
                            <div id="location-select" wire:ignore></div>
                            @error('ref_location_id')
                            <div class="custom-invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="inputDateAndTimeIn">Date & Time (In)</label>
                            <div wire:ignore>
                                <input class="form-control date_and_time_in">
                            </div>
                            @error('date_and_time_in')
                            <div class="custom-invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="inputIssueOrConcern">Issues or Concerns</label>
                            <div wire:ignore>
                                <div id="issue-or-concern-summernote"></div>
                            </div>
                            @error('issue_or_concern')
                            <div class="custom-invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear2">Close</button>
                <button type="submit" class="btn btn-primary">{{ $editMode ? 'Update' : 'Save' }}</button>
                </form>
            </div>
        </div>
    </div>
</div>