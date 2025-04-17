<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 5/02/2019
 * Time: 2:50 PM
 */

namespace Swarnajith\Empdupe\model;


interface PayerIdentityInterface
{
    public function getPayerName();
    public function getContactEmail();
}