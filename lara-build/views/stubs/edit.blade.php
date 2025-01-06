<?php

echo "@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])\n\n";

echo "@section('content')\n";
echo "    @include('layouts.navbars.auth.topnav', ['title' => 'Edit " . Str::studly(Str::singular($data->table_name)) . "'])\n";
echo "    <div class=\"container-fluid py-4\">\n";
echo "        <div class=\"row mt-4\">\n";
echo "            <div class=\"col-lg-12 mb-lg-0 mb-4\">\n";
echo "                <div class=\"card mb-4\">\n";
echo "                    <div class=\"card-header pb-0\">\n";
echo "                        <h6>Edit " . Str::studly(Str::singular($data->table_name)) . "</h6>\n";
echo "                    </div>\n";
echo "                    <div class=\"card-body p-3\">\n";
echo "                        <form role=\"form\" method=\"POST\" action=\"{{ route('" . Str::kebab(Str::singular(trim($data->table_name))) . ".update', ['" . Str::snake(Str::singular($data->table_name)) . "' => \$data->id]) }}\" enctype=\"multipart/form-data\">\n";
echo "                            @csrf\n";
echo "                            @method('PUT')\n";
echo "                            <div class=\"row\">\n";

foreach ($data->columns as $column) {
    if ($column->type == 'string') {
        echo "                                <div class=\"col-md-6\">\n";
        echo "                                    <div class=\"form-group\">\n";
        echo "                                        <label for=\"example-text-input\" class=\"form-control-label\">$column->name <span class=\"text-danger\">*</span></label>\n";
        echo "                                        <input class=\"form-control\" type=\"text\" name=\"$column->name\" required value=\"{{ \$data->$column->name }}\">\n";
        echo "                                    </div>\n";
        echo "                                </div>\n";
    } elseif ($column->type == 'text') {
        echo "                                <div class=\"col-md-6\">\n";
        echo "                                    <div class=\"form-group\">\n";
        echo "                                        <label for=\"example-text-input\" class=\"form-control-label\">$column->name <span class=\"text-danger\">*</span></label>\n";
        echo "                                        <textarea class=\"form-control\" cols=\"30\" rows=\"5\" required name=\"$column->name\">{{ \$data->$column->name }}</textarea>\n";
        echo "                                    </div>\n";
        echo "                                </div>\n";
    } elseif (($column->type == 'integer' && $column->additional == 'number') || $column->type == 'float') {
        echo "                                <div class=\"col-md-6\">\n";
        echo "                                    <div class=\"form-group\">\n";
        echo "                                        <label for=\"example-text-input\" class=\"form-control-label\">$column->name <span class=\"text-danger\">*</span></label>\n";
        echo "                                        <input class=\"form-control\" type=\"number\" name=\"$column->name\" required value=\"{{ \$data->$column->name }}\">\n";
        echo "                                    </div>\n";
        echo "                                </div>\n";
    } elseif ($column->type == 'integer' && $column->additional == 'select') {
        echo "                                <div class=\"col-md-6\">\n";
        echo "                                    <div class=\"form-group\">\n";
        echo "                                        <label for=\"example-text-input\" class=\"form-control-label\">$column->name <span class=\"text-danger\">*</span></label>\n";
        echo "                                        <select class=\"form-select\" name=\"$column->name\">\n";
        echo "                                            <option selected>Open this select menu</option>\n";
        echo "                                            <option @if(\$data->$column->name == '1') selected @endif value=\"1\">One</option>\n";
        echo "                                            <option @if(\$data->$column->name == '2') selected @endif value=\"2\">Two</option>\n";
        echo "                                            <option @if(\$data->$column->name == '3') selected @endif value=\"3\">Three</option>\n";
        echo "                                        </select>\n";
        echo "                                    </div>\n";
        echo "                                </div>\n";
    } elseif ($column->type == 'boolean') {
        echo "                                <div class=\"col-md-6\">\n";
        echo "                                    <div class=\"form-group\">\n";
        echo "                                        <div class=\"form-check form-switch\">\n";
        echo "                                            <input class=\"form-check-input\" type=\"checkbox\" id=\"flexSwitchCheckDefault\" checked=\"{{ \$data->$column->name == 1 ? 'true' : 'false' }}\" name=\"$column->name\">\n";
        echo "                                            <label class=\"form-check-label\" for=\"flexSwitchCheckDefault\">$column->name</label>\n";
        echo "                                        </div>\n";
        echo "                                    </div>\n";
        echo "                                </div>\n";
    } elseif ($column->type == 'date') {
        echo "                                <div class=\"col-md-6\">\n";
        echo "                                    <div class=\"form-group\">\n";
        echo "                                        <label for=\"example-text-input\" class=\"form-control-label\">$column->name <span class=\"text-danger\">*</span></label>\n";
        echo "                                        <input class=\"form-control flatpickr\" placeholder=\"Please select date\" name=\"$column->name\" type=\"text\" value=\"{{ \$data->$column->name }}\">\n";
        echo "                                    </div>\n";
        echo "                                </div>\n";
    } elseif ($column->type == 'time') {
        echo "                                <div class=\"col-md-6\">\n";
        echo "                                    <div class=\"form-group\">\n";
        echo "                                        <label for=\"example-text-input\" class=\"form-control-label\">$column->name <span class=\"text-danger\">*</span></label>\n";
        echo "                                        <input class=\"form-control time-flatpickr\" placeholder=\"Please select time\" name=\"$column->name\" type=\"text\" value=\"{{ \$data->$column->name }}\">\n";
        echo "                                    </div>\n";
        echo "                                </div>\n";
    } elseif ($column->type == 'foreign') {
        echo "                                <div class=\"col-md-6\">\n";
        echo "                                    <div class=\"form-group\">\n";
        echo "                                        <label for=\"example-text-input\" class=\"form-control-label\">$column->name <span class=\"text-danger\">*</span></label>\n";
        echo "                                        <select class=\"form-select\" name=\"$column->name\">\n";
        echo "                                            <option selected>Open this select menu</option>\n";
        echo "                                            @foreach (\$" . Str::kebab(Str::plural($column->additional)) . " as \$" . Str::kebab(Str::singular($column->additional)) . ")\n";
        echo "                                                <option @if(\$data->$column->name == \$" . Str::kebab(Str::singular($column->additional)) . "->id) selected @endif value=\"{{ \$" . Str::kebab(Str::singular($column->additional)) . "->id }}\">{{ \$" . Str::kebab(Str::singular($column->additional)) . "->name }}</option>\n";
        echo "                                            @endforeach\n";
        echo "                                        </select>\n";
        echo "                                    </div>\n";
        echo "                                </div>\n";
    }
}

echo "                            </div>\n";
echo "                            <div class=\"text-end mt-2\">\n";
echo "                                <button type=\"button\" onclick=\"history.back()\" class=\"btn btn-secondary btn-md ms-auto\">Back</button>\n";
echo "                                <button type=\"submit\" class=\"btn btn-primary btn-md ms-auto\">Save</button>\n";
echo "                            </div>\n";
echo "                        </form>\n";
echo "                    </div>\n";
echo "                </div>\n";
echo "            </div>\n";
echo "        </div>\n";
echo "        @include('layouts.footers.auth.footer')\n";
echo "    </div>\n";
echo "@endsection\n\n";

echo "@push('js')\n";
echo "    <script></script>\n";
echo "@endpush\n";
