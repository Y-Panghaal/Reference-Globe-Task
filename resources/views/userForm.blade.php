<form class="row g-3 w-50" autocomplete="off" method="POST" action="{{ $route }}" enctype="multipart/form-data">
    @csrf
    @if(isset($method))
    @method($method)
    @endif
    <div class="col-md-4">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control {{ $errors->first('name') ? 'is-invalid' : '' }}" id="name" name="name" value="{{ old('name') }}" placeholder="First-name Last-name" autocomplete="off" {{(!isset($required) || $required !== false) ? 'required' : ''}}>
        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
    </div>
    <div class="col-md-4">
        <label for="mobile" class="form-label">Mobile</label>
        <input type="number" class="form-control {{ $errors->first('mobile') ? 'is-invalid' : '' }}" id="mobile" name="mobile" value="{{ old('mobile') }}" placeholder="1234567890" {{(!isset($required) || $required !== false) ? 'required' : ''}}>
        <div class="invalid-feedback">{{ $errors->first('mobile') }}</div>
    </div>
    <div class="col-md-4">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" class="form-control {{ $errors->first('email') ? 'is-invalid' : '' }}" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" autocomplete="off" {{(!isset($required) || $required !== false) ? 'required' : ''}}>
        <div class="invalid-feedback">{{ $errors->first('email') }}</div>
    </div>
    <div class="col-md-6">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control {{ $errors->first('password') ? 'is-invalid' : '' }}" id="password" name="password" value="" autocomplete="new-password" {{(!isset($required) || $required !== false) ? 'required' : ''}}>
        <div class="invalid-feedback">{{ $errors->first('password') }}</div>
    </div>
    <div class="col-md-6">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <input type="password" class="form-control {{ $errors->first('password_confirmation') ? 'is-invalid' : '' }}" id="password_confirmation" name="password_confirmation" value="" {{(!isset($required) || $required !== false) ? 'required' : ''}}>
        <div class="invalid-feedback">{{ $errors->first('password_confirmation') }}</div>
    </div>
    <div class="col-md-12">
        <label for="address" class="form-label">Address</label>
        <textarea class="form-control {{ $errors->first('address') ? 'is-invalid' : '' }}" id="address" name="address" style="resize:none;">{{ old('address') }}</textarea>
        <div class="invalid-feedback">{{ $errors->first('address') }}</div>
    </div>
    <div class="col-md-6">
        <label for="gender" class="form-label">Gender</label>
        <select class="form-select {{ $errors->first('password_confirmation') ? 'is-invalid' : '' }}" id="gender" name="gender" aria-describedby="genderFeedback" {{(!isset($required) || $required !== false) ? 'required' : ''}}>
            <option value="" disabled {{ !old('gender') ? 'selected' : '' }}>Choose...</option>
            <option value="0" {{ old('gender') == 0 ? 'selected' : '' }}>Male</option>
            <option value="1" {{ old('gender') == 1 ? 'selected' : '' }}>Female</option>
        </select>
        <div class="invalid-feedback" id="genderFeedback">{{ $errors->first('password_confirmation') }}</div>
    </div>
    <div class="col-md-6">
        <label for="date_of_birth" class="form-label">Date of Birth</label>
        <input type="date" class="form-control {{ $errors->first('date_of_birth') ? 'is-invalid' : '' }}" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" max="{{ now()->subYears(18)->format('Y-m-d') }}" {{(!isset($required) || $required !== false) ? 'required' : ''}}>
        <div class="invalid-feedback">{{ $errors->first('date_of_birth') }}</div>
    </div>
    <div class="col-md-6">
        <label for="profile_picture" class="form-label">Profile Picture</label>
        <input type="file" class="form-control {{ $errors->first('profile_picture') ? 'is-invalid' : '' }}" id="profile_picture" name="profile_picture" value="" {{(!isset($required) || $required !== false) ? 'required' : ''}}>
        <div class="invalid-feedback">{{ $errors->first('profile_picture') }}</div>
    </div>
    <div class="col-md-6">
        <label for="signature" class="form-label">Signature</label>
        <input type="file" class="form-control {{ $errors->first('signature') ? 'is-invalid' : '' }}" id="signature" name="signature" value="" {{(!isset($required) || $required !== false) ? 'required' : ''}}>
        <div class="invalid-feedback">{{ $errors->first('signature') }}</div>
    </div>
    <div class="col-12">
        <button class="btn btn-primary" type="{{(isset($submitButton) && $submitButton === 'button') ? 'button' : 'submit'}}" id=submitButton>Submit form</button>
    </div>
</form>