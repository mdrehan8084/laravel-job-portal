@php
    // Checkbox state: after a validation error use the old input, otherwise the saved value
    $isActive = old() ? old('is_active') : ($job->is_active ?? true);
@endphp

<form method="POST" action="{{ $action }}">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Job title</label>
            <input type="text" name="title" value="{{ old('title', $job->title ?? '') }}"
                   class="form-control @error('title') is-invalid @enderror">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Company</label>
            <input type="text" name="company" value="{{ old('company', $job->company ?? '') }}"
                   class="form-control @error('company') is-invalid @enderror">
            @error('company')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Location</label>
            <input type="text" name="location" value="{{ old('location', $job->location ?? '') }}"
                   class="form-control @error('location') is-invalid @enderror">
            @error('location')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Job type</label>
            <select name="type" class="form-select @error('type') is-invalid @enderror">
                @foreach(['full-time', 'part-time', 'internship', 'remote'] as $type)
                    <option value="{{ $type }}" @selected(old('type', $job->type ?? 'full-time') == $type)>
                        {{ ucfirst($type) }}
                    </option>
                @endforeach
            </select>
            @error('type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Minimum salary (₹ per month)</label>
            <input type="number" name="salary_min" value="{{ old('salary_min', $job->salary_min ?? '') }}"
                   class="form-control @error('salary_min') is-invalid @enderror">
            @error('salary_min')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Maximum salary (₹ per month)</label>
            <input type="number" name="salary_max" value="{{ old('salary_max', $job->salary_max ?? '') }}"
                   class="form-control @error('salary_max') is-invalid @enderror">
            @error('salary_max')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Job description</label>
        <textarea name="description" rows="6"
                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $job->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-check mb-4">
        <input type="checkbox" name="is_active" value="1" id="is_active"
               class="form-check-input" @checked($isActive)>
        <label class="form-check-label" for="is_active">Active (visible to candidates)</label>
    </div>

    <button class="btn btn-primary">{{ $submitLabel }}</button>
    <a href="{{ route('recruiter.jobs.index') }}" class="btn btn-outline-secondary">Cancel</a>
</form>