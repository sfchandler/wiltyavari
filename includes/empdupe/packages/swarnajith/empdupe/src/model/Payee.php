<?php

namespace Swarnajith\Empdupe\model;


class Payee implements PayeeInterface
{
    private $recordIdentifier;
    private $incomeType;
    private $firstName;
    private $middleName;
    private $lastName;
    private $tfn;
    private $dob;
    private $residentialAddressLine1;
    private $residentialAddressLine2;
    private $suburb;
    private $state;
    private $postCode;
    private $country;
    private $totalTaxWithHeld;
    private $grossPayment;
    private $totalAllowance;
    private $lumpsumA;
    private $lumpsumB;
    private $lumpsumD;
    private $lumpsumE;
    private $communityDevelopmentEmploymentProject;
    private $zeroFiller;
    private $reportableFringeBenefit;
    private $amendmentIndicator;
    private $reportableEmployerSuperAnnuationContribution;
    private $lumpsumPaymentAType;
    private $workplaceGiving;
    private $unionFess;
    private $foreignEmploymentIncome;
    private $deductiblePurchasePriceAnnuity;
    private $fbtType;


    public function __construct($recordIdentifier = 'DINB',$amendmentIndicator = 'O',$lumpsumPaymentAType = 'T',$fbtType = 'N')
    {
        $this->recordIdentifier = $recordIdentifier;
        $this->amendmentIndicator = $amendmentIndicator;
        $this->lumpsumPaymentAType = $lumpsumPaymentAType;
        $this->fbtType = $fbtType;
    }

    /**
     * @return mixed
     */
    public function getZeroFiller()
    {
        return $this->zeroFiller;
    }

    /**
     * @param mixed $zeroFiller
     */
    public function setZeroFiller($zeroFiller): void
    {
        $this->zeroFiller = $zeroFiller;
    }

    /**
     * @return string
     */
    public function getRecordIdentifier(): string
    {
        return $this->recordIdentifier;
    }

    /**
     * @param string $recordIdentifier
     */
    public function setRecordIdentifier(string $recordIdentifier): void
    {
        $this->recordIdentifier = $recordIdentifier;
    }

    /**
     * @return mixed
     */
    public function getIncomeType()
    {
        return $this->incomeType;
    }

    /**
     * @param mixed $incomeType
     */
    public function setIncomeType($incomeType): void
    {
        $this->incomeType = $incomeType;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * @param mixed $middleName
     */
    public function setMiddleName($middleName): void
    {
        $this->middleName = $middleName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getTfn()
    {
        return $this->tfn;
    }

    /**
     * @param mixed $tfn
     */
    public function setTfn($tfn): void
    {
        $this->tfn = $tfn;
    }

    /**
     * @return mixed
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * @param mixed $dob
     */
    public function setDob($dob): void
    {
        $this->dob = $dob;
    }

    /**
     * @return mixed
     */
    public function getResidentialAddressLine1()
    {
        return $this->residentialAddressLine1;
    }

    /**
     * @param mixed $residentialAddressLine1
     */
    public function setResidentialAddressLine1($residentialAddressLine1): void
    {
        $this->residentialAddressLine1 = $residentialAddressLine1;
    }

    /**
     * @return mixed
     */
    public function getResidentialAddressLine2()
    {
        return $this->residentialAddressLine2;
    }

    /**
     * @param mixed $residentialAddressLine2
     */
    public function setResidentialAddressLine2($residentialAddressLine2): void
    {
        $this->residentialAddressLine2 = $residentialAddressLine2;
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
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state): void
    {
        $this->state = $state;
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
    public function getTotalTaxWithHeld()
    {
        return $this->totalTaxWithHeld;
    }

    /**
     * @param mixed $totalTaxWithHeld
     */
    public function setTotalTaxWithHeld($totalTaxWithHeld): void
    {
        $this->totalTaxWithHeld = $totalTaxWithHeld;
    }

    /**
     * @return mixed
     */
    public function getGrossPayment()
    {
        return $this->grossPayment;
    }

    /**
     * @param mixed $grossPayment
     */
    public function setGrossPayment($grossPayment): void
    {
        $this->grossPayment = $grossPayment;
    }

    /**
     * @return mixed
     */
    public function getTotalAllowance()
    {
        return $this->totalAllowance;
    }

    /**
     * @param mixed $totalAllowance
     */
    public function setTotalAllowance($totalAllowance): void
    {
        $this->totalAllowance = $totalAllowance;
    }

    /**
     * @return mixed
     */
    public function getLumpsumA()
    {
        return $this->lumpsumA;
    }

    /**
     * @param mixed $lumpsumA
     */
    public function setLumpsumA($lumpsumA): void
    {
        $this->lumpsumA = $lumpsumA;
    }

    /**
     * @return mixed
     */
    public function getLumpsumB()
    {
        return $this->lumpsumB;
    }

    /**
     * @param mixed $lumpsumB
     */
    public function setLumpsumB($lumpsumB): void
    {
        $this->lumpsumB = $lumpsumB;
    }

    /**
     * @return mixed
     */
    public function getLumpsumD()
    {
        return $this->lumpsumD;
    }

    /**
     * @param mixed $lumpsumD
     */
    public function setLumpsumD($lumpsumD): void
    {
        $this->lumpsumD = $lumpsumD;
    }

    /**
     * @return mixed
     */
    public function getLumpsumE()
    {
        return $this->lumpsumE;
    }

    /**
     * @param mixed $lumpsumE
     */
    public function setLumpsumE($lumpsumE): void
    {
        $this->lumpsumE = $lumpsumE;
    }

    /**
     * @return mixed
     */
    public function getCommunityDevelopmentEmploymentProject()
    {
        return $this->communityDevelopmentEmploymentProject;
    }

    /**
     * @param mixed $communityDevelopmentEmploymentProject
     */
    public function setCommunityDevelopmentEmploymentProject($communityDevelopmentEmploymentProject): void
    {
        $this->communityDevelopmentEmploymentProject = $communityDevelopmentEmploymentProject;
    }

    /**
     * @return mixed
     */
    public function getReportableFringeBenefit()
    {
        return $this->reportableFringeBenefit;
    }

    /**
     * @param mixed $reportableFringeBenefit
     */
    public function setReportableFringeBenefit($reportableFringeBenefit): void
    {
        $this->reportableFringeBenefit = $reportableFringeBenefit;
    }

    /**
     * @return mixed
     */
    public function getAmendmentIndicator()
    {
        return $this->amendmentIndicator;
    }

    /**
     * @param mixed $amendmentIndicator
     */
    public function setAmendmentIndicator($amendmentIndicator): void
    {
        $this->amendmentIndicator = $amendmentIndicator;
    }

    /**
     * @return mixed
     */
    public function getReportableEmployerSuperAnnuationContribution()
    {
        return $this->reportableEmployerSuperAnnuationContribution;
    }

    /**
     * @param mixed $reportableEmployerSuperAnnuationContribution
     */
    public function setReportableEmployerSuperAnnuationContribution($reportableEmployerSuperAnnuationContribution): void
    {
        $this->reportableEmployerSuperAnnuationContribution = $reportableEmployerSuperAnnuationContribution;
    }

    /**
     * @return mixed
     */
    public function getLumpsumPaymentAType()
    {
        return $this->lumpsumPaymentAType;
    }

    /**
     * @param mixed $lumpsumPaymentAType
     */
    public function setLumpsumPaymentAType($lumpsumPaymentAType): void
    {
        $this->lumpsumPaymentAType = $lumpsumPaymentAType;
    }

    /**
     * @return mixed
     */
    public function getWorkplaceGiving()
    {
        return $this->workplaceGiving;
    }

    /**
     * @param mixed $workplaceGiving
     */
    public function setWorkplaceGiving($workplaceGiving): void
    {
        $this->workplaceGiving = $workplaceGiving;
    }

    /**
     * @return mixed
     */
    public function getUnionFess()
    {
        return $this->unionFess;
    }

    /**
     * @param mixed $unionFess
     */
    public function setUnionFess($unionFess): void
    {
        $this->unionFess = $unionFess;
    }

    /**
     * @return mixed
     */
    public function getForeignEmploymentIncome()
    {
        return $this->foreignEmploymentIncome;
    }

    /**
     * @param mixed $foreignEmploymentIncome
     */
    public function setForeignEmploymentIncome($foreignEmploymentIncome): void
    {
        $this->foreignEmploymentIncome = $foreignEmploymentIncome;
    }

    /**
     * @return mixed
     */
    public function getDeductiblePurchasePriceAnnuity()
    {
        return $this->deductiblePurchasePriceAnnuity;
    }

    /**
     * @param mixed $deductiblePurchasePriceAnnuity
     */
    public function setDeductiblePurchasePriceAnnuity($deductiblePurchasePriceAnnuity): void
    {
        $this->deductiblePurchasePriceAnnuity = $deductiblePurchasePriceAnnuity;
    }

    /**
     * @return mixed
     */
    public function getFbtType()
    {
        return $this->fbtType;
    }

    /**
     * @param mixed $fbtType
     */
    public function setFbtType($fbtType): void
    {
        $this->fbtType = $fbtType;
    }



}
