<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 5/02/2019
 * Time: 2:50 PM
 */

namespace Swarnajith\Empdupe\model;


interface PayeeInterface
{
    public function getFirstName();
    public function getMiddleName();
    public function getLastName();
    public function getTFN();
    public function getDOB();
    public function getResidentialAddressLine1();
    public function getResidentialAddressLine2();
    public function getSuburb();
    public function getState();
    public function getPostCode();
}