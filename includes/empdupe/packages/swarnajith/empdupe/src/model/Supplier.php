<?php

namespace Swarnajith\Empdupe\model;

class Supplier implements SupplierInterface
{
    private $supplierName;
    private $tradingName;
    private $abn;
    private $contactName;
    private $contactNumber;
    private $facsmileNumber;
    private $fileReferenceNumber;
    private $addressLine1;
    private $addressLine2;
    private $suburb;
    private $State;
    private $postCode;
    private $country;
    private $postalAddressLine1;
    private $postalAddressLine2;
    private $postalSuburb;
    private $postalState;
    private $postalPostCode;
    private $postalCountry;
    private $emailAddress;
    private $financialYear;


    /**
     * @return mixed
     */
    public function getPostalAddressLine1()
    {
        return $this->postalAddressLine1;
    }

    /**
     * @param mixed $postalAddressLine1
     */
    public function setPostalAddressLine1($postalAddressLine1): void
    {
        $this->postalAddressLine1 = $postalAddressLine1;
    }

    /**
     * @return mixed
     */
    public function getPostalAddressLine2()
    {
        return $this->postalAddressLine2;
    }

    /**
     * @param mixed $postalAddressLine2
     */
    public function setPostalAddressLine2($postalAddressLine2): void
    {
        $this->postalAddressLine2 = $postalAddressLine2;
    }

    /**
     * @return mixed
     */
    public function getPostalSuburb()
    {
        return $this->postalSuburb;
    }

    /**
     * @param mixed $postalSuburb
     */
    public function setPostalSuburb($postalSuburb): void
    {
        $this->postalSuburb = $postalSuburb;
    }

    /**
     * @return mixed
     */
    public function getPostalState()
    {
        return $this->postalState;
    }

    /**
     * @param mixed $postalState
     */
    public function setPostalState($postalState): void
    {
        $this->postalState = $postalState;
    }

    /**
     * @return mixed
     */
    public function getPostalPostCode()
    {
        return $this->postalPostCode;
    }

    /**
     * @param mixed $postalPostCode
     */
    public function setPostalPostCode($postalPostCode): void
    {
        $this->postalPostCode = $postalPostCode;
    }

    /**
     * @return mixed
     */
    public function getPostalCountry()
    {
        return $this->postalCountry;
    }

    /**
     * @param mixed $postalCountry
     */
    public function setPostalCountry($postalCountry): void
    {
        $this->postalCountry = $postalCountry;
    }

    /**
     * @return mixed
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @param mixed $emailAddress
     */
    public function setEmailAddress($emailAddress): void
    {
        $this->emailAddress = $emailAddress;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country): void
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getFileReferenceNumber()
    {
        return $this->fileReferenceNumber;
    }

    /**
     * @param mixed $fileReferenceNumber
     */
    public function setFileReferenceNumber($fileReferenceNumber): void
    {
        $this->fileReferenceNumber = $fileReferenceNumber;
    }

    /**
     * @return mixed
     */
    public function getFacsmileNumber()
    {
        return $this->facsmileNumber;
    }

    /**
     * @param mixed $facsmileNumber
     */
    public function setFacsmileNumber($facsmileNumber): void
    {
        $this->facsmileNumber = $facsmileNumber;
    }

    /**
     * @return mixed
     */
    public function getSupplierName()
    {
        return $this->supplierName;
    }

    /**
     * @param mixed $supplierName
     */
    public function setSupplierName($supplierName): void
    {
        $this->supplierName = $supplierName;
    }

    /**
     * @return mixed
     */

    /**
     * @return mixed
     */
    public function getTradingName()
    {
        return $this->tradingName;
    }

    /**
     * @param mixed $tradingName
     */
    public function setTradingName($tradingName): void
    {
        $this->tradingName = $tradingName;
    }

    /**
     * @return mixed
     */
    public function getAbn()
    {
        return $this->abn;
    }

    /**
     * @param mixed $abn
     */
    public function setAbn($abn): void
    {
        $this->abn = $abn;
    }

    /**
     * @return mixed
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * @param mixed $contactName
     */
    public function setContactName($contactName): void
    {
        $this->contactName = $contactName;
    }

    /**
     * @return mixed
     */
    public function getContactNumber()
    {
        return $this->contactNumber;
    }

    /**
     * @param mixed $contactNumber
     */
    public function setContactNumber($contactNumber): void
    {
        $this->contactNumber = $contactNumber;
    }

    /**
     * @return mixed
     */
    public function getAddressLine1()
    {
        return $this->addressLine1;
    }

    /**
     * @param mixed $addressLine1
     */
    public function setAddressLine1($addressLine1): void
    {
        $this->addressLine1 = $addressLine1;
    }

    /**
     * @return mixed
     */
    public function getAddressLine2()
    {
        return $this->addressLine2;
    }

    /**
     * @param mixed $addressLine2
     */
    public function setAddressLine2($addressLine2): void
    {
        $this->addressLine2 = $addressLine2;
    }

    /**
     * @return mixed
     */
    public function getSuburb()
    {
        return $this->suburb;
    }

    /**
     * @param mixed $suburb
     */
    public function setSuburb($suburb): void
    {
        $this->suburb = $suburb;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->State;
    }

    /**
     * @param mixed $State
     */
    public function setState($State): void
    {
        $this->State = $State;
    }

    /**
     * @return mixed
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * @param mixed $postCode
     */
    public function setPostCode($postCode): void
    {
        $this->postCode = $postCode;
    }

    /**
     * @return mixed
     */
    public function getFinancialYear()
    {
        return $this->financialYear;
    }

    /**
     * @param mixed $financialYear
     */
    public function setFinancialYear($financialYear): void
    {
        $this->financialYear = $financialYear;
    }


}
