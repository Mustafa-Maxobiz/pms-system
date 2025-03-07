<x-app-layout>
    <div id="add-user-status" class="my-3 split">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow mb-4 mt-4">
                        <div class="card-header py-3 table-heading">
                            <h6>Add User Status</h6>
                        </div>
                        <form method="POST" action="{{ route('user-status.store') }}" style="padding: 30px;">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="title" class="form-label">Title<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" placeholder="Title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="description" class="form-label">Description<span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        placeholder="Enter Description" required rows="6">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3 mt-4">
                                    <label for="order_by" class="form-label">Order By<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('order_by') is-invalid @enderror"
                                        id="order_by" name="order_by" placeholder="Order By"
                                        value="{{ old('order_by') }}" required>
                                    @error('order_by')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Font Awesome Icon Class Input -->
                                <div class="col-md-12 mb-3">
                                    <label for="icon" class="form-label">Font Awesome Icon (e.g.,
                                        fa-laptop-code)</label>
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror"
                                        id="icon" name="icon"
                                        placeholder="Enter Font Awesome icon class (e.g., fa-laptop-code)"
                                        value="{{ old('icon') }}">
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-12 text-start">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i>
                                        Save</button>
                                    <a href="{{ route('user-status.index') }}" class="btn btn-warning"><i
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
