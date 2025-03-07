<x-app-layout>
    <div id="edit-task-priorities" class="mb-4 mt-4 split">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow mb-4 mt-4">
                        <div class="card-header py-3 table-heading">
                            <h6>Edit Task Priority</h6>
                        </div>
                        <form method="POST" action="{{ route('task-priorities.update', $taskPriority->id) }}" style="padding: 30px;">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="order_by" class="form-label">Title<span class="text-danger">*</span></label>
                                    <input
                                        type="text"
                                        class="form-control @error('title') is-invalid @enderror"
                                        id="title"
                                        name="title"
                                        placeholder="Title"
                                        value="{{ old('title', $taskPriority->title) }}"
                                    required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="description" class="form-label">Description<span class="text-danger">*</span></label>
                                    <textarea
                                        class="form-control"
                                        id="description"
                                        name="description"
                                        placeholder="Enter Description"
                                        rows="6"
                                    required>{{ old('description', $taskPriority->description) }}</textarea>
                                </div>
                                <div class="col-md-12 mb-3 mt-4">
                                    <label for="order_by" class="form-label">Order By<span class="text-danger">*</span></label>
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="order_by"
                                        name="order_by"
                                        placeholder="Order By"
                                        value="{{ old('order_by', $taskPriority->order_by) }}"
                                    required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-start">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                                    <a href="{{ route('task-priorities.index') }}" class="btn btn-warning"><i class="fa fa-arrow-rotate-left"></i> Back</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
