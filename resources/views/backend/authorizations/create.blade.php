<!-- Create Role Modal-->
<div class="modal fade" id="createRoleModal" tabindex="-1" role="dialog" aria-labelledby="createRoleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createRoleModalLabel">Create Role</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.authorizations.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Role Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control"
                            placeholder="Role Name" required />
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Permissions</label>
                        <div>
                            @php
                            $permissions = config('authorization.permissions');
                            $groups = [];
                            if(is_array($permissions)) {
                            foreach($permissions as $key => $label) {
                            $prefix = explode('_', $key)[0];
                            $title = ucfirst(str_replace('_', ' ', $prefix));
                            $groups[$prefix] = $title;
                            }
                            }

                            $grouped = [];
                            if(is_array($permissions)) {
                            foreach($permissions as $key => $label) {
                            $prefix = explode('_', $key)[0];
                            $title = $groups[$prefix] ?? ucfirst($prefix);
                            $grouped[$title][$key] = $label;
                            }
                            }
                            @endphp
                            @if(is_array($permissions))
                            @foreach($grouped as $groupTitle => $perms)
                            <div class="card mb-2">
                                <div class="card-header py-1 px-2">
                                    <strong>{{ $groupTitle }}</strong>
                                </div>
                                <div class="card-body py-2 px-3">
                                    <div class="row">
                                        @foreach($perms as $key => $label)
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                                    id="permission_{{ $key }}" value="{{ $key }}" {{
                                                    (is_array(old('permissions')) && in_array($key, old('permissions')))
                                                    ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permission_{{ $key }}">
                                                    {{ $label }}
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @else
                            <span class="text-danger">No permissions found.</span>
                            @endif
                        </div>
                        @error('permissions')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-success" type="submit">Create Role</button>
                </div>
            </form>
        </div>
    </div>
</div>
