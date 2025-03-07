<x-app-layout>
    <div id="edit-knowledge-base" class="my-3 split">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow mb-4 mt-4">
                        <div class="card-header py-3 table-heading">
                            <h6>Edit Knowledge Base</h6>
                        </div>
                        <form method="POST" action="{{ route('knowledge-base.update', $knowledgeBase->id) }}" enctype="multipart/form-data" style="padding: 30px;">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Title -->
                                <div class="col-md-12 mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input
                                        type="text"
                                        class="form-control @error('title') is-invalid @enderror"
                                        id="title"
                                        name="title"
                                        placeholder="Enter Title"
                                        value="{{ old('title', $knowledgeBase->title) }}"
                                        required>
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
                                        rows="6"
                                        placeholder="Enter Content"
                                        required
                                    >{{ old('content', $knowledgeBase->content) }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Department -->
                                <div class="col-md-12 my-3">
                                    <label for="department_id" class="form-label">Department</label>
                                    <select
                                        class="form-control select2 @error('department_id') is-invalid @enderror"
                                        id="department_id"
                                        name="department_id"
                                    >
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                            <option
                                                value="{{ $department->id }}"
                                                {{ old('department_id', $knowledgeBase->department_id) == $department->id ? 'selected' : '' }}
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
                                        value="{{ old('tags', is_array($knowledgeBase->tags) ? implode(',', $knowledgeBase->tags) : $knowledgeBase->tags) }}"

                                    >
                                    @error('tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Attachments -->
                                <div class="col-md-12 mb-3">
                                    <label for="attachments" class="form-label">Attachments</label>
                                    <input type="file" class="form-control" id="attachments" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.zip">

                                    @php
                                        $attachments = json_decode($knowledgeBase->attachments, true) ?? [];
                                    @endphp

                                    @if (!empty($attachments))
                                        <div class="mt-3">
                                            <label class="form-label">Existing Attachments:</label>
                                            <ul>
                                                @foreach ($attachments as $index => $attachment)
                                                    <li>
                                                        <a href="{{ asset('storage/app/public/' . $attachment['path']) }}" target="_blank">{{ $attachment['original_name'] ?? basename($attachment['path']) }}</a>
                                                        <a href="javascript:void(0);"
                                                           onclick="event.preventDefault(); deleteAttachment({{ $index }});"
                                                           class="btn btn-warning btn-sm p-1">Delete</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="col-md-12 text-start">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                                    <a href="{{ route('knowledge-base.index') }}" class="btn btn-warning"><i class="fa fa-arrow-rotate-left"></i> Back</a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($attachments as $index => $attachment)
        <form
            id="delete-form-{{ $index }}"
            action="{{ route('knowledge-base.delete-attachment', ['id' => $knowledgeBase->id]) }}"
            method="POST"
            style="display: none;">
            @csrf
            @method('DELETE')
            <input type="hidden" name="attachment_index" value="{{ $index }}">
        </form>
    @endforeach

    @section('scripts')
    <script>
        function deleteAttachment(index) {
            if (confirm('Are you sure you want to delete this attachment?')) {
                document.getElementById('delete-form-' + index).submit();
            }
        }
    </script>
   @endsection
</x-app-layout>
