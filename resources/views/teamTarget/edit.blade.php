<x-app-layout>
    <div id="team-management" class="mb-4 mt-4 split">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">

                    <div class="card shadow mb-4 mt-4">
                        <div class="card-header table-heading p-3 d-flex justify-content-between align-items-center">
                            <h6>Edit Team Target</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('target.update') }}" method="POST">
                                @csrf

                                <input type="hidden" name="target_id" value="{{ $teamTarget->id ?? '' }}">
                                <input type="hidden" name="user_id" value="{{ $teamTarget->user_id ?? '' }}">
                                <input type="hidden" name="team_id" value="{{ $teamTarget->team_id ?? '' }}">

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Target</label>
                                        <input type="number" name="target_amount" class="form-control me-2"
                                            value="{{ $teamTarget->target_amount ?? '' }}" step="any" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Hours</label>
                                        <input type="number" name="hours" class="form-control me-2"
                                            value="{{ $teamTarget->hours ?? '' }}" step="any" required>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-success">Update</button>
                                        <a href="{{ route('target.index') }}" class="btn btn-warning"><svg
                                                class="svg-inline--fa fa-arrow-rotate-left" aria-hidden="true"
                                                focusable="false" data-prefix="fas" data-icon="arrow-rotate-left"
                                                role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                                                data-fa-i2svg="">
                                                <path fill="currentColor"
                                                    d="M480 256c0 123.4-100.5 223.9-223.9 223.9c-48.86 0-95.19-15.58-134.2-44.86c-14.14-10.59-17-30.66-6.391-44.81c10.61-14.09 30.69-16.97 44.8-6.375c27.84 20.91 61 31.94 95.89 31.94C344.3 415.8 416 344.1 416 256s-71.67-159.8-159.8-159.8C205.9 96.22 158.6 120.3 128.6 160H192c17.67 0 32 14.31 32 32S209.7 224 192 224H48c-17.67 0-32-14.31-32-32V48c0-17.69 14.33-32 32-32s32 14.31 32 32v70.23C122.1 64.58 186.1 32.11 256.1 32.11C379.5 32.11 480 132.6 480 256z">
                                                </path>
                                            </svg>Back</a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script></script>
    @endsection
</x-app-layout>
