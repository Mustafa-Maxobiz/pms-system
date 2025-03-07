<x-app-layout>
    <div id="add-projects" class="my-3 split">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow mb-4 mt-4">
                        <div class="card-header py-3 table-heading">
                            <h6>Add Project</h6>
                        </div>
                        <form method="POST" action="{{ route('projects.store') }}" style="padding: 30px;">
                            @csrf
                            <div class="row">
                                <!-- Project ID -->
                                <div class="col-md-6 mb-3 d-none">
                                <label for="project_id" class="form-label">PROJECT ID:</label>
                                <input 
                                        type="text" 
                                        class="form-control @error('project_id') is-invalid @enderror" 
                                        id="project_id" 
                                        name="project_id" 
                                        placeholder="Project ID" 
                                        value="{{ old('project_id') }}"
                                    >
                                    @error('project_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Project MainId -->
                                <div class="col-md-6 mb-3">
                                <label for="mainid" class="form-label">PROJECT MAINID:</label>
                                <input 
                                        type="text" 
                                        class="form-control @error('mainid') is-invalid @enderror" 
                                        id="mainid" 
                                        name="mainid" 
                                        placeholder="Project MainID" 
                                        value="{{ old('mainid') }}"
                                    >
                                    @error('mainid')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Project Name -->
                                <div class="col-md-6 mb-3">
                                <label for="project_name" class="form-label">PROJECT NAME:</label>
                                <input 
                                        type="text" 
                                        class="form-control @error('project_name') is-invalid @enderror" 
                                        id="project_name" 
                                        name="project_name" 
                                        placeholder="Project Name" 
                                        value="{{ old('project_name') }}"
                                    >
                                    @error('project_name')
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

                                <!-- Client Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="client_id" class="form-label">CLIENT NAME:</label>
                                    <select class="form-control form-select select2 @error('client_id') is-invalid @enderror" id="client_id" name="client_id">
                                        <option value="" {{ old('client_id') === '' ? 'selected' : '' }}>Select Client</option>
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                                {{ $client->client_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- URL -->
                                <div class="col-md-6 mb-3">
                                    <label for="url" class="form-label">URL:</label>
                                    <input 
                                        type="url" 
                                        class="form-control @error('url') is-invalid @enderror" 
                                        id="url" 
                                        name="url" 
                                        placeholder="URL" 
                                        value="{{ old('url') }}"
                                    >
                                    @error('url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- External Status -->
                                <div class="col-md-6 mb-3">
                                    <label for="external_status" class="form-label">EXTERNAL STATUS:</label>
                                    <select class="form-control form-select select2 @error('external_status') is-invalid @enderror" id="external_status" name="external_status">
                                        <option value="" {{ old('external_status') === '' ? 'selected' : '' }}>Select External Status</option>
                                        @foreach ($externalStatus as $externalS)
                                            <option value="{{ $externalS->id }}" {{ old('external_status') == $externalS->id ? 'selected' : '' }}>
                                                {{ $externalS->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('external_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Total Amount -->
                                <div class="col-md-6 mb-3">
                                    <label for="total_amount" class="form-label">TOTAL AMOUNT:</label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('total_amount') is-invalid @enderror" 
                                        id="total_amount" 
                                        name="total_amount" 
                                        placeholder="Total Amount" 
                                        value="{{ old('total_amount') }}"
                                    >
                                    @error('total_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Start Date -->
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label">START DATE:</label>
                                    <input 
                                        type="date" 
                                        class="form-control @error('start_date') is-invalid @enderror" 
                                        id="start_date" 
                                        name="start_date" 
                                        value="{{ old('start_date', date('Y-m-d')) }}"
                                    >
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Target Date -->
                                <div class="col-md-6 mb-3">
                                    <label for="target_date" class="form-label">TARGET DATE:</label>
                                    <input 
                                        type="date" 
                                        class="form-control @error('target_date') is-invalid @enderror" 
                                        id="target_date" 
                                        name="target_date" 
                                        value="{{ old('target_date') }}"
                                    >
                                    @error('target_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Completion Date -->
                                <div class="col-md-6 mb-3">
                                    <label for="completion_date" class="form-label">COMPLETION DATE:</label>
                                    <input 
                                        type="date" 
                                        class="form-control @error('completion_date') is-invalid @enderror" 
                                        id="completion_date" 
                                        name="completion_date" 
                                        value="{{ old('completion_date') }}"
                                    >
                                    @error('completion_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Project Alerts -->
                                <div class="col-md-6 mb-3">
                                    <label for="project_alerts" class="form-label">PROJECT ALERTS:</label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('project_alerts') is-invalid @enderror" 
                                        id="project_alerts" 
                                        name="project_alerts" 
                                        placeholder="Project Alerts" 
                                        value="{{ old('project_alerts') }}"
                                    >
                                    @error('project_alerts')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Final Feedback -->
                                <div class="col-md-6 mb-3">
                                    <label for="final_feedback" class="form-label">FINAL FEEDBACK:</label>
                                    <textarea 
                                        class="form-control @error('final_feedback') is-invalid @enderror" 
                                        id="final_feedback" 
                                        name="final_feedback" 
                                        placeholder="Final Feedback">{{ old('final_feedback') }}</textarea>
                                    @error('final_feedback')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-start">
                                    <button type="submit" class="btn btn-success">Save</button>
                                    <a href="{{ route('projects.index') }}" class="btn btn-warning"><i class="fa fa-arrow-rotate-left"></i> Back</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>