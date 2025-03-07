<x-app-layout>
    <div id="edit-client" class="mb-4 mt-4 split">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow mb-4 mt-4">
                        <div class="card-header py-3 table-heading">
                            <h6>Edit Client</h6>
                        </div>
                        <form method="POST" action="{{ route('clients.update', $client->id) }}" style="padding: 30px;">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="client_name"  class="form-label">Client Name:</label>
                                    <input
                                        type="text"
                                        class="form-control @error('client_name') is-invalid @enderror"
                                        id="client_name"
                                        name="client_name"
                                        placeholder="Client Name"
                                        value="{{ old('client_name', $client->client_name) }}"
                                    >
                                    @error('client_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="client_username" class="form-label">Client Username:</label>
                                    <input
                                        type="text"
                                        class="form-control @error('client_username') is-invalid @enderror"
                                        id="client_username"
                                        name="client_username"
                                        placeholder="Client Username"
                                        value="{{ old('client_username', $client->client_username) }}"
                                    >
                                    @error('client_username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3"  class="form-label">
                                    <label for="source_id" class="form-label">SOURCE:</label>
                                    <select class="form-control form-select select2 @error('source') is-invalid @enderror" id="source_id" name="source_id">
                                        <option value="" {{ old('source_id') === '' ? 'selected' : '' }}>Select Source</option>
                                        @foreach ($sources as $source)
                                            <option value="{{ $source->id }}" 
                                            {{ old('source_id', $client->source_id) == $source->id ? 'selected' : '' }}
                                            >
                                                {{ $source->sourceName }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('source')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="client_phone" class="form-label">Client Phone:</label>
                                    <input
                                        type="text"
                                        class="form-control @error('client_phone') is-invalid @enderror"
                                        id="client_phone"
                                        name="client_phone"
                                        placeholder="Client Phone"
                                        value="{{ old('client_phone', $client->client_phone) }}"
                                    >
                                    @error('client_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="client_mobile" class="form-label">Client Mobile:</label>
                                    <input
                                        type="text"
                                        class="form-control @error('client_mobile') is-invalid @enderror"
                                        id="client_mobile"
                                        name="client_mobile"
                                        placeholder="Client Mobile"
                                        value="{{ old('client_mobile', $client->client_mobile) }}"
                                    >
                                    @error('client_mobile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="client_email" class="form-label">Client Email:</label>
                                    <input
                                        type="email"
                                        class="form-control @error('client_email') is-invalid @enderror"
                                        id="client_email"
                                        name="client_email"
                                        placeholder="Client Email"
                                        value="{{ old('client_email', $client->client_email) }}"
                                    >
                                    @error('client_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="meeting" class="form-label">Meeting:</label>
                                    <input
                                        type="text"
                                        class="form-control @error('meeting') is-invalid @enderror"
                                        id="meeting"
                                        name="meeting"
                                        placeholder="Meeting"
                                        value="{{ old('meeting', $client->meeting) }}"
                                    >
                                    @error('meeting')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="client_country">Client Country:</label>
                                    <input
                                        type="text"
                                        class="form-control @error('client_country') is-invalid @enderror"
                                        id="client_country"
                                        name="client_country"
                                        placeholder="Client Country"
                                        value="{{ old('client_country', $client->client_country) }}"
                                    >
                                    @error('client_country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="address">Address:</label>
                                    <input
                                        type="text"
                                        class="form-control @error('address') is-invalid @enderror"
                                        id="address"
                                        name="address"
                                        placeholder="Address"
                                        value="{{ old('address', $client->address) }}"
                                    >
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-start">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                                    <a href="{{ route('clients.index') }}" class="btn btn-warning"><i class="fa fa-arrow-rotate-left"></i> Back</a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
