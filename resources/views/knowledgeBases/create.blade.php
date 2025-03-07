<x-app-layout>
    <div id="add-knowledge-bases" class="my-3 split">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow mb-4 mt-4">
                        <div class="card-header py-3 table-heading">
                            <h6>Add Knowledge Base</h6>
                        </div>
                        <form method="POST" action="{{ route('knowledge-base.store') }}" enctype="multipart/form-data" style="padding: 30px;">
                            @csrf

                            <div class="row">
                                <!-- Title -->
                                <div class="col-md-12 mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input
                                        type="text"
                                        class="form-control @error('title') is-invalid @enderror"
                                        id="title"
                                        name="title"
                                        placeholder="Enter Knowledge Base Title"
                                        value="{{ old('title') }}"
                                    >
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Content -->
                                <div class="col-md-12 mb-3">
                                    <label for="content" class="form-label">Content</label>
                                    <textarea
                                        class="form-control @error('content') is-invalid @enderror"
                                        id="content"
                                        name="content"
                                        placeholder="Enter Knowledge Base Content"
                                        rows="6"
                                    >{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Department -->
                                <div class="col-md-12 my-3">
                                    <label for="department_id" class="form-label">Department</label>
                                    <select
                                        class="form-control select2 form-select @error('department_id') is-invalid @enderror"
                                        id="department_id"
                                        name="department_id"
                                    >
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                            <option
                                                value="{{ $department->id }}"
                                                {{ old('department_id') == $department->id ? 'selected' : '' }}
                                            >
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Tags -->
                                <div class="col-md-12 mb-3">
                                    <label for="tags" class="form-label">Tags</label>
                                    <input
                                        type="text"
                                        class="form-control @error('tags') is-invalid @enderror"
                                        id="tags"
                                        name="tags"
                                        placeholder="Enter tags, separated by commas"
                                        value="{{ old('tags') }}"
                                    >
                                    @error('tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Attachments -->
                                <div class="col-md-12 mb-3">
                                    <label for="attachments" class="form-label">Attachments</label>
                                    <input
                                        type="file"
                                        class="form-control @error('attachments') is-invalid @enderror"
                                        id="attachments"
                                        name="attachments[]"
                                        multiple
                                        accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.zip"
                                    >
                                    @error('attachments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Action Buttons -->
                                <div class="col-md-12 text-start">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                                    <a href="{{ route('knowledge-base.index') }}" class="btn btn-warning"><i class="fa fa-arrow-rotate-left"></i> Back</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
