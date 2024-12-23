<!-- jobOrderModal -->
<div class="modal fade" id="jobOrderModal" tabindex="-1" aria-labelledby="jobOrderModalLabel" aria-hidden="true" data-bs-backdrop="static" wire:ignore.self>
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
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
                    <div class="form-group row" style="display: {{ $ref_incoming_request_types_id === '1' ? '' : 'none' }}">
                        <div class="col-md-6">
                            <label for="inputMileage">Mileage / Odometer Reading</label>
                            <input type="text" class="form-control @error('mileage') is-invalid @enderror" id="inputMileage" oninput="this.value = this.value.replace(/[^0-9]/g, '')" wire:model="mileage">
                            @error('mileage')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="inputPersonInCharge">{{ $ref_incoming_request_types_id == '1' ? 'Driver' : 'Person' }} in charge</label>
                            <input type="text" class="form-control @error('person_in_charge') is-invalid @enderror" id="inputPersonInCharge" wire:model="person_in_charge">
                            @error('person_in_charge')
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
                    <div class="form-group row" style="display: {{ $editMode ? '' : 'none' }}">
                        <div class="col-md-12">
                            <label for="inputFindings">Findings</label>
                            <div wire:ignore>
                                <div id="findings-summernote"></div>
                            </div>
                            @error('findings')
                            <div class="custom-invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="files">Files <small class="text-info fst-italic">* Only .jpg, .png, and .pdf formats are accepted.</small></label>
                            <div wire:ignore>
                                <input type="file" class="form-control my-pond-files" multiple data-allow-reorder="true">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row" style="display: {{ $editMode ? '' : 'none' }}">
                        <div class="col-md-12">
                            <label for="files">Uploaded Files</small></label>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            #
                                        </th>
                                        <th>
                                            File Name
                                        </th>
                                        <th>
                                            File Type
                                        </th>
                                        <th>
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($previewFiles as $index=>$item)
                                    <tr>
                                        <td>
                                            {{ $index+1 }}
                                        </td>
                                        <td>
                                            {{ $item->name }}
                                        </td>
                                        <td>
                                            {{ $item->type }}
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-info" role="button" title="View File" wire:click="viewFile('{{ $item->id }}')">
                                                <i class='bx bx-file bx-sm'></i>
                                            </a>
                                            <a class="btn btn-sm btn-danger" role="button" title="Remove File" wire:click="$dispatch('confirm-file-deletion', {{ $item->id }})">
                                                <i class="bx bx-trash bx-sm"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No attachments found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
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