<div>
    <div class="card registration-form-card">
        <div class="registration-form-heading">
            <div>
                <span class="registration-section-label">Student details</span>
                <h2>Personal information</h2>
            </div>
            <span class="registration-required-note"><span>*</span> Required fields</span>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form wire:submit="submit">
            <div class="mb-3">
                <label for="reg_no" class="form-label">Register No: </label>
                <input type="text" wire:model="reg_no" placeholder="Enter registration number"
                    class="form-control" id="reg_no">
                @error('reg_no')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Full Name: </label>
                <input type="text" wire:model="name" placeholder="Enter name with initials"
                    class="form-control" id="name">
                @error('name')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Enter phone number : </label>
                <input type="text" wire:model="phone" placeholder="Enter phone number"
                    class="form-control" id="phone">
                @error('phone')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" wire:model="email" class="form-control" id="email">
                @error('email')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Enter address: </label>
                <input type="text" wire:model="address" placeholder="Enter address" class="form-control"
                    id="address">
                @error('address')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="dob" class="form-label">Enter birth date: </label>
                <input type="date" wire:model="dob" class="form-control" id="dob">
                @error('dob')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Upload image: </label>
                <input type="file" wire:model="image" class="form-control" id="image"
                    accept="image/jpeg,image/png,image/gif,image/webp">
                <div wire:loading wire:target="image" class="form-text">Uploading...</div>
                @error('image')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror

                @if ($image && $image->isPreviewable())
                    <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="img-thumbnail mt-2"
                        style="max-height: 150px;">
                @endif
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" wire:model="password" class="form-control" id="password">
                @error('password')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="submit">
                <span wire:loading.remove wire:target="submit">Submit</span>
                <span wire:loading wire:target="submit">Saving...</span>
            </button>
        </form>
    </div>
</div>
