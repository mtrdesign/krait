<?php

namespace MtrDesign\Krait\Http\Controllers\Api;

use MtrDesign\Krait\Http\Requests\TableStructureRequest;
use MtrDesign\Krait\Http\Resources\TableStructureCollection;
use MtrDesign\Krait\Services\TablesOrchestrator\TablesOrchestrator;

class TableStructureController extends Controller
{
    private TablesOrchestrator $tablesOrchestrator;

    public function __construct(TablesOrchestrator $tablesOrchestrator)
    {
        $this->tablesOrchestrator = $tablesOrchestrator;
    }

    public function __invoke(TableStructureRequest $request)
    {
        $data = $request->validated();

        $tableName = $data['table'];

        $table = $this->tablesOrchestrator->getTableByRoute($tableName);

        if (! $table) {
            abort(404);
        }

        return new TableStructureCollection($table);
    }
}
