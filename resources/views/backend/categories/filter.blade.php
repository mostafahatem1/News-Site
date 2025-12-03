<div class="card">
    <div class="card-header bg-primary text-white">
        <strong>Filter Users</strong>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.categories.index') }}" method="get">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="keyword">Keyword</label>
                        <div class="input-group">
                            <input type="text" name="keyword" id="keyword"
                                value="{{ old('keyword', request()->input('keyword')) }}" class="form-control"
                                placeholder="Search here">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">---</option>
                            <option value="1" {{ old('status', request()->input('status')) == '1' ? 'selected' : ''
                                }}>Active</option>
                            <option value="0" {{ old('status', request()->input('status')) == '0' ? 'selected' : ''
                                }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="sort_by">Sort By</label>
                        <select name="sort_by" id="sort_by" class="form-control">
                            <option value="">---</option>
                            <option value="id" {{ old('sort_by', request()->input('sort_by')) == 'id' ? 'selected' : ''
                                }}>ID</option>
                            <option value="name" {{ old('sort_by', request()->input('sort_by')) == 'name' ? 'selected' :
                                '' }}>Name</option>
                            <option value="created_at" {{ old('sort_by', request()->input('sort_by')) == 'created_at' ?
                                'selected' : '' }}>Created at</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="order_by">Order</label>
                        <select name="order_by" id="order_by" class="form-control">
                            <option value="">---</option>
                            <option value="asc" {{ old('order_by', request()->input('order_by')) == 'asc' ? 'selected' :
                                '' }}>Ascending</option>
                            <option value="desc" {{ old('order_by', request()->input('order_by')) == 'desc' ? 'selected'
                                : '' }}>Descending</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="limit_by">Limit</label>
                        <select name="limit_by" id="limit_by" class="form-control">
                            <option value="">---</option>
                            <option value="10" {{ old('limit_by', request()->input('limit_by')) == '10' ? 'selected' :
                                '' }}>10</option>
                            <option value="20" {{ old('limit_by', request()->input('limit_by')) == '20' ? 'selected' :
                                '' }}>20</option>
                            <option value="50" {{ old('limit_by', request()->input('limit_by')) == '50' ? 'selected' :
                                '' }}>50</option>
                            <option value="100" {{ old('limit_by', request()->input('limit_by')) == '100' ? 'selected' :
                                '' }}>100</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group" style="margin-top: 32px;">
                        <button type="submit" name="submit" class="btn btn-primary btn-block"
                            style="border-radius: 20px; font-weight: 600; letter-spacing: 1px; min-width: 120px;">
                            <i class="fas fa-filter mr-1"></i> Search
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
