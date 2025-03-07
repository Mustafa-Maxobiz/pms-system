<x-app-layout>
    <div id="add-departments" class="my-3 split">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow mb-4 mt-4">
                        <div class="card-header py-3 table-heading">
                            <h6>Add Department</h6>
                        </div>
                        <form method="POST" action="{{ route('departments.store') }}" style="padding: 30px;">
                            @csrf

                            <div class="row">
                                <!-- Name -->
                                <div class="col-md-12 mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input
                                        type="text"
                                        class="form-control @error('name') is-invalid @enderror"
                                        id="name"
                                        name="name"
                                        placeholder="Enter Name"
                                        value="{{ old('name') }}"
                                    >
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="col-md-12 mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea
                                        class="form-control @error('description') is-invalid @enderror"
                                        id="description"
                                        name="description"
                                        placeholder="Enter Description"
                                        rows="6"
                                    >{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mt-4">
                                <!-- Action Buttons -->
                                <div class="col-md-12 mt-4 text-start">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                                    <a href="{{ route('departments.index') }}" class="btn btn-warning"><i class="fa fa-arrow-rotate-left"></i> Back</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
