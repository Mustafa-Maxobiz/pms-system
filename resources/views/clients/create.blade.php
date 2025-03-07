<x-app-layout>
    <div id="add-clients" class="my-3 split">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow mb-4 mt-4">
                        <div class="card-header py-3 table-heading">
                            <h6>Client Management</h6>
                        </div>
                        <form method="POST" action="{{ route('clients.store') }}" style="padding: 30px;">
                            @csrf

                            <div class="row">
                                <!-- Client Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="client_name"  class="form-label">Client Name:</label>
                                    <input type="text" class="form-control @error('client_name') is-invalid @enderror"
                                        id="client_name" name="client_name" placeholder="Client Name"
                                        value="{{ old('client_name') }}">
                                    @error('client_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Client Username -->
                                <div class="col-md-6 mb-3">
                                    <label for="client_username"  class="form-label">Client Username:</label>
                                    <input type="text"
                                        class="form-control @error('client_username') is-invalid @enderror"
                                        id="client_username" name="client_username" placeholder="Client Username"
                                        value="{{ old('client_username') }}">
                                    @error('client_username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Source -->
                                <div class="col-md-6 mb-3">
                                    <label for="source_id" class="form-label">SOURCE:</label>
                                    <select class="form-control form-select select2 @error('source_id') is-invalid @enderror" id="source_id" name="source_id">
                                        <option value="" {{ old('source_id') === '' ? 'selected' : '' }}>Select Source</option>
                                        @foreach ($sources as $source)
                                            <option value="{{ $source->id }}" {{ old('source_id') == $source->id ? 'selected' : '' }}>
                                                {{ $source->source_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('source_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Client Phone -->
                                <div class="col-md-6 mb-3">
                                    <label for="client_phone"  class="form-label">Client Phone:</label>
                                    <input type="text"
                                        class="form-control @error('client_phone') is-invalid @enderror" id="client_phone"
                                        name="client_phone" placeholder="Client Phone" value="{{ old('client_phone') }}">
                                    @error('client_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Client Mobile -->
                                <div class="col-md-6 mb-3">
                                    <label for="client_mobile"  class="form-label">Client Mobile:</label>
                                    <input type="text"
                                        class="form-control @error('client_mobile') is-invalid @enderror"
                                        id="client_mobile" name="client_mobile" placeholder="Client Mobile"
                                        value="{{ old('client_mobile') }}">
                                    @error('client_mobile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Client Email -->
                                <div class="col-md-6 mb-3">
                                    <label for="client_email"  class="form-label">Client Email:</label>
                                    <input type="email"
                                        class="form-control @error('client_email') is-invalid @enderror" id="client_email"
                                        name="client_email" placeholder="Client Email" value="{{ old('client_email') }}">
                                    @error('client_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Meeting -->
                                <div class="col-md-6 mb-3">
                                    <label for="meeting"  class="form-label">Meeting:</label>
                                    <input type="text" class="form-control @error('meeting') is-invalid @enderror"
                                        id="meeting" name="meeting" placeholder="Meeting"
                                        value="{{ old('meeting') }}">
                                    @error('meeting')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Client Country -->
                                <div class="col-md-6 mb-3">
                                    <label for="client_country"  class="form-label">Client Country:</label>
                                    <input type="text"
                                        class="form-control @error('client_country') is-invalid @enderror"
                                        id="client_country" name="client_country" placeholder="Client Country"
                                        value="{{ old('client_country') }}">
                                    @error('client_country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Address -->
                                <div class="col-md-12 mb-3">
                                    <label for="address"  class="form-label">Address:</label>
                                    <input class="form-control @error('address') is-invalid @enderror" id="address" name="address"
                                        placeholder="Address">{{ old('address') }}</input>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-12 text-start">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i>
                                        Save</button>
                                    <a href="{{ route('clients.index') }}" class="btn btn-warning"><i
                                            class="fa fa-arrow-rotate-left"></i> Back</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
