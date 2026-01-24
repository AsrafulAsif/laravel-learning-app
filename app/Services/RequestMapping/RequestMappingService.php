<?php

namespace App\Services\RequestMapping;

use App\Models\RequestMap\RequestMapping;
use App\Utils\PlaceholderReplacer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Log;

class RequestMappingService
{

    protected PlaceholderReplacer $placeholderReplacer;
    public function __construct(PlaceholderReplacer $placeholderReplacer)
    {
        $this->placeholderReplacer = $placeholderReplacer;
    }

    public function getByRequestId(int|string $requestId): RequestMapping
    {
        // Automatically throws ModelNotFoundException if not found
        $data = RequestMapping::where('request_id', $requestId)->firstOrFail();

        // Decode JSON safely
        $decodedTemplate = json_decode($data->request_json_template, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("Failed to decode request_json_template for request_id: {$requestId}", [
                'error' => json_last_error_msg()
            ]);
            $decodedTemplate = null;
        }

        // Add decoded JSON as a new attribute without mutating original column
        return $data->setAttribute('request_json_template', $decodedTemplate);
    }


    public function requestMapping(int $requestId, Request $request):array
    {
        $template = RequestMapping::where('request_id', $requestId)->firstOrFail()->request_json_template;
        $template =  $this->placeholderReplacer->replacePlaceholders($template, $request->all());
        $data = json_decode($template, true);
        $tableColumns = Schema::getColumnListing('main_data_table');
        $filteredData = array_intersect_key(
            $data,
            array_flip($tableColumns)
        );
        DB::table('main_data_table')->insert($filteredData);
        return $filteredData;
    }

}
