<x-app-layout>
    <div id="edit-user-status" class="mb-4 mt-4 split">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow mb-4 mt-4">
                        <div class="card-header py-3 table-heading">
                            <h6>Edit User Status</h6>
                        </div>
                        <form method="POST" action="{{ route('user-status.update', $userStatus->id) }}"
                            style="padding: 30px;">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="title" class="form-label">Title<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" placeholder="Title"
                                        value="{{ old('title', $userStatus->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="description" class="form-label">Description<span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        placeholder="Enter Description" rows="6" required>{{ old('description', $userStatus->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3 mt-4">
                                    <label for="order_by" class="form-label">Order By<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('order_by') is-invalid @enderror"
                                        id="order_by" name="order_by" placeholder="Order By"
                                        value="{{ old('order_by', $userStatus->order_by) }}" required>
                                    @error('order_by')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Icon Input -->
                                <div class="col-md-12 mb-3 mt-4">
                                    <label for="icon" class="form-label">Font Awesome Icon (e.g., "fas
                                        fa-laptop-code")</label>
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror"
                                        id="icon" name="icon" placeholder="Enter Font Awesome Icon"
                                        value="{{ old('icon', $userStatus->icon) }}">
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-start">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i>
                                        Update</button>
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
