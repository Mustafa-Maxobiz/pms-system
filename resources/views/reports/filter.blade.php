<form method="GET"> 
    <div class="row p-3">
    <div class="col-md-3 mt-2">
        <select class="form-control form-select select2" name="department_id" id="department"
            onchange="getTeam(this.value, this)">
            <option value="" disabled selected>Select Department</option>
            @foreach ($data['departments'] as $dep)
                <option value="{{ $dep->id }}">{{ $dep->name ?? '' }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3 mt-2">
        <select class="form-control form-select select2" name="team_id" id="team"
            onchange="getMember(this.value, this)">
            <option value="" disabled selected>Select Team</option>
            @foreach ($data['teams'] as $team)
                <option value="{{ $team->id }}">{{ $team->name ?? '' }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3 mt-2">
        <select class="form-control form-select select2" name="member_id" id="member">
            <option value="" disabled selected>Select Member</option>
            @foreach ($data['members'] as $member)
                <option value="{{ $member->id }}">{{ $member->name ?? '' }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3 mt-2">
        <select class="form-control form-select select2" name="client_id" id="client"
            onchange="getProject(this.value, this)">
            <option value="" disabled selected>Select Client</option>
            @foreach ($data['clients'] as $client)
                <option value="{{ $client->id }}">{{ $client->client_name ?? '' }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3 mt-2">
        <select class="form-control form-select select2" name="project_id" id="project">
            <option value="" disabled selected>Select Project</option>
            @foreach ($data['projects'] as $project)
                <option value="{{ $project->id }}">{{ $project->project_name ?? '' }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3 mt-2">
        <select class="form-control form-select select2" name="source_id" id="source">
            <option value="" disabled selected>Select Source</option>
            @foreach ($data['sorces'] as $source)
                <option value="{{ $source->id }}">{{ $source->source_name ?? '' }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2 mt-2">
        <input type="date" name="from_date" class="form-control">
    </div>

    <div class="col-md-2 mt-2">
        <input type="date" name="to_date" class="form-control">
    </div>
    <div class="col-md-2 mt-2">
        <input type="submit" name="submit" class="btn btn-success" value="Search">
    </div>
    </div>
</form>