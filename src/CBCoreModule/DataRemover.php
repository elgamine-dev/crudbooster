<?php

namespace crocodicstudio\crudbooster\CBCoreModule;

use Illuminate\Support\Facades\Schema;

class DataRemover
{
    private $ctrl;

    /**
     * DataRemover constructor.
     *
     * @param $ctrl
     */
    public function __construct($ctrl)
    {
        $this->ctrl =  $ctrl;
    }

    /**
     * @param $idsArray
     */
    function deleteIds($idsArray)
    {
        $query = $this->ctrl->table()->whereIn($this->ctrl->primary_key, $idsArray);
        if (Schema::hasColumn($this->ctrl->table, 'deleted_at')) {
            $query->update(['deleted_at' => date('Y-m-d H:i:s')]);
        } else {
            $query->delete();
        }
    }

    /**
     * @param $idsArray
     */
    function doDeleteWithHook($idsArray)
    {
        $this->ctrl->hookBeforeDelete($idsArray);
        $this->deleteIds($idsArray);
        $this->ctrl->hookAfterDelete($idsArray);
    }
}