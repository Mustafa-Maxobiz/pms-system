<x-app-layout>
    <div id="edit-sources" class="my-3 split">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow mb-4 mt-4">
                        <div class="card-header py-3 table-heading">
                            <h6>Edit Source</h6>
                        </div>
                        <form method="POST" action="{{ route('sources.update', $source->id) }}" style="padding: 30px;">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <!-- Source Name -->
                                <div class="col-md-12 mb-3">
                                    <label for="source_name" class="form-label">Source Name:</label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('source_name') is-invalid @enderror" 
                                        id="source_name" 
                                        name="source_name" 
                                        placeholder="Enter Source Name" 
                                        value="{{ old('source_name', $source->source_name) }}"
                                    >
                                    @error('source_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Source URL -->
                                <div class="col-md-12 mb-3">
                                    <label for="source_url" class="form-label">Source URL:</label>
                                    <input 
                                        type="url" 
                                        class="form-control @error('source_url') is-invalid @enderror" 
                                        id="source_url" 
                                        name="source_url" 
                                        placeholder="Enter Source URL" 
                                        value="{{ old('source_url', $source->source_url) }}"
                                    >
                                    @error('source_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <!-- Source Type -->
                                <div class="col-md-12 mb-3">
                                    <label for="source_type" class="form-label">Source Type:</label>
                                    <select 
                                        class="form-select @error('source_type') is-invalid @enderror" 
                                        id="source_type" 
                                        name="source_type"
                                    >
                                        <option value="" disabled>Select Source Type</option>
                                        <option value="direct" {{ old('source_type', $source->source_type) == 'direct' ? 'selected' : '' }}>Direct</option>
                                        <option value="platforms" {{ old('source_type', $source->source_type) == 'platforms' ? 'selected' : '' }}>Platforms</option>
                                        <option value="contest" {{ old('source_type', $source->source_type) == 'contest' ? 'selected' : '' }}>Contest</option>
                                    </select>
                                    @error('source_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <!-- Action Buttons -->
                                <div class="col-md-12 text-start">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                                    <a href="{{ route('sources.index') }}" class="btn btn-warning"><i class="fa fa-arrow-rotate-left"></i> Back</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>