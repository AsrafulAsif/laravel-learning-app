<?php

namespace App\Services\RequestMapping;

use App\Repositories\Contracts\RequestMappingRepositoryInterface;
use App\Utils\PlaceholderReplacer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Log;

class RequestMappingService
{
    protected RequestMappingRepositoryInterface $repository;
    protected PlaceholderReplacer $placeholderReplacer;
    public function __construct(RequestMappingRepositoryInterface $repository, PlaceholderReplacer $placeholderReplacer)
    {
        $this->repository = $repository;
        $this->placeholderReplacer = $placeholderReplacer;
    }

    public function getByRequestId($requestId)
    {
        $data = $this->repository->getByRequestId($requestId);
        Log::info($data->request_json_template);
        $data->request_json_template =
            json_decode($data->request_json_template, true);
        return $data;
    }

    public function requestMapping(int $requestId, Request $request):array
    {
        $template = $this->repository->getByRequestId($requestId)->request_json_template;
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
