<?php

echo "@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Show " . Str::studly(Str::singular($data->table_name)) . "'])
    <div class=\"container-fluid py-4\">
        <div class=\"row mt-4\">
            <div class=\"col-lg-12 mb-lg-0 mb-4\">
                <div class=\"card mb-4\">
                    <div class=\"card-header pb-0\">
                        <h6>Show " . Str::studly(Str::singular($data->table_name)) . "</h6>
                    </div>
                    <div class=\"card-body p-3\">
                        <div class=\"row\">
";

foreach ($data->columns as $column) {
    if ($column->type != 'boolean') {
        echo "
                            <div class=\"col-md-6\">
                                <div class=\"form-group\">
                                    <label class=\"form-control-label\">" . $column->name . "</label>
                                    <p class=\"ms-1\">{{ \$data->" . $column->name . " }}</p>
                                </div>
                            </div>
        ";
    } else {
        echo "
                            <div class=\"col-md-6\">
                                <div class=\"form-group\">
                                    <label class=\"form-control-label\">" . $column->name . "</label>
                                    <div class=\"form-check form-switch\">
                                        <input class=\"form-check-input\" type=\"checkbox\" id=\"flexSwitchCheckDefault\"
                                            {{ \$data->" . $column->name . " == 1 ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
        ";
    }
}

echo "
                        </div>
                        <div class=\"text-end mt-2\">
                            <button onclick=\"history.back()\" class=\"btn btn-secondary btn-md ms-auto\">Back</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection

@push('js')
    <script></script>
@endpush
";
