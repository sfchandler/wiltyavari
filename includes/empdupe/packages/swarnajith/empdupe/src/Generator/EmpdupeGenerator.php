<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 1/02/2019
 * Time: 12:31 PM
 */

namespace Swarnajith\Empdupe;


use Swarnajith\Empdupe\model\PayerIdentity;
use Swarnajith\Empdupe\model\Payee;
use Swarnajith\Empdupe\model\Software;
use Swarnajith\Empdupe\model\Supplier;

class EmpdupeGenerator
{
    const SPACER = '';
    const DESCRIPTIVE_TYPE = '0';
    const RECORD_IDENTIFIER = 'IDENTITY';
    const IDENT_REGISTER = 'IDENTREGISTER';
    const DINBS = 'DINBS';
    const RECORD_LENGTH = 628;
    const RUN_TYPE = 'P';
    const DATA_TYPE = 'E';
    const TYPE_OF_REPORT = 'A';
    const FORMAT_OF_RETURN_MEDIA = 'P';
    const REPORT_SPECIFICATION_VERSION_NUMBER = 'FEMPA013.0';
    const FILE_TOTAL = 'FILE-TOTAL';

    private $generatedDate;
    private $fromDate;
    private $toDate;
    private $empdupeString;
    private $financialYear;

    /**
     * EmpdupeGenerator constructor.
     * @param $generatedDate
     * @param $fromDate
     * @param $toDate
     * @param $empdupeString
     * @param $financialYear
     */
/*$generatedDate = date('dmY',strtotime('2018-06-15'));
$fromDate = date('dmY',strtotime('2017-08-10'));
$toDate = date('dmY',strtotime('2018-06-30'));
$financialYear = date('Y',strtotime($toDate));
$empdupeString = '';*/
    public function __construct($generatedDate = '', $fromDate = '', $toDate = '',$financialYear='')
    {
        $this->generatedDate = date('dmY',strtotime($generatedDate));
        $this->fromDate = date('dmY',strtotime($fromDate));
        $this->toDate = date('dmY',strtotime($toDate));
        $this->empdupeString = '';
        $this->financialYear = trim(date('Y',strtotime($toDate)));
    }

    /**
     * @return mixed
     */
    public function getPayerIdentity()
    {
        return $this->payerIdentity;
    }

    /**
     * @param mixed $payerIdentity
     */
    public function setPayerIdentity($payerIdentity)
    {
        $this->payerIdentity = $payerIdentity;
    }

    /**
     * @return mixed
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * @param mixed $supplier
     */
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;
    }

    /**
     * @return mixed
     */
    public function getPayeeArray()
    {
        return $this->payeeArray;
    }

    /**
     * @param mixed $payeeArray
     */
    public function setPayeeArray($payeeArray)
    {
        $this->payeeArray = $payeeArray;
    }

    /**
     * @return mixed
     */
    public function getGeneratedDate()
    {
        return $this->generatedDate;
    }

    /**
     * @param mixed $generatedDate
     */
    public function setGeneratedDate($generatedDate): void
    {
        $this->generatedDate = $generatedDate;
    }

    /**
     * @return mixed
     */
    public function getEmpdupeString()
    {
        return $this->empdupeString;
    }

    /**
     * @param mixed $empdupeString
     */
    public function setEmpdupeString($empdupeString): void
    {
        $this->empdupeString = $empdupeString;
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

    /**
     * @return string
     */
    public function getFromDate(): string
    {
        return $this->fromDate;
    }

    /**
     * @param string $fromDate
     */
    public function setFromDate(string $fromDate): void
    {
        $this->fromDate = $fromDate;
    }

    /**
     * @return string
     */
    public function getToDate(): string
    {
        return $this->toDate;
    }

    /**
     * @param string $toDate
     */
    public function setToDate(string $toDate): void
    {
        $this->toDate = $toDate;
    }

    /*public function __construct($fromDate,$toDate)
    {
        $this->fromDate = $fromDate->format("d").$fromDate->format("m").$fromDate->format("Y");
        $this->toDate = $toDate->format("d").$toDate->format("m").$toDate->format("Y");
    }*/

    private function addLine($line, $crlf = true)
    {
        //$this->empdupeString .= $line.($crlf ? "\r\n" : "");
        $this->empdupeString .= $line;
    }

    public function generate($payerIdentity,$supplier,$payeeArray){

        $this->supplierDataRecord1($supplier);
        $this->supplierDataRecord2($supplier);
        $this->supplierDataRecord3($supplier);
        $this->addPayerIdentityRecord($payerIdentity);
        $this->addSoftwareRecord(new Software());

        if(!is_array($payeeArray)){
            $payeeArray = array($payeeArray);
        }
        $this->addEmployeeRecords($payeeArray);
        return $this->empdupeString;

    }

    private function supplierDataRecord1(Supplier $supplier){
        $line = self::RECORD_LENGTH;
        $line .= self::IDENT_REGISTER.'1';
        $line .= $supplier->getAbn();
        $line .= self::RUN_TYPE;
        $line .= $this->generatedDate;
        $line .= self::DATA_TYPE;
        $line .= self::TYPE_OF_REPORT;
        $line .= self::FORMAT_OF_RETURN_MEDIA;
        $line .= self::REPORT_SPECIFICATION_VERSION_NUMBER;
        $line .= str_repeat(' ', 578);
        $this->addLine($line,true);
    }
    private function supplierDataRecord2(Supplier $supplier){
        $line = self::RECORD_LENGTH;
        $line .= self::IDENT_REGISTER.'2';
        $line .= str_pad($supplier->getSupplierName(),200,' ',STR_PAD_RIGHT);
        $line .= str_pad($supplier->getContactName(),38,' ',STR_PAD_RIGHT);
        $line .= str_pad($supplier->getContactNumber(),15,' ',STR_PAD_BOTH);
        $line .= str_pad($supplier->getFacsmileNumber(),15,' ',STR_PAD_BOTH);
        $line .= str_pad($supplier->getFileReferenceNumber(),16,' ',STR_PAD_BOTH);
        $line .= str_repeat(' ', 327);
        $this->addLine($line,true);
    }
    private function supplierDataRecord3(Supplier $supplier){
        $line = self::RECORD_LENGTH;
        $line .= self::IDENT_REGISTER.'3';
        $line .= str_pad($supplier->getAddressLine1(),38,' ',STR_PAD_RIGHT);
        $line .= str_pad($supplier->getAddressLine2(),38,' ',STR_PAD_RIGHT);
        $line .= str_pad($supplier->getSuburb(),27,' ',STR_PAD_RIGHT);
        $line .= str_pad($supplier->getState(),3,' ',STR_PAD_BOTH);
        $line .= str_pad($supplier->getPostCode(),4,' ',STR_PAD_BOTH);
        $line .= str_pad($supplier->getCountry(),20,' ',STR_PAD_BOTH);
        $line .= str_pad($supplier->getPostalAddressLine1(),38,' ',STR_PAD_RIGHT);
        $line .= str_pad($supplier->getPostalAddressLine2(),38,' ',STR_PAD_RIGHT);
        $line .= str_pad($supplier->getPostalSuburb(),27,' ',STR_PAD_RIGHT);
        $line .= str_pad($supplier->getPostalState(),3,' ',STR_PAD_BOTH);
        $line .= str_pad($supplier->getPostalPostCode(),4,' ',STR_PAD_BOTH);
        $line .= str_pad($supplier->getPostalCountry(),20,' ',STR_PAD_BOTH);
        $line .= str_pad($supplier->getEmailAddress(),76,' ',STR_PAD_RIGHT);
        $line .= str_repeat(' ', 275);
        $this->addLine($line,true);
    }
    private function addPayerIdentityRecord(PayerIdentity $payerIdentity){
        $line = self::RECORD_LENGTH;
        $line .= self::RECORD_IDENTIFIER;
        $line .= str_pad($payerIdentity->getPayerAbn(),11,' ',STR_PAD_BOTH);
        $line .= str_pad($payerIdentity->getBranchNumber(),3,' ',STR_PAD_BOTH);
        $line .= str_pad($payerIdentity->getFinancialYear(),4,' ',STR_PAD_BOTH);
        $line .= str_pad($payerIdentity->getPayerName(),200,' ',STR_PAD_RIGHT);
        $line .= str_pad($payerIdentity->getTradingName(),200,' ',STR_PAD_RIGHT);
        $line .= str_pad($payerIdentity->getPayerAddressLine1(),38,' ',STR_PAD_RIGHT);
        $line .= str_pad($payerIdentity->getPayerAddressLine2(),38,' ',STR_PAD_RIGHT);
        $line .= str_pad($payerIdentity->getPayerSuburb(),27,' ',STR_PAD_RIGHT);
        $line .= str_pad($payerIdentity->getPayerState(),3,' ',STR_PAD_BOTH);
        $line .= str_pad($payerIdentity->getPayerPostCode(),4,' ',STR_PAD_BOTH);
        $line .= str_pad($payerIdentity->getPayerCountry(),20,' ',STR_PAD_BOTH);
        $line .= str_pad($payerIdentity->getPayerContactName(),38,' ',STR_PAD_RIGHT);
        $line .= str_pad($payerIdentity->getPayerContactPhone(),15,' ',STR_PAD_BOTH);
        $line .= str_pad($payerIdentity->getPayerContactFacsimileNumber(),15,' ',STR_PAD_BOTH);
        $line .= str_repeat(' ', 1);
        $this->addLine($line,true);
    }
    private function addSoftwareRecord(Software $software){
        $line = self::RECORD_LENGTH;
        $line .= str_pad($software->getRecordIdentifier(),8,' ',STR_PAD_BOTH);
        $line .= str_pad($software->getProductType(),80,' ',STR_PAD_RIGHT);
        $line .= str_repeat(' ', 537);
        $this->addLine($line,true);
    }
    private function addEmployeeRecords($payeeArray){
        $i = 0;
        $len = count($payeeArray);
        foreach ($payeeArray as $emp) {
            if ($i == 0) {

            } else if ($i == $len - 1) {
                $line = self::RECORD_LENGTH;
                $line .= str_pad($emp->getRecordIdentifier(),3,' ',STR_PAD_BOTH);
                $line .= str_pad($emp->getIncomeType(),1,' ',STR_PAD_BOTH);
                $line .= str_pad($emp->getTfn(),9,' ',STR_PAD_BOTH);
                $line .= str_pad(date('dmY',strtotime($emp->getDob())),8,' ',STR_PAD_RIGHT);
                $line .= str_pad($emp->getLastName(),30,' ',STR_PAD_RIGHT);
                $line .= str_pad(substr($emp->getFirstName(),15),15,' ',STR_PAD_RIGHT);
                $line .= str_pad($emp->getMiddleName(),15,' ',STR_PAD_RIGHT);
                $line .= str_pad($emp->getResidentialAddressLine1(),38,' ',STR_PAD_RIGHT);
                $line .= str_pad($emp->getResidentialAddressLine2(),38,' ',STR_PAD_RIGHT);
                $line .= str_pad($emp->getSuburb(),27,' ',STR_PAD_RIGHT);
                $line .= str_pad($emp->getState(),3,' ',STR_PAD_RIGHT);
                $line .= str_pad($emp->getPostCode(),4, ' ',STR_PAD_RIGHT);
                $line .= str_pad($emp->getCountry(),20, ' ',STR_PAD_BOTH);
                $line .= str_pad($this->getFromDate(),8, ' ',STR_PAD_BOTH);
                $line .= str_pad($this->getToDate(),8, ' ',STR_PAD_BOTH);
                $line .= str_pad(round($emp->getTotalTaxWithHeld(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad(round($emp->getGrossPayment(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad(round($emp->getTotalAllowance(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad(round($emp->getLumpsumA(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad(round($emp->getLumpsumB(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad(round($emp->getLumpsumD(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad(round($emp->getLumpsumE(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad(round($emp->getCommunityDevelopmentEmploymentProject(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad('',8,'0',STR_PAD_LEFT);//zero filler
                $line .= str_pad(round($emp->getReportableFringeBenefit(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad($emp->getAmendmentIndicator(),1,'',STR_PAD_BOTH);
                $line .= str_pad(round($emp->getReportableEmployerSuperAnnuationContribution(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad($emp->getLumpsumPaymentAType(),1,'',STR_PAD_BOTH);
                $line .= str_pad(round($emp->getWorkplaceGiving(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad(round($emp->getUnionFess(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad(round($emp->getForeignEmploymentIncome(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad(round($emp->getDeductiblePurchasePriceAnnuity(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad($emp->getFbtType(),1,'0',STR_PAD_BOTH);
                $line .= str_repeat(' ', 274);
                $this->addLine($line,true);
                $line .= self::RECORD_LENGTH;
                $line .= self::FILE_TOTAL.str_pad($len+6,8,'0',STR_PAD_LEFT);
                $line .= str_repeat(' ', 607);
                $this->addLine($line,true);
            }else{
                $line = self::RECORD_LENGTH;
                $line .= str_pad($emp->getRecordIdentifier(),3,' ',STR_PAD_BOTH);
                $line .= str_pad($emp->getIncomeType(),1,' ',STR_PAD_BOTH);
                $line .= str_pad($emp->getTfn(),9,' ',STR_PAD_BOTH);
                $line .= str_pad(date('dmY',strtotime($emp->getDob())),8,' ',STR_PAD_RIGHT);
                $line .= str_pad($emp->getLastName(),30,' ',STR_PAD_RIGHT);
                $line .= str_pad(substr($emp->getFirstName(),15),15,' ',STR_PAD_RIGHT);
                $line .= str_pad($emp->getMiddleName(),15,' ',STR_PAD_RIGHT);
                $line .= str_pad($emp->getResidentialAddressLine1(),38,' ',STR_PAD_RIGHT);
                $line .= str_pad($emp->getResidentialAddressLine2(),38,' ',STR_PAD_RIGHT);
                $line .= str_pad($emp->getSuburb(),27,' ',STR_PAD_RIGHT);
                $line .= str_pad($emp->getState(),3,' ',STR_PAD_RIGHT);
                $line .= str_pad($emp->getPostCode(),4, ' ',STR_PAD_RIGHT);
                $line .= str_pad($emp->getCountry(),20, ' ',STR_PAD_BOTH);
                $line .= str_pad($this->getFromDate(),8, ' ',STR_PAD_BOTH);
                $line .= str_pad($this->getToDate(),8, ' ',STR_PAD_BOTH);
                $line .= str_pad(round($emp->getTotalTaxWithHeld(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad(round($emp->getGrossPayment(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad(round($emp->getTotalAllowance(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad(round($emp->getLumpsumA(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad(round($emp->getLumpsumB(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad(round($emp->getLumpsumD(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad(round($emp->getLumpsumE(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad(round($emp->getCommunityDevelopmentEmploymentProject(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad('',8,'0',STR_PAD_LEFT);//zero filler
                $line .= str_pad(round($emp->getReportableFringeBenefit(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad($emp->getAmendmentIndicator(),1,'',STR_PAD_BOTH);
                $line .= str_pad(round($emp->getReportableEmployerSuperAnnuationContribution(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad($emp->getLumpsumPaymentAType(),1,'',STR_PAD_BOTH);
                $line .= str_pad(round($emp->getWorkplaceGiving(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad(round($emp->getUnionFess(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad(round($emp->getForeignEmploymentIncome(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad(round($emp->getDeductiblePurchasePriceAnnuity(),0),8,'0',STR_PAD_LEFT);
                $line .= str_pad($emp->getFbtType(),1,'0',STR_PAD_BOTH);
                $line .= str_repeat(' ', 274);
                $this->addLine($line,true);
            }
            $i++;
        }
    }

}