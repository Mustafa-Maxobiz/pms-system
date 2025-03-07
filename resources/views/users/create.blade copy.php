<x-app-layout>
    <div id="add-users" class="my-3 split">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow mb-4 mt-4">
                        <div class="card-header py-3 table-heading">
                            <h6>Add User</h6>
                        </div>
                        <form method="POST" action="{{ route('users.store') }}" style="padding: 30px;">
                            @csrf
                            <div class="row">
                                <!-- Name Field -->
                                <div class="col-md-3 mb-3">
                                    <input 
                                        type="text" 
                                        class="form-control @error('name') is-invalid @enderror" 
                                        id="name" 
                                        name="name"
                                        placeholder="Name" 
                                        value="{{ old('name') }}"
                                        autocomplete="off"
                                    >
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email Field -->
                                <div class="col-md-3 mb-3">
                                    <input 
                                        type="email" 
                                        class="form-control @error('email') is-invalid @enderror" 
                                        id="email" 
                                        name="email"
                                        placeholder="Email Address" 
                                        value="{{ old('email') }}"
                                        autocomplete="off"
                                    >
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Password Field -->
                                <div class="col-md-3 mb-3">
                                    <input 
                                        type="password" 
                                        class="form-control @error('password') is-invalid @enderror" 
                                        id="password" 
                                        name="password"
                                        placeholder="Password" 
                                        autocomplete="off"
                                    >
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Confirmed Password Field -->
                                <div class="col-md-3 mb-3">
                                    <input 
                                        type="password" 
                                        class="form-control @error('confirmed') is-invalid @enderror" 
                                        id="confirmed" 
                                        name="confirmed"
                                        placeholder="Confirmed Password" 
                                        autocomplete="off"
                                    >
                                    @error('confirmed')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Role -->
                                <div class="col-md-12 mb-3">
                                    
                                    @if($roles->isNotEmpty())
                                        @foreach($roles as $key => $role)
                                                <div class="form-check-inline">
                                                    <input 
                                                        type="checkbox" 
                                                        id="role-{{ $role->id }}" 
                                                        name="role[]" 
                                                        value="{{ $role->name }}"
                                                    >
                                                    <label class="form-check-label" for="role-{{ $role->id }}">{{ $role->name }}</label>
                                                </div>
                                        @endforeach
                                    @endif

                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-start">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                                    <a href="{{ route('users.index') }}" class="btn btn-warning"><i class="fa fa-arrow-rotate-left"></i> Back</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
