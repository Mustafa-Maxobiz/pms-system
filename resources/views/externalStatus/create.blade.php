<x-app-layout>
    <div id="add-external-status" class="my-3 split">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow mb-4 mt-4">
                        <div class="card-header py-3 table-heading">
                            <h6>Add External Status</h6>
                        </div>
                        <form method="POST" action="{{ route('external-status.store') }}" style="padding: 30px;">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('title') is-invalid @enderror" 
                                        id="title" 
                                        name="title"
                                        placeholder="Title" 
                                        value="{{ old('title') }}"
                                    >
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea
                                        class="form-control"
                                        id="description"
                                        name="description"
                                        placeholder="Enter Description"
                                        rows="6"
                                    >{{ old('description') }}</textarea>
                                </div>
                                <div class="col-md-12 mb-3 mt-4">
                                    <label for="order_by" class="form-label">Order By</label>
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        id="order_by" 
                                        name="order_by"
                                        placeholder="Order By" 
                                        value="{{ old('order_by') }}"
                                    >
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 text-start">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                                    <a href="{{ route('external-status.index') }}" class="btn btn-warning"><i class="fa fa-arrow-rotate-left"></i> Back</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
