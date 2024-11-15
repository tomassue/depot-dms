<div>
    <div class="row">
        <div class="card">
            @can('create incoming')
            <div class="col-md-12 my-2 d-inline-flex align-content-center justify-content-end">
                <button class="btn btn-primary btn-md btn-icon-text" wire:click="$dispatch('showLocationModal')"> Add <i class="typcn typcn-plus-outline btn-icon-append"></i></button>
            </div>
            @endcan
            <div class="col-md-12 my-2">
                <div id="table_incomings" wire:ignore></div>
            </div>
        </div>
    </div>

    <!-- incomingModal -->
    <div class="modal fade" id="incomingModal" tabindex="-1" aria-labelledby="incomingModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="incomingModalLabel">{{ $editMode ? 'Update' : 'Add' }} Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" wire:submit="{{ $editMode ? 'updateLocation' : 'createLocation' }}" novalidate>
                        <div class="form-group">
                            <label for="inputLocation">Location</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" id="inputLocation" placeholder="Location" wire:model="location">
                            @error('location')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear">Close</button>
                    <button type="submit" class="btn btn-primary">{{ $editMode ? 'Update' : 'Save' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>