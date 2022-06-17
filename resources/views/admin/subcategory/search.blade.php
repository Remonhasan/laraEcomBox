@section('admin_search_form_body')
    <?php
    $lang = config('app.locale');
    /**
     * Get all the filter parameters from URL and Sanitize 'em.
     * @link https://www.php.net/manual/en/function.filter-input.php
     * @var $_filter_params Array of parameters. VARIABLE NAME NEEDS TO BE INTACT.
     */
    $_filter_params = [
        'name' => filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING),
        'is_active' => filter_input(INPUT_GET, 'is_active', FILTER_SANITIZE_STRING),
    ];

    $_count = count( array_filter($_filter_params, 'strlen') );
    ?>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="name" class="font-weight-bold">{{ __('Name') }}</label>
                <input type="text" name="name" id="search" class="form-control" autocomplete="off"
                    value="{{ $_filter_params['name'] }}">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="category-status" class="font-weight-bold">{{ __('Status') }}</label>
                <select name="is_active" id="category-status" class="custom-select">
                    <option value="active" {{ 'active' === $_filter_params['is_active'] ? 'selected="selected"' : '' }}>
                        {{ __('Active') }}</option>
                    <option value="inactive"
                        {{ 'inactive' === $_filter_params['is_active'] ? 'selected="selected"' : '' }}>
                        {{ __('Inactive') }}</option>
                </select>
            </div>
        </div>
    </div>
@endsection

@include('admin-layouts.admin-search-form')
