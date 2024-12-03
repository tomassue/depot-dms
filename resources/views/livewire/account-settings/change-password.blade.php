<div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Account Settings</h4>
            <p class="card-description">
                Change Password
            </p>
            <form class="forms-sample" wire:submit="updatePassword">
                <div class="form-group row">
                    <label for="inputOldPassword" class="col-sm-3 col-form-label">Old Password</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" id="inputOldPassword" placeholder="Old Password" wire:model="oldPassword">
                        @error('oldPassword')
                        <div class="custom-invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputNewPassword" class="col-sm-3 col-form-label">New Password</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" id="inputNewPassword" placeholder="New Password" wire:model="newPassword">
                        @error('newPassword')
                        <div class="custom-invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputConfirmPassword" class="col-sm-3 col-form-label">Re Password</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" id="inputConfirmPassword" placeholder="Password" wire:model="confirmPassword">
                        @error('confirmPassword')
                        <div class="custom-invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mr-2">Submit</button>
                <button class="btn btn-light" wire:click="clear">Clear</button>
            </form>
        </div>
    </div>
</div>