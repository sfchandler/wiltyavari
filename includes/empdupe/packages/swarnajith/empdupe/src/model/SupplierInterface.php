<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 5/02/2019
 * Time: 2:52 PM
 */

namespace Swarnajith\Empdupe\model;


interface SupplierInterface
{
    public function getSupplierName();
    public function getTradingName();
    public function getABN();
    public function getContactName();
    public function getContactNumber();
    public function getAddressLine1();
    public function getAddressLine2();
    public function getSuburb();
    public function getState();
    public function getPostCode();
    public function getFinancialYear();
}