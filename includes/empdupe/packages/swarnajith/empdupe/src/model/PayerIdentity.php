<?php

namespace Swarnajith\Empdupe\model;



class PayerIdentity implements PayerIdentityInterface
{
    private $payerName;
    private $contactEmail;
    private $payerAbn;
    private $branchNumber;
    private $financialYear;
    private $tradingName;
    private $payerAddressLine1;
    private $payerAddressLine2;
    private $payerSuburb;
    private $payerState;
    private $payerPostCode;
    private $payerCountry;
    private $payerContactName;
    private $payerContactPhone;
    private $payerContactFacsimileNumber;

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

    /**
     * @return mixed
     */
    public function getPayerAddressLine1()
    {
        return $this->payerAddressLine1;
    }

    /**
     * @param mixed $payerAddressLine1
     */
    public function setPayerAddressLine1($payerAddressLine1): void
    {
        $this->payerAddressLine1 = $payerAddressLine1;
    }

    /**
     * @return mixed
     */
    public function getPayerAddressLine2()
    {
        return $this->payerAddressLine2;
    }

    /**
     * @param mixed $payerAddressLine2
     */
    public function setPayerAddressLine2($payerAddressLine2): void
    {
        $this->payerAddressLine2 = $payerAddressLine2;
    }

    /**
     * @return mixed
     */
    public function getPayerSuburb()
    {
        return $this->payerSuburb;
    }

    /**
     * @param mixed $payerSuburb
     */
    public function setPayerSuburb($payerSuburb): void
    {
        $this->payerSuburb = $payerSuburb;
    }

    /**
     * @return mixed
     */
    public function getPayerState()
    {
        return $this->payerState;
    }

    /**
     * @param mixed $payerState
     */
    public function setPayerState($payerState): void
    {
        $this->payerState = $payerState;
    }

    /**
     * @return mixed
     */
    public function getPayerPostCode()
    {
        return $this->payerPostCode;
    }

    /**
     * @param mixed $payerPostCode
     */
    public function setPayerPostCode($payerPostCode): void
    {
        $this->payerPostCode = $payerPostCode;
    }

    /**
     * @return mixed
     */
    public function getPayerCountry()
    {
        return $this->payerCountry;
    }

    /**
     * @param mixed $payerCountry
     */
    public function setPayerCountry($payerCountry): void
    {
        $this->payerCountry = $payerCountry;
    }

    /**
     * @return mixed
     */
    public function getPayerContactName()
    {
        return $this->payerContactName;
    }

    /**
     * @param mixed $payerContactName
     */
    public function setPayerContactName($payerContactName): void
    {
        $this->payerContactName = $payerContactName;
    }

    /**
     * @return mixed
     */
    public function getPayerContactPhone()
    {
        return $this->payerContactPhone;
    }

    /**
     * @param mixed $payerContactPhone
     */
    public function setPayerContactPhone($payerContactPhone): void
    {
        $this->payerContactPhone = $payerContactPhone;
    }

    /**
     * @return mixed
     */
    public function getPayerContactFacsimileNumber()
    {
        return $this->payerContactFacsimileNumber;
    }

    /**
     * @param mixed $payerContactFacsimileNumber
     */
    public function setPayerContactFacsimileNumber($payerContactFacsimileNumber): void
    {
        $this->payerContactFacsimileNumber = $payerContactFacsimileNumber;
    }



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
    public function getPayerName()
    {
        return $this->payerName;
    }

    /**
     * @param mixed $payerName
     */
    public function setPayerName($payerName): void
    {
        $this->payerName = $payerName;
    }

    /**
     * @return mixed
     */
    public function getBranchNumber()
    {
        return $this->branchNumber;
    }

    /**
     * @param mixed $branchNumber
     */
    public function setBranchNumber($branchNumber): void
    {
        $this->branchNumber = $branchNumber;
    }


    /**
     * @return mixed
     */
    public function getPayerAbn()
    {
        return $this->payerAbn;
    }

    /**
     * @param mixed $payerAbn
     */
    public function setPayerAbn($payerAbn): void
    {
        $this->payerAbn = $payerAbn;
    }

    /**
     * @return mixed
     */
    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    /**
     * @param mixed $contactEmail
     */
    public function setContactEmail($contactEmail): void
    {
        $this->contactEmail = $contactEmail;
    }


}
