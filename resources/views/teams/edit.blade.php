<x-app-layout>
    <div id="edit-teams" class="my-3 split">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow mb-4 mt-4">
                        <div class="card-header py-3 table-heading">
                            <h6>Edit Team</h6>
                        </div>
                        <form method="POST" action="{{ route('teams.update', $team->id) }}" style="padding: 30px;">
                            @csrf
                            @method('PUT')
                            
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
                                        value="{{ old('name', $team->name) }}"
                                    >
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Department -->
                                <div class="col-md-12 mb-3">
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
                                                {{ old('department_id', $team->department_id) == $department->id ? 'selected' : '' }}
                                            >
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
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
                                    >{{ old('description', $team->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row mt-4">
                                <!-- Action Buttons -->
                                <div class="col-md-12 text-start mt-4">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                                    <a href="{{ route('teams.index') }}" class="btn btn-warning"><i class="fa fa-arrow-rotate-left"></i> Back</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>