<?php

namespace App\Services;


use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Purchase extends Controller {

    protected static function queryBuilder($table = 'tbl_purchase_head') {
        return DB::table($table)
            ->where('dflag', 0);
    }

    /**
     * Get all Categories
     * @return Collection
     */
    protected function requestCategoryRecords() {
        return DB::table('tbl_category')
            ->where('DFlag', 0)
            ->where('ActiveStatus', 1)
            ->get();
    }

    /**
     * Get subCategories related to the category
     * @param string $cid
     * @return Collection
     */
    protected function requestSubCategoryRecords(string $cid) {
        return DB::table('tbl_subcategory')
            ->where('DFlag', 0)
            ->where('ActiveStatus', 1)
            ->where('CID', $cid)
            ->get(['SCID', 'SCName']);
    }

    /**
     * Tax queryBuilder
     * @return \Illuminate\Database\Query\Builder
     */
    protected function queryBuilderTax() {
        return DB::table('tbl_tax')
            ->where('DFlag', 0)
            ->where('ActiveStatus', 1);
    }

    /**
     * Get all tax requests
     * @return Collection
     */
    protected function requestTaxRecords() {
        return $this->queryBuilderTax()->get(['TaxID', 'TaxName', 'TaxPercentage']);
    }

    /**
     * Summary of requestSingleTaxRecords
     * @param string $taxId
     * @return \Illuminate\Database\Concerns\BuildsQueries|\Illuminate\Database\Eloquent\Model|object|null
     */
    protected function requestSingleTaxRecords(string $taxId) {
        return $this->queryBuilderTax()->where('TaxID', $taxId)->first();
    }

    // subCategoryId
    protected function requestProductRecords(string $scId) {
        return DB::table('tbl_products')
            ->where('dflag', 0)
            ->where('is_active', 1)
            ->where('subCategoryId', $scId)
            ->get(['pid', 'name', 'taxId', 'purchaseRate']);
    }
    protected function requestSingleProductRecords(string $pid) {
        return DB::table('tbl_products')
            ->where('dflag', 0)
            ->where('is_active', 1)
            ->where('pid', $pid)
            ->first();
    }


    /**
     * Summary of requestSupplierRecords
     * @return Collection
     */
    protected function requestSupplierRecords() {
        return DB::table('tbl_suppliers')
            ->where('dflag', 0)
            ->where('is_active', 1)
            ->get();
    }

}