<x-app-layout>
    <div id="edit-settings" class="mb-4 mt-4 split">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow mb-4 mt-4">
                        <div class="card-header py-3 table-heading">
                            <h6>Settings</h6>
                        </div>
                        <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" style="padding: 30px;">
                            @csrf
                            @method('POST')
                            
                            <!-- Site Name Field -->
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('name') is-invalid @enderror" 
                                        id="name" 
                                        name="name"
                                        placeholder="Site Name" 
                                        value="{{ old('name', $setting->name) }}" 
                                    >
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Logo Field -->
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="logo" class="form-label">Logo</label>
                                    <label for="logo" class="form-label">Logo</label>
                                    <input 
                                        type="file" 
                                        class="form-control @error('logo') is-invalid @enderror" 
                                        id="logo" 
                                        name="logo"
                                    >
                                    @error('logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($setting->logo)
                                        <img src="{{ asset('storage/app/public/' . $setting->logo) }}" alt="Logo" width="100" class="mt-3">
                                    @endif
                                </div>
                            </div>

                            <!-- Email Field -->
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input 
                                        type="email" 
                                        class="form-control @error('email') is-invalid @enderror" 
                                        id="email" 
                                        name="email"
                                        placeholder="Email" 
                                        value="{{ old('email', $setting->email) }}"
                                    >
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Phone Field -->
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('phone') is-invalid @enderror" 
                                        id="phone" 
                                        name="phone"
                                        placeholder="Phone" 
                                        value="{{ old('phone', $setting->phone) }}"
                                    >
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Copyright Field -->
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="copyright" class="form-label">Copyright</label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('copyright') is-invalid @enderror" 
                                        id="copyright" 
                                        name="copyright"
                                        placeholder="Copyright Information" 
                                        value="{{ old('copyright', $setting->copyright) }}"
                                    >
                                    @error('copyright')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Other Info Field -->
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="other_info" class="form-label">Other Info</label>
                                    <textarea 
                                        class="form-control @error('other_info') is-invalid @enderror" 
                                        id="other_info" 
                                        name="other_info"
                                        placeholder="Other Information"
                                    >{{ old('other_info', $setting->other_info) }}</textarea>
                                    @error('other_info')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- GST -->
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="gst" class="form-label">GST</label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('gst') is-invalid @enderror" 
                                        id="gst" 
                                        name="gst"
                                        placeholder="GST" 
                                        value="{{ old('gst', $setting->gst) }}" 
                                    >
                                    @error('gst')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="col-md-12 text-start">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update Settings</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>