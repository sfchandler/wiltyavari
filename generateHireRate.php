<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once "includes/TCPDF-main/tcpdf.php";
ini_set('memory_limit', '3072M');
date_default_timezone_set('Australia/Melbourne');
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$client = $_POST['client'];//getClientNameByClientId($mysqli,$_POST['clientId']);
$position = $_POST['position'];//getCandidatePositionNameById($mysqli,$_POST['positionId']);
$position2 = $_POST['position2'];
$position3 = $_POST['position3'];
$position4 = $_POST['position4'];

$award = getAwardById($mysqli,$_POST['award']);
$breakdown = $_POST['breakdown'];

$hourly_rate = $_POST['hourly_rate'];
$superannuation = $_POST['superannuation'];
$payroll_tax = $_POST['payroll_tax'];
$mhws = $_POST['mhws'];
$workcover = $_POST['workcover'];
$margin = $_POST['margin'];

$hourly_rate2 = $_POST['hourly_rate2'];
$superannuation2 = $_POST['superannuation2'];
$payroll_tax2 = $_POST['payroll_tax2'];
$mhws2 = $_POST['mhws2'];
$workcover2 = $_POST['workcover2'];
$margin2 = $_POST['margin2'];

$hourly_rate3 = $_POST['hourly_rate3'];
$superannuation3 = $_POST['superannuation3'];
$payroll_tax3 = $_POST['payroll_tax3'];
$mhws3 = $_POST['mhws3'];
$workcover3 = $_POST['workcover3'];
$margin3 = $_POST['margin3'];

$hourly_rate4 = $_POST['hourly_rate4'];
$superannuation4 = $_POST['superannuation4'];
$payroll_tax4 = $_POST['payroll_tax4'];
$mhws4 = $_POST['mhws4'];
$workcover4 = $_POST['workcover4'];
$margin4 = $_POST['margin4'];

$payment_terms = $_POST['payment_terms'];
$client_logo = $_POST['client_logo'];

class HIRERATEPDF extends TCPDF {
    //Page header
    /*public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES.'logo_example.jpg';
        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }*/
    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('calibri', 'R', 8);
        // Page number
        $this->Cell(0,10,DOMAIN_NAME,0,false, 'C', 0, '', 0, false, 'T', 'M');
        $this->SetY(-10);
        $this->Cell(0,10,DOMAIN_NAME.' |   |   Melbourne VIC 3000',0,false, 'C', 0, '', 0, false, 'T', 'M');
        //$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

class HireRate{
    const ANNUAL_WAGE = 44388.86;
    const WEEKLY_HOURS = 38;
    const CASUAL_LOADING = 0.25;
    const AFTERNOON_LOADING = 0.15;
    const RT_AFTERNOON_LOADING = 0.25;
    const EARLY_MORNING_LOADING = 0.125;
    const NIGHT_LOADING = 0.30;
    const T15_OVERTIME = 1.50;
    const T2_OVERTIME = 2.00;
    const T25_OVERTIME = 2.50;
    const RTT2_OVERTIME = 1.75;
    const RTT25_OVERTIME = 2.25;
    const FOOD_AWD = 'Food Beverage and Tobacco Manufacturing Award';
    const STORAGE_AWD = 'Storage Services and Wholesale Award';
    const RETAIL_AWD = 'General Retail Industry Award';

    public $client;
    public $position;
    public $position2;
    public $position3;
    public $position4;
    public $award;
    public $breakdown;
    public $hourly_rate;
    public $superannuation;
    public $payroll_tax;
    public $mhws;
    public $workcover;
    public $margin;
    public $hourly_rate2;
    public $superannuation2;
    public $payroll_tax2;
    public $mhws2;
    public $workcover2;
    public $margin2;
    public $hourly_rate3;
    public $superannuation3;
    public $payroll_tax3;
    public $mhws3;
    public $workcover3;
    public $margin3;
    public $hourly_rate4;
    public $superannuation4;
    public $payroll_tax4;
    public $mhws4;
    public $workcover4;
    public $margin4;
    public $payment_terms;
    public $client_logo;

    public function __construct($client,$position,$position2,$position3,$position4,$award,$breakdown,$hourly_rate,$superannuation,$payroll_tax,$mhws,$workcover,$margin,$hourly_rate2,$superannuation2,$payroll_tax2,$mhws2,$workcover2,$margin2,$hourly_rate3,$superannuation3,$payroll_tax3,$mhws3,$workcover3,$margin3,$hourly_rate4,$superannuation4,$payroll_tax4,$mhws4,$workcover4,$margin4,$payment_terms,$client_logo)
    {
        $this->client = $client;
        $this->position = $position;
        $this->position2 = $position2;
        $this->position3 = $position3;
        $this->position4 = $position4;
        $this->award = $award;
        $this->breakdown = $breakdown;
        $this->hourly_rate = $hourly_rate;
        $this->superannuation = $superannuation;
        $this->payroll_tax = $payroll_tax;
        $this->mhws = $mhws;
        $this->workcover = $workcover;
        $this->margin = $margin;

        $this->hourly_rate2 = $hourly_rate2;
        $this->superannuation2 = $superannuation2;
        $this->payroll_tax2 = $payroll_tax2;
        $this->mhws2 = $mhws2;
        $this->workcover2 = $workcover2;
        $this->margin2 = $margin2;

        $this->hourly_rate3 = $hourly_rate3;
        $this->superannuation3 = $superannuation3;
        $this->payroll_tax3 = $payroll_tax3;
        $this->mhws3 = $mhws3;
        $this->workcover3 = $workcover3;
        $this->margin3 = $margin3;

        $this->hourly_rate4 = $hourly_rate4;
        $this->superannuation4 = $superannuation4;
        $this->payroll_tax4 = $payroll_tax4;
        $this->mhws4 = $mhws4;
        $this->workcover4 = $workcover4;
        $this->margin4 = $margin4;


        $this->payment_terms = $payment_terms;
        $this->client_logo = $client_logo;
    }
    /*public function hireRatesForTwoPositions($client,$position,$award,$breakdown,$hourly_rate,$superannuation,$payroll_tax,$mhws,$workcover,$margin,$hourly_rate2,$superannuation2,$payroll_tax2,$mhws2,$workcover2,$margin2,$payment_terms,$client_logo)
    {
        $this->client = $client;
        $this->position = $position;
        $this->award = $award;
        $this->breakdown = $breakdown;
        $this->hourly_rate = $hourly_rate;
        $this->superannuation = $superannuation;
        $this->payroll_tax = $payroll_tax;
        $this->mhws = $mhws;
        $this->workcover = $workcover;
        $this->margin = $margin;
        $this->hourly_rate2 = $hourly_rate2;
        $this->superannuation2 = $superannuation2;
        $this->payroll_tax2 = $payroll_tax2;
        $this->mhws2 = $mhws2;
        $this->workcover2 = $workcover2;
        $this->margin2 = $margin2;
        $this->payment_terms = $payment_terms;
        $this->client_logo = $client_logo;
    }*/
    public function calculateHireRate(){

        $pdf = new HIRERATEPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setHeaderTemplateAutoreset(true);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('');
        $pdf->SetTitle('');
        $pdf->SetSubject('');
        $pdf->SetKeywords('');
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 8));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        define('PDF_CUSTOM_HEADER_STRING',' ');
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_CUSTOM_HEADER_STRING);
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        $pdf->SetFont('calibri', '', 9);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        $pdf->AddPage();
        $html = '';
        $html = $html.'<style>
                        tr{ 
                        text-transform: uppercase;
                        }
                        table {
                            border-color:gray;
                        }
                        th{
                            text-align: center;
                            background-color: #5b9ad5;
                            color: #FFFFFF;
                        }
                        .zebra0{
                            background-color: #cbd2d5;
                        }
                        .zebra1{
                            background-color: white;
                        }
                        </style>';
        $hourly_rate = $this->hourly_rate;
        $fulltime_t15 = number_format(($hourly_rate * self::T15_OVERTIME),2);
        $fulltime_t2 = number_format(($hourly_rate * self::T2_OVERTIME),2);
        $fulltime_holiday = number_format(($hourly_rate * self::T25_OVERTIME),2);
        $fulltime_early = number_format(($hourly_rate * (1 + self::EARLY_MORNING_LOADING)),2);
        $fulltime_afternoon = number_format(($hourly_rate * (1 + self::AFTERNOON_LOADING)),2);
        $fulltime_night = number_format(($hourly_rate * (1 + self::NIGHT_LOADING)),2);
        $fulltime_saturday = number_format(($hourly_rate * (self::T15_OVERTIME)),2);
        $fulltime_sunday = number_format(($hourly_rate * (self::T2_OVERTIME)),2);

        if($this->award == self::STORAGE_AWD){
            $casual_t1 = number_format(($hourly_rate * self::CASUAL_LOADING), 2);
            $casual_t15 = $casual_t1;
            $casual_t2 = $casual_t1;
            $casual_holiday = $casual_t1;
            $casual_early = $casual_t1;
            $casual_afternoon = $casual_t1;
            $casual_night = $casual_t1;
            $casual_saturday = $casual_t1;
            $casual_sunday = $casual_t1;

            $pay_rate_t1 = number_format(($hourly_rate + $casual_t1), 2);
            $pay_rate_t15 = number_format(($fulltime_t15 + $casual_t15), 2);
            $pay_rate_t2 = number_format(($fulltime_t2 + $casual_t2), 2);
            $pay_rate_holiday = number_format(($fulltime_holiday + $casual_holiday), 2);
            $pay_rate_early = number_format(($fulltime_early + $casual_early), 2);
            $pay_rate_afternoon = number_format(($fulltime_afternoon + $casual_afternoon), 2);
            $pay_rate_night = number_format(($fulltime_night + $casual_night), 2);
            $pay_rate_saturday = number_format(($fulltime_saturday + $casual_saturday), 2);
            $pay_rate_sunday = number_format(($fulltime_sunday + $casual_sunday), 2);
            //($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD
        }elseif($this->award == self::FOOD_AWD){
            $casual_t1 = number_format(($hourly_rate * self::CASUAL_LOADING), 2);
            $casual_t15 = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_t2 = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);
            $casual_holiday = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T25_OVERTIME), 2);
            $casual_early = number_format(($casual_t1 * (1 + self::EARLY_MORNING_LOADING) + 0.01),2);
            $casual_afternoon = number_format((($hourly_rate * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1)), 2);
            $casual_night = number_format((($hourly_rate * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1)), 2);
            $casual_saturday = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_sunday = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);

            $pay_rate_t1 = number_format(($hourly_rate + $casual_t1), 2);
            $pay_rate_t15 = number_format(($fulltime_t15 + $casual_t15), 2);
            $pay_rate_t2 = number_format(($fulltime_t2 + $casual_t2), 2);
            $pay_rate_holiday = number_format(($fulltime_holiday + $casual_holiday), 2);
            $pay_rate_early = number_format(($fulltime_early + $casual_early), 2);
            $pay_rate_afternoon = number_format(($fulltime_afternoon + $casual_afternoon), 2);
            $pay_rate_night = number_format(($fulltime_night + $casual_night), 2);
            $pay_rate_saturday = number_format(($fulltime_saturday + $casual_saturday), 2);
            $pay_rate_sunday = number_format(($fulltime_sunday + $casual_sunday), 2);
        }elseif($this->award == self::RETAIL_AWD){
            $fulltime_t2 = number_format(($hourly_rate * self::RTT2_OVERTIME),2);
            $fulltime_holiday = number_format(($hourly_rate * self::RTT25_OVERTIME),2);
            $fulltime_afternoon = number_format(($hourly_rate * (1 + self::RT_AFTERNOON_LOADING)),2);
            $fulltime_sunday = number_format(($hourly_rate * (self::RTT2_OVERTIME)),2);

            $casual_t1 = number_format(($hourly_rate * self::CASUAL_LOADING), 2);
            $casual_t15 = $casual_t1;
            $casual_t2 = $casual_t1;
            $casual_holiday = $casual_t1;
            $casual_early = $casual_t1;
            $casual_afternoon = $casual_t1;
            $casual_night = $casual_t1;
            $casual_saturday = $casual_t1;
            $casual_sunday = $casual_t1;

            $pay_rate_t1 = number_format(($hourly_rate + $casual_t1), 2);
            $pay_rate_t15 = number_format(($fulltime_t15 + $casual_t15), 2);
            $pay_rate_t2 = number_format(($fulltime_t2 + $casual_t2), 2);
            $pay_rate_holiday = number_format(($fulltime_holiday + $casual_holiday), 2);
            $pay_rate_early = number_format(($fulltime_early + $casual_early), 2);
            $pay_rate_afternoon = number_format(($fulltime_afternoon + $casual_afternoon), 2);
            $pay_rate_night = number_format(($fulltime_night + $casual_night), 2);
            $pay_rate_saturday = number_format(($fulltime_saturday + $casual_saturday), 2);
            $pay_rate_sunday = number_format(($fulltime_sunday + $casual_sunday), 2);
        }else {
            $casual_t1 = number_format(($hourly_rate * self::CASUAL_LOADING), 2);
            $casual_t15 = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_t2 = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);
            $casual_holiday = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T25_OVERTIME), 2);
            $casual_afternoon = number_format((($hourly_rate * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1)), 2);
            $casual_night = number_format((($hourly_rate * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1)), 2);
            $casual_saturday = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_sunday = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);

            $pay_rate_t1 = number_format(($hourly_rate + ($hourly_rate * self::CASUAL_LOADING)), 2);
            $pay_rate_t15 = number_format((($hourly_rate * self::T15_OVERTIME) + (($hourly_rate * self::CASUAL_LOADING) * self::T15_OVERTIME)), 2);
            $pay_rate_t2 = number_format((($hourly_rate * self::T2_OVERTIME) + (($hourly_rate * self::CASUAL_LOADING) * self::T2_OVERTIME)), 2);
            $pay_rate_holiday = number_format((($hourly_rate * self::T25_OVERTIME) + (($hourly_rate * self::CASUAL_LOADING) * self::T25_OVERTIME)), 2);
            $pay_rate_afternoon = number_format((($hourly_rate * (1 + self::AFTERNOON_LOADING)) + (($hourly_rate * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1))), 2);
            $pay_rate_night = number_format((($hourly_rate * (1 + self::NIGHT_LOADING)) + (($hourly_rate * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1))), 2);
            $pay_rate_saturday = number_format((($hourly_rate * (self::T15_OVERTIME)) + (($hourly_rate * self::CASUAL_LOADING) * self::T15_OVERTIME)), 2);
            $pay_rate_sunday = number_format((($hourly_rate * (self::T2_OVERTIME)) + (($hourly_rate * self::CASUAL_LOADING) * self::T2_OVERTIME)), 2);
        }
        $super_t1 = number_format((($pay_rate_t1) * $this->superannuation),2);
        $super_t15 = number_format((0),2);
        $super_t2 = number_format((0),2);
        $super_holiday = number_format((($pay_rate_holiday) * $this->superannuation),2);
        $super_early = number_format((($pay_rate_early) * $this->superannuation),2);
        $super_afternoon = number_format((($pay_rate_afternoon) * $this->superannuation),2);
        $super_night = number_format((($pay_rate_night) * $this->superannuation),2);
        $super_saturday = number_format((($pay_rate_saturday) * $this->superannuation),2);
        $super_sunday = number_format((($pay_rate_sunday) * $this->superannuation),2);

        $payrollTax_t1 = number_format((($pay_rate_t1 + $super_t1) * $this->payroll_tax),2);
        $payrollTax_t15 = number_format((($pay_rate_t15 + $super_t15) * $this->payroll_tax),2);
        $payrollTax_t2 = number_format((($pay_rate_t2 + $super_t2) * $this->payroll_tax),2);
        $payrollTax_holiday = number_format((($pay_rate_holiday + $super_holiday) * $this->payroll_tax),2);
        $payrollTax_early = number_format((($pay_rate_early + $super_early) * $this->payroll_tax),2);
        $payrollTax_afternoon = number_format((($pay_rate_afternoon + $super_afternoon) * $this->payroll_tax),2);
        $payrollTax_night = number_format((($pay_rate_night + $super_night) * $this->payroll_tax),2);
        $payrollTax_saturday = number_format((($pay_rate_saturday + $super_saturday) * $this->payroll_tax),2);
        $payrollTax_sunday = number_format((($pay_rate_sunday + $super_sunday) * $this->payroll_tax),2);

        $mhws_t1 = number_format((($pay_rate_t1 + $super_t1) * $this->mhws),2);
        $mhws_t15 = number_format((($pay_rate_t15 + $super_t15) * $this->mhws),2);
        $mhws_t2 = number_format((($pay_rate_t2 + $super_t2) * $this->mhws),2);
        $mhws_holiday = number_format((($pay_rate_holiday + $super_holiday) * $this->mhws),2);
        $mhws_early = number_format((($pay_rate_early + $super_early) * $this->mhws),2);
        $mhws_afternoon = number_format((($pay_rate_afternoon + $super_afternoon) * $this->mhws),2);
        $mhws_night = number_format((($pay_rate_night + $super_night) * $this->mhws),2);
        $mhws_saturday = number_format((($pay_rate_saturday + $super_saturday) * $this->mhws),2);
        $mhws_sunday = number_format((($pay_rate_sunday + $super_sunday) * $this->mhws),2);

        $workcover_t1 = number_format((($pay_rate_t1 + $super_t1) * $this->workcover),2);
        $workcover_t15 = number_format((($pay_rate_t15 + $super_t15) * $this->workcover),2);
        $workcover_t2 = number_format((($pay_rate_t2 + $super_t2) * $this->workcover),2);
        $workcover_holiday = number_format((($pay_rate_holiday + $super_holiday) * $this->workcover),2);
        $workcover_early = number_format((($pay_rate_early + $super_early) * $this->workcover),2);
        $workcover_afternoon = number_format((($pay_rate_afternoon + $super_afternoon) * $this->workcover),2);
        $workcover_night = number_format((($pay_rate_night + $super_night) * $this->workcover),2);
        $workcover_saturday = number_format((($pay_rate_saturday + $super_saturday) * $this->workcover),2);
        $workcover_sunday = number_format((($pay_rate_sunday + $super_sunday) * $this->workcover),2);

        $loadedcost_t1 = number_format(($pay_rate_t1 + $super_t1 + $payrollTax_t1 + $mhws_t1 + $workcover_t1),2);
        $loadedcost_t15 = number_format(($pay_rate_t15 + $super_t15 + $payrollTax_t15 + $mhws_t15 + $workcover_t15),2);
        $loadedcost_t2 = number_format(($pay_rate_t2 + $super_t2 + $payrollTax_t2 + $mhws_t2 + $workcover_t2),2);
        $loadedcost_holiday = number_format(($pay_rate_holiday + $super_holiday + $payrollTax_holiday + $mhws_holiday + $workcover_holiday),2);
        $loadedcost_early = number_format(($pay_rate_early + $super_early + $payrollTax_early + $mhws_early + $workcover_early),2);
        $loadedcost_afternoon = number_format(($pay_rate_afternoon + $super_afternoon + $payrollTax_afternoon + $mhws_afternoon + $workcover_afternoon),2);
        $loadedcost_night = number_format(($pay_rate_night + $super_night + $payrollTax_night + $mhws_night + $workcover_night),2);
        $loadedcost_saturday = number_format(($pay_rate_saturday + $super_saturday + $payrollTax_saturday + $mhws_saturday + $workcover_saturday),2);
        $loadedcost_sunday = number_format(($pay_rate_sunday + $super_sunday + $payrollTax_sunday + $mhws_sunday + $workcover_sunday),2);

        $placement_fee = number_format($this->margin,2);

        $chargeRate_t1 = number_format(($placement_fee + $loadedcost_t1),2);
        $chargeRate_t15 = number_format(($placement_fee + $loadedcost_t15),2);
        $chargeRate_t2 = number_format(($placement_fee + $loadedcost_t2),2);
        $chargeRate_holiday = number_format(($placement_fee + $loadedcost_holiday),2);
        $chargeRate_early = number_format(($placement_fee + $loadedcost_early),2);
        $chargeRate_afternoon = number_format(($placement_fee + $loadedcost_afternoon),2);
        $chargeRate_night = number_format(($placement_fee + $loadedcost_night),2);
        $chargeRate_saturday = number_format(($placement_fee + $loadedcost_saturday),2);
        $chargeRate_sunday = number_format(($placement_fee + $loadedcost_sunday),2);

        $gst_t1 = number_format(($chargeRate_t1 * 0.1),2);
        $gst_t15 = number_format(($chargeRate_t15 * 0.1),2);
        $gst_t2 = number_format(($chargeRate_t2 * 0.1),2);
        $gst_holiday = number_format(($chargeRate_holiday * 0.1),2);
        $gst_early = number_format(($chargeRate_early * 0.1),2);
        $gst_afternoon = number_format(($chargeRate_afternoon * 0.1),2);
        $gst_night = number_format(($chargeRate_night * 0.1),2);
        $gst_saturday = number_format(($chargeRate_saturday * 0.1),2);
        $gst_sunday = number_format(($chargeRate_sunday * 0.1),2);

        $chg_gst_t1 = number_format(($chargeRate_t1),2);
        $chg_gst_t15 = number_format(($chargeRate_t15),2);
        $chg_gst_t2 = number_format(($chargeRate_t2),2);
        $chg_gst_holiday = number_format(($chargeRate_holiday),2);
        $chg_gst_early = number_format(($chargeRate_early),2);
        $chg_gst_afternoon = number_format(($chargeRate_afternoon),2);
        $chg_gst_night = number_format(($chargeRate_night),2);
        $chg_gst_saturday = number_format(($chargeRate_saturday),2);
        $chg_gst_sunday = number_format(($chargeRate_sunday),2);

        $html = $html.'<table cellspacing="0" style="background-color: #FFFFFF; border: none; border-color: #FFFFFF">
            <thead style="border: none; background-color: #FFFFFF; background: #FFFFFF">
              <tr style="border: none; background-color: #FFFFFF; background: #FFFFFF">
                <th style="border: none; background-color: #FFFFFF; background: #FFFFFF"></th>
                <th style="border: none; background-color: #FFFFFF; background: #FFFFFF"></th>
                <th style="border: none; background-color: #FFFFFF; background: #FFFFFF"></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="text-align: right; width: 35%"><span style="font-size: 12pt; color: #0d0f12; font-weight: bold; border: 1px solid red">Rates presented to:</span>
                    <br>
                        <span style="font-size: 10pt; color: #0AA699; font-weight: bold;">'.$this->client.'</span>
                    <br>
                    <br>
                        <span style="font-size: 12pt; color: #0d0f12; font-weight: bold">Award/EBA:</span>
                        <br>
                        <span style="font-size: 10pt; color: #0AA699; font-weight: bold">'.$this->award.'</span></td>
                <td></td>
                <td><img src="includes/TCPDF-master/ratespdf/images/cp_logo_new.png" width="260" height="82" border="0" alt=""/>
                    <br>
                    <img src="'.$this->client_logo.'"  alt="">
                </td>
              </tr>
            </tbody>
          </table>';
        $html = $html.'<br>';
        $html = $html.'';
        $html = $html.'<br></div>';

        $html = $html.'<br>';
        $html = $html.'<div style="font-size: 22pt; color: red; font-weight: bold">Labour <br>';
        $html = $html.'Hire Rates</div>';
        $html = $html.'<br>';
        $html = $html.'<div style="font-size: 14pt; font-weight: bold; font-style: italic"><b>Classification: '.$this->position.'</b></div>';
        $html = $html.'<br>';

        if($this->breakdown == 'CHARGE RATE'){
            $html = $html . '<div align="center"><table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <thead>
              <tr>
                <th style="width: 17%">Description</th>
                <th>T1.0 Ordinary</th>
                <th>T1.5 Overtime</th>
                <th>T2.0 Overtime</th>
                <th>Public Holiday</th>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<th>Early Morning</th>';
            }
            $html = $html.'<th>Afternoon</th>
                <th>Night</th>
                <th>Saturday</th>
                <th>Sunday</th>
              </tr>
            </thead>
            <tbody>';
            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Charge Rate</td>
                <td>' . $chg_gst_t1 . '</td>
                <td>' . $chg_gst_t15 . '</td>
                <td>' . $chg_gst_t2 . '</td>
                <td>' . $chg_gst_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$chg_gst_early.'</td>';
            }
            $html = $html.'<td>' . $chg_gst_afternoon . '</td>
                <td>' . $chg_gst_night . '</td>
                <td>' . $chg_gst_saturday . '</td>
                <td>' . $chg_gst_sunday . '</td></tr>';
            $html = $html . '</tbody>
          </table></div><br>';
        }else {
            $html = $html . '<div align="center"><table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <thead>
              <tr>
                <th style="width: 17%">Description</th>
                <th>T1.0 Ordinary</th>
                <th>T1.5 Overtime</th>
                <th>T2.0 Overtime</th>
                <th>Public Holiday</th>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<th>Early Morning</th>';
            }
            $html = $html.'<th>Afternoon</th>
                <th>Night</th>
                <th>Saturday</th>
                <th>Sunday</th>
              </tr>
            </thead>
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Fulltime Rate</td>
                <td>' . $hourly_rate . '</td>
                <td>' . $fulltime_t15 . '</td>
                <td>' . $fulltime_t2 . '</td>
                <td>' . $fulltime_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$fulltime_early.'</td>';
            }
            $html = $html.'<td>' . $fulltime_afternoon . '</td>
                <td>' . $fulltime_night . '</td>
                <td>' . $fulltime_saturday . '</td>
                <td>' . $fulltime_sunday . '</td></tr>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Casual Loading</td>
                <td>' . $casual_t1 . '</td>
                <td>' . $casual_t15 . '</td>
                <td>' . $casual_t2 . '</td>
                <td>' . $casual_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$casual_early.'</td>';
            }
            $html = $html.'<td>' . $casual_afternoon . '</td>
                <td>' . $casual_night . '</td>
                <td>' . $casual_saturday . '</td>
                <td>' . $casual_sunday . '</td></tr>';

            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Pay Rate</td>
                <td>' . $pay_rate_t1 . '</td>
                <td>' . $pay_rate_t15 . '</td>
                <td>' . $pay_rate_t2 . '</td>
                <td>' . $pay_rate_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$pay_rate_early.'</td>';
            }
            $html = $html.'<td>' . $pay_rate_afternoon . '</td>
                <td>' . $pay_rate_night . '</td>
                <td>' . $pay_rate_saturday . '</td>
                <td>' . $pay_rate_sunday . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Superannuation</td>
                <td>' . $super_t1 . '</td>
                <td>' . $super_t15 . '</td>
                <td>' . $super_t2 . '</td>
                <td>' . $super_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$super_early.'</td>';
            }
            $html = $html.'<td>' . $super_afternoon . '</td>
                <td>' . $super_night . '</td>
                <td>' . $super_saturday . '</td>
                <td>' . $super_sunday . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Payroll Tax</td>
                <td>' . $payrollTax_t1 . '</td>
                <td>' . $payrollTax_t15 . '</td>
                <td>' . $payrollTax_t2 . '</td>
                <td>' . $payrollTax_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$payrollTax_early.'</td>';
            }
            $html = $html.'<td>' . $payrollTax_afternoon . '</td>
                <td>' . $payrollTax_night . '</td>
                <td>' . $payrollTax_saturday . '</td>
                <td>' . $payrollTax_sunday . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">MHWS</td>
                <td>' . $mhws_t1 . '</td>
                <td>' . $mhws_t15 . '</td>
                <td>' . $mhws_t2 . '</td>
                <td>' . $mhws_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$mhws_early.'</td>';
            }
            $html = $html.'<td>' . $mhws_afternoon . '</td>
                <td>' . $mhws_night . '</td>
                <td>' . $mhws_saturday . '</td>
                <td>' . $mhws_sunday . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">WorkCover</td>
                <td>' . $workcover_t1 . '</td>
                <td>' . $workcover_t15 . '</td>
                <td>' . $workcover_t2 . '</td>
                <td>' . $workcover_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$workcover_early.'</td>';
            }
            $html = $html.'<td>' . $workcover_afternoon . '</td>
                <td>' . $workcover_night . '</td>
                <td>' . $workcover_saturday . '</td>
                <td>' . $workcover_sunday . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Loaded Cost</td>
                <td>' . $loadedcost_t1 . '</td>
                <td>' . $loadedcost_t15 . '</td>
                <td>' . $loadedcost_t2 . '</td>
                <td>' . $loadedcost_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$loadedcost_early.'</td>';
            }
            $html = $html.'<td>' . $loadedcost_afternoon . '</td>
                <td>' . $loadedcost_night . '</td>
                <td>' . $loadedcost_saturday . '</td>
                <td>' . $loadedcost_sunday . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Placement Fee</td>
                <td>' . $placement_fee . '</td>
                <td>' . $placement_fee . '</td>
                <td>' . $placement_fee . '</td>
                <td>' . $placement_fee . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$placement_fee.'</td>';
            }
            $html = $html.'<td>' . $placement_fee . '</td>
                <td>' . $placement_fee . '</td>
                <td>' . $placement_fee . '</td>
                <td>' . $placement_fee . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Charge Rate</td>
                <td>' . $chargeRate_t1 . '</td>
                <td>' . $chargeRate_t15 . '</td>
                <td>' . $chargeRate_t2 . '</td>
                <td>' . $chargeRate_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$chargeRate_early.'</td>';
            }
            $html = $html.'<td>' . $chargeRate_afternoon . '</td>
                <td>' . $chargeRate_night . '</td>
                <td>' . $chargeRate_saturday . '</td>
                <td>' . $chargeRate_sunday . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br>';
           /* $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">GST</td>
                <td>' . $gst_t1 . '</td>
                <td>' . $gst_t15 . '</td>
                <td>' . $gst_t2 . '</td>
                <td>' . $gst_holiday . '</td>
                <td>' . $gst_afternoon . '</td>
                <td>' . $gst_night . '</td>
                <td>' . $gst_saturday . '</td>
                <td>' . $gst_sunday . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Charge Rate Inc GST</td>
                <td>' . $chg_gst_t1 . '</td>
                <td>' . $chg_gst_t15 . '</td>
                <td>' . $chg_gst_t2 . '</td>
                <td>' . $chg_gst_holiday . '</td>
                <td>' . $chg_gst_afternoon . '</td>
                <td>' . $chg_gst_night . '</td>
                <td>' . $chg_gst_saturday . '</td>
                <td>' . $chg_gst_sunday . '</td></tr>';
            $html = $html . '</tbody>
          </table></div>';*/
            $html = $html . '</div>';
        }
        $html = $html.'<div style="text-align: right">Please note charge rates expressed are exclusive of GST.<br>Payment terms - '.$this->payment_terms.' Days from Invoice date</div><br>';
        $html = $html.'I agree to the rates and terms proposed by '.DOMAIN_NAME.' and understand the rules and regulations according to the '.$this->award;
        $html = $html.'<br><br>';
        /*$html = $html.'<table style="background-color: #FFFFFF; border: none; border-color: #FFFFFF">
                        <thead style="border: none; background-color: #FFFFFF; background: #FFFFFF">
                             <tr style="border: none; background-color: #FFFFFF; background: #FFFFFF">
                                <th style="border: none; background-color: #FFFFFF; background: #FFFFFF"></th>
                                <th style="border: none; background-color: #FFFFFF; background: #FFFFFF"></th>
                                <th style="border: none; background-color: #FFFFFF; background: #FFFFFF"></th>
                                <th style="border: none; background-color: #FFFFFF; background: #FFFFFF"></th>
                             </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td style="width: 45%">Name: ..............................................................................</td>
                            <td style="width: 5%"></td>
                            <td style="width: 20%">Received T&C s</td>
                            <td style="width: 20%"><input type="checkbox" name="box1" value="1" checked="" style="border: 1px solid black"/>Yes<input type="checkbox" name="box2" value="1" checked="" style="border: 1px solid black"/> No</td>
                          </tr>
                          <tr><td colspan="4"></td></tr>
                          <tr>
                            <td style="width: 45%">Title: ................................................................................</td>
                            <td style="width: 5%"></td>
                            <td style="width: 20%"></td>
                            <td style="width: 20%"></td>
                          </tr>
                          <tr><td colspan="4"></td></tr>
                           <tr>
                            <td style="width: 45%">Company: ..........................................................................</td>
                            <td style="width: 5%"></td>
                            <td style="width: 20%"></td>
                            <td style="width: 20%"></td>
                          </tr>
                          <tr><td colspan="4"></td></tr>
                            <tr>
                            <td style="width: 45%">Signature: ..........................................................................</td>
                            <td style="width: 5%"></td>
                            <td style="width: 20%">Date:</td>
                            <td style="width: 20%"></td>
                          </tr>
                        </tbody>
                      </table>';*/

        $fileName = 'hireRate_'.time().'-'.mt_rand();
        $filePathPDF = './rates/'.$fileName.'.pdf';
        $pdf->writeHTML($html, true, false, false, false, '');

        $pdf->lastPage();
        $pdf->Output(__DIR__.'/rates/'.$fileName.'.pdf', 'F');
        echo $filePathPDF;
    }
    public function calculateHireRateForTwoPositions(){

        $pdf = new HIRERATEPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setHeaderTemplateAutoreset(true);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('');
        $pdf->SetTitle('');
        $pdf->SetSubject('');
        $pdf->SetKeywords('');
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 8));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        define('PDF_CUSTOM_HEADER_STRING',' ');
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_CUSTOM_HEADER_STRING);
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        //$pdf->SetFont('helvetica', '', 9);
        /*$fontname = TCPDF_FONTS::addTTFfont("includes/TCPDF-master/fonts/Poppins-Light.ttf","TrueType","12");
        $pdf->SetFont($fontname, '', 9);*/
        $pdf->SetFont('calibri','',9);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        $pdf->AddPage();
        $html = '';
        $html = $html.'<style>
tr{ 
text-transform: uppercase;
}

table {
    border-color:gray;
}
th{
    text-align: center;
    background-color: #5b9ad5;
    color: #FFFFFF;
}
.zebra0{
    background-color: #cbd2d5;
}
.zebra1{
    background-color: white;
}
</style>';

        $hourly_rate = $this->hourly_rate;
        $fulltime_t15 = number_format(($hourly_rate * self::T15_OVERTIME),2);
        $fulltime_t2 = number_format(($hourly_rate * self::T2_OVERTIME),2);
        $fulltime_holiday = number_format(($hourly_rate * self::T25_OVERTIME),2);
        $fulltime_early = number_format(($hourly_rate * (1 + self::EARLY_MORNING_LOADING)),2);
        $fulltime_afternoon = number_format(($hourly_rate * (1 + self::AFTERNOON_LOADING)),2);
        $fulltime_night = number_format(($hourly_rate * (1 + self::NIGHT_LOADING)),2);
        $fulltime_saturday = number_format(($hourly_rate * (self::T15_OVERTIME)),2);
        $fulltime_sunday = number_format(($hourly_rate * (self::T2_OVERTIME)),2);

        if($this->award == self::STORAGE_AWD){
            $casual_t1 = number_format(($hourly_rate * self::CASUAL_LOADING), 2);
            $casual_t15 = $casual_t1;
            $casual_t2 = $casual_t1;
            $casual_holiday = $casual_t1;
            $casual_early = $casual_t1;
            $casual_afternoon = $casual_t1;
            $casual_night = $casual_t1;
            $casual_saturday = $casual_t1;
            $casual_sunday = $casual_t1;

            $pay_rate_t1 = number_format(($hourly_rate + $casual_t1), 2);
            $pay_rate_t15 = number_format(($fulltime_t15 + $casual_t15), 2);
            $pay_rate_t2 = number_format(($fulltime_t2 + $casual_t2), 2);
            $pay_rate_holiday = number_format(($fulltime_holiday + $casual_holiday), 2);
            $pay_rate_early = number_format(($fulltime_early + $casual_early), 2);
            $pay_rate_afternoon = number_format(($fulltime_afternoon + $casual_afternoon), 2);
            $pay_rate_night = number_format(($fulltime_night + $casual_night), 2);
            $pay_rate_saturday = number_format(($fulltime_saturday + $casual_saturday), 2);
            $pay_rate_sunday = number_format(($fulltime_sunday + $casual_sunday), 2);

        }elseif($this->award == self::FOOD_AWD){
            $casual_t1 = number_format(($hourly_rate * self::CASUAL_LOADING), 2);
            $casual_t15 = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_t2 = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);
            $casual_holiday = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T25_OVERTIME), 2);
            $casual_early = number_format(($casual_t1 * (1 + self::EARLY_MORNING_LOADING) + 0.01),2);
            $casual_afternoon = number_format((($hourly_rate * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1)), 2);
            $casual_night = number_format((($hourly_rate * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1)), 2);
            $casual_saturday = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_sunday = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);

            $pay_rate_t1 = number_format(($hourly_rate + $casual_t1), 2);
            $pay_rate_t15 = number_format(($fulltime_t15 + $casual_t15), 2);
            $pay_rate_t2 = number_format(($fulltime_t2 + $casual_t2), 2);
            $pay_rate_holiday = number_format(($fulltime_holiday + $casual_holiday), 2);
            $pay_rate_early = number_format(($fulltime_early + $casual_early), 2);
            $pay_rate_afternoon = number_format(($fulltime_afternoon + $casual_afternoon), 2);
            $pay_rate_night = number_format(($fulltime_night + $casual_night), 2);
            $pay_rate_saturday = number_format(($fulltime_saturday + $casual_saturday), 2);
            $pay_rate_sunday = number_format(($fulltime_sunday + $casual_sunday), 2);
        }elseif($this->award == self::RETAIL_AWD){
            $fulltime_t2 = number_format(($hourly_rate * self::RTT2_OVERTIME),2);
            $fulltime_holiday = number_format(($hourly_rate * self::RTT25_OVERTIME),2);
            $fulltime_afternoon = number_format(($hourly_rate * (1 + self::RT_AFTERNOON_LOADING)),2);
            $fulltime_sunday = number_format(($hourly_rate * (self::RTT2_OVERTIME)),2);

            $casual_t1 = number_format(($hourly_rate * self::CASUAL_LOADING), 2);
            $casual_t15 = $casual_t1;
            $casual_t2 = $casual_t1;
            $casual_holiday = $casual_t1;
            $casual_early = $casual_t1;
            $casual_afternoon = $casual_t1;
            $casual_night = $casual_t1;
            $casual_saturday = $casual_t1;
            $casual_sunday = $casual_t1;

            $pay_rate_t1 = number_format(($hourly_rate + $casual_t1), 2);
            $pay_rate_t15 = number_format(($fulltime_t15 + $casual_t15), 2);
            $pay_rate_t2 = number_format(($fulltime_t2 + $casual_t2), 2);
            $pay_rate_holiday = number_format(($fulltime_holiday + $casual_holiday), 2);
            $pay_rate_early = number_format(($fulltime_early + $casual_early), 2);
            $pay_rate_afternoon = number_format(($fulltime_afternoon + $casual_afternoon), 2);
            $pay_rate_night = number_format(($fulltime_night + $casual_night), 2);
            $pay_rate_saturday = number_format(($fulltime_saturday + $casual_saturday), 2);
            $pay_rate_sunday = number_format(($fulltime_sunday + $casual_sunday), 2);
        }else {
            $casual_t1 = number_format(($hourly_rate * self::CASUAL_LOADING), 2);
            $casual_t15 = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_t2 = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);
            $casual_holiday = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T25_OVERTIME), 2);
            $casual_afternoon = number_format((($hourly_rate * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1)), 2);
            $casual_night = number_format((($hourly_rate * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1)), 2);
            $casual_saturday = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_sunday = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);

            $pay_rate_t1 = number_format(($hourly_rate + ($hourly_rate * self::CASUAL_LOADING)), 2);
            $pay_rate_t15 = number_format((($hourly_rate * self::T15_OVERTIME) + (($hourly_rate * self::CASUAL_LOADING) * self::T15_OVERTIME)), 2);
            $pay_rate_t2 = number_format((($hourly_rate * self::T2_OVERTIME) + (($hourly_rate * self::CASUAL_LOADING) * self::T2_OVERTIME)), 2);
            $pay_rate_holiday = number_format((($hourly_rate * self::T25_OVERTIME) + (($hourly_rate * self::CASUAL_LOADING) * self::T25_OVERTIME)), 2);
            $pay_rate_afternoon = number_format((($hourly_rate * (1 + self::AFTERNOON_LOADING)) + (($hourly_rate * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1))), 2);
            $pay_rate_night = number_format((($hourly_rate * (1 + self::NIGHT_LOADING)) + (($hourly_rate * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1))), 2);
            $pay_rate_saturday = number_format((($hourly_rate * (self::T15_OVERTIME)) + (($hourly_rate * self::CASUAL_LOADING) * self::T15_OVERTIME)), 2);
            $pay_rate_sunday = number_format((($hourly_rate * (self::T2_OVERTIME)) + (($hourly_rate * self::CASUAL_LOADING) * self::T2_OVERTIME)), 2);
        }
        $super_t1 = number_format((($pay_rate_t1) * $this->superannuation),2);
        $super_t15 = number_format((0),2);
        $super_t2 = number_format((0),2);
        $super_holiday = number_format((($pay_rate_holiday) * $this->superannuation),2);
        $super_early = number_format((($pay_rate_early) * $this->superannuation),2);
        $super_afternoon = number_format((($pay_rate_afternoon) * $this->superannuation),2);
        $super_night = number_format((($pay_rate_night) * $this->superannuation),2);
        $super_saturday = number_format((($pay_rate_saturday) * $this->superannuation),2);
        $super_sunday = number_format((($pay_rate_sunday) * $this->superannuation),2);

        $payrollTax_t1 = number_format((($pay_rate_t1 + $super_t1) * $this->payroll_tax),2);
        $payrollTax_t15 = number_format((($pay_rate_t15 + $super_t15) * $this->payroll_tax),2);
        $payrollTax_t2 = number_format((($pay_rate_t2 + $super_t2) * $this->payroll_tax),2);
        $payrollTax_holiday = number_format((($pay_rate_holiday + $super_holiday) * $this->payroll_tax),2);
        $payrollTax_early = number_format((($pay_rate_early + $super_early) * $this->payroll_tax),2);
        $payrollTax_afternoon = number_format((($pay_rate_afternoon + $super_afternoon) * $this->payroll_tax),2);
        $payrollTax_night = number_format((($pay_rate_night + $super_night) * $this->payroll_tax),2);
        $payrollTax_saturday = number_format((($pay_rate_saturday + $super_saturday) * $this->payroll_tax),2);
        $payrollTax_sunday = number_format((($pay_rate_sunday + $super_sunday) * $this->payroll_tax),2);

        $mhws_t1 = number_format((($pay_rate_t1 + $super_t1) * $this->mhws),2);
        $mhws_t15 = number_format((($pay_rate_t15 + $super_t15) * $this->mhws),2);
        $mhws_t2 = number_format((($pay_rate_t2 + $super_t2) * $this->mhws),2);
        $mhws_holiday = number_format((($pay_rate_holiday + $super_holiday) * $this->mhws),2);
        $mhws_early = number_format((($pay_rate_early + $super_early) * $this->mhws),2);
        $mhws_afternoon = number_format((($pay_rate_afternoon + $super_afternoon) * $this->mhws),2);
        $mhws_night = number_format((($pay_rate_night + $super_night) * $this->mhws),2);
        $mhws_saturday = number_format((($pay_rate_saturday + $super_saturday) * $this->mhws),2);
        $mhws_sunday = number_format((($pay_rate_sunday + $super_sunday) * $this->mhws),2);

        $workcover_t1 = number_format((($pay_rate_t1 + $super_t1) * $this->workcover),2);
        $workcover_t15 = number_format((($pay_rate_t15 + $super_t15) * $this->workcover),2);
        $workcover_t2 = number_format((($pay_rate_t2 + $super_t2) * $this->workcover),2);
        $workcover_holiday = number_format((($pay_rate_holiday + $super_holiday) * $this->workcover),2);
        $workcover_early = number_format((($pay_rate_early + $super_early) * $this->workcover),2);
        $workcover_afternoon = number_format((($pay_rate_afternoon + $super_afternoon) * $this->workcover),2);
        $workcover_night = number_format((($pay_rate_night + $super_night) * $this->workcover),2);
        $workcover_saturday = number_format((($pay_rate_saturday + $super_saturday) * $this->workcover),2);
        $workcover_sunday = number_format((($pay_rate_sunday + $super_sunday) * $this->workcover),2);

        $loadedcost_t1 = number_format(($pay_rate_t1 + $super_t1 + $payrollTax_t1 + $mhws_t1 + $workcover_t1),2);
        $loadedcost_t15 = number_format(($pay_rate_t15 + $super_t15 + $payrollTax_t15 + $mhws_t15 + $workcover_t15),2);
        $loadedcost_t2 = number_format(($pay_rate_t2 + $super_t2 + $payrollTax_t2 + $mhws_t2 + $workcover_t2),2);
        $loadedcost_holiday = number_format(($pay_rate_holiday + $super_holiday + $payrollTax_holiday + $mhws_holiday + $workcover_holiday),2);
        $loadedcost_early = number_format(($pay_rate_early + $super_early + $payrollTax_early + $mhws_early + $workcover_early),2);
        $loadedcost_afternoon = number_format(($pay_rate_afternoon + $super_afternoon + $payrollTax_afternoon + $mhws_afternoon + $workcover_afternoon),2);
        $loadedcost_night = number_format(($pay_rate_night + $super_night + $payrollTax_night + $mhws_night + $workcover_night),2);
        $loadedcost_saturday = number_format(($pay_rate_saturday + $super_saturday + $payrollTax_saturday + $mhws_saturday + $workcover_saturday),2);
        $loadedcost_sunday = number_format(($pay_rate_sunday + $super_sunday + $payrollTax_sunday + $mhws_sunday + $workcover_sunday),2);

        $placement_fee = number_format($this->margin,2);

        $chargeRate_t1 = number_format(($placement_fee + $loadedcost_t1),2);
        $chargeRate_t15 = number_format(($placement_fee + $loadedcost_t15),2);
        $chargeRate_t2 = number_format(($placement_fee + $loadedcost_t2),2);
        $chargeRate_holiday = number_format(($placement_fee + $loadedcost_holiday),2);
        $chargeRate_early = number_format(($placement_fee + $loadedcost_early),2);
        $chargeRate_afternoon = number_format(($placement_fee + $loadedcost_afternoon),2);
        $chargeRate_night = number_format(($placement_fee + $loadedcost_night),2);
        $chargeRate_saturday = number_format(($placement_fee + $loadedcost_saturday),2);
        $chargeRate_sunday = number_format(($placement_fee + $loadedcost_sunday),2);

        $gst_t1 = number_format(($chargeRate_t1 * 0.1),2);
        $gst_t15 = number_format(($chargeRate_t15 * 0.1),2);
        $gst_t2 = number_format(($chargeRate_t2 * 0.1),2);
        $gst_holiday = number_format(($chargeRate_holiday * 0.1),2);
        $gst_early = number_format(($chargeRate_early * 0.1),2);
        $gst_afternoon = number_format(($chargeRate_afternoon * 0.1),2);
        $gst_night = number_format(($chargeRate_night * 0.1),2);
        $gst_saturday = number_format(($chargeRate_saturday * 0.1),2);
        $gst_sunday = number_format(($chargeRate_sunday * 0.1),2);

        $chg_gst_t1 = number_format(($chargeRate_t1),2);
        $chg_gst_t15 = number_format(($chargeRate_t15),2);
        $chg_gst_t2 = number_format(($chargeRate_t2),2);
        $chg_gst_holiday = number_format(($chargeRate_holiday),2);
        $chg_gst_early = number_format(($chargeRate_early),2);
        $chg_gst_afternoon = number_format(($chargeRate_afternoon),2);
        $chg_gst_night = number_format(($chargeRate_night),2);
        $chg_gst_saturday = number_format(($chargeRate_saturday),2);
        $chg_gst_sunday = number_format(($chargeRate_sunday),2);

        /* --------------------------    hire rate 2    ---------------------------- */

        $hourly_rate2 = $this->hourly_rate2;
        $fulltime_t15_2 = number_format(($hourly_rate2 * self::T15_OVERTIME),2);
        $fulltime_t2_2 = number_format(($hourly_rate2 * self::T2_OVERTIME),2);
        $fulltime_holiday_2 = number_format(($hourly_rate2 * self::T25_OVERTIME),2);
        $fulltime_early_2 =  number_format(($hourly_rate2 * (1 + self::EARLY_MORNING_LOADING)),2);
        $fulltime_afternoon_2 = number_format(($hourly_rate2 * (1 + self::AFTERNOON_LOADING)),2);
        $fulltime_night_2 = number_format(($hourly_rate2 * (1 + self::NIGHT_LOADING)),2);
        $fulltime_saturday_2 = number_format(($hourly_rate2 * (self::T15_OVERTIME)),2);
        $fulltime_sunday_2 = number_format(($hourly_rate2 * (self::T2_OVERTIME)),2);

        if($this->award == self::STORAGE_AWD){
            $casual_t1_2 = number_format(($hourly_rate2 * self::CASUAL_LOADING), 2);
            $casual_t15_2 = $casual_t1_2;
            $casual_t2_2 = $casual_t1_2;
            $casual_holiday_2 = $casual_t1_2;
            $casual_early_2 = $casual_t1_2;
            $casual_afternoon_2 = $casual_t1_2;
            $casual_night_2 = $casual_t1_2;
            $casual_saturday_2 = $casual_t1_2;
            $casual_sunday_2 = $casual_t1_2;

            $pay_rate_t1_2 = number_format(($hourly_rate2 + $casual_t1_2), 2);
            $pay_rate_t15_2 = number_format(($fulltime_t15_2 + $casual_t15_2), 2);
            $pay_rate_t2_2 = number_format(($fulltime_t2_2 + $casual_t2_2), 2);
            $pay_rate_holiday_2 = number_format(($fulltime_holiday_2 + $casual_holiday_2), 2);
            $pay_rate_early_2 = number_format(($fulltime_early_2 + $casual_early_2), 2);
            $pay_rate_afternoon_2 = number_format(($fulltime_afternoon_2 + $casual_afternoon_2), 2);
            $pay_rate_night_2 = number_format(($fulltime_night_2 + $casual_night_2), 2);
            $pay_rate_saturday_2 = number_format(($fulltime_saturday_2 + $casual_saturday_2), 2);
            $pay_rate_sunday_2 = number_format(($fulltime_sunday_2 + $casual_sunday_2), 2);
        }elseif($this->award == self::FOOD_AWD){
            $casual_t1_2 = number_format(($hourly_rate2 * self::CASUAL_LOADING), 2);
            $casual_t15_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_t2_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);
            $casual_holiday_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T25_OVERTIME), 2);
            $casual_early_2 = number_format(($casual_t1_2 * (1 + self::EARLY_MORNING_LOADING) + 0.01),2);
            $casual_afternoon_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1)), 2);
            $casual_night_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1)), 2);
            $casual_saturday_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_sunday_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);

            $pay_rate_t1_2 = number_format(($hourly_rate2 + $casual_t1_2), 2);
            $pay_rate_t15_2 = number_format(($fulltime_t15_2 + $casual_t15_2), 2);
            $pay_rate_t2_2 = number_format(($fulltime_t2_2 + $casual_t2_2), 2);
            $pay_rate_holiday_2 = number_format(($fulltime_holiday_2 + $casual_holiday_2), 2);
            $pay_rate_early_2 = number_format(($fulltime_early_2 + $casual_early_2), 2);
            $pay_rate_afternoon_2 = number_format(($fulltime_afternoon_2 + $casual_afternoon_2), 2);
            $pay_rate_night_2 = number_format(($fulltime_night_2 + $casual_night_2), 2);
            $pay_rate_saturday_2 = number_format(($fulltime_saturday_2 + $casual_saturday_2), 2);
            $pay_rate_sunday_2 = number_format(($fulltime_sunday_2 + $casual_sunday_2), 2);
        }elseif($this->award == self::RETAIL_AWD){
            $fulltime_t2_2 = number_format(($hourly_rate2 * self::RTT2_OVERTIME),2);
            $fulltime_holiday_2 = number_format(($hourly_rate2 * self::RTT25_OVERTIME),2);
            $fulltime_afternoon_2 = number_format(($hourly_rate2 * (1 + self::RT_AFTERNOON_LOADING)),2);
            $fulltime_sunday_2 = number_format(($hourly_rate2 * (self::RTT2_OVERTIME)),2);

            $casual_t1_2 = number_format(($hourly_rate2 * self::CASUAL_LOADING), 2);
            $casual_t15_2 = $casual_t1_2;
            $casual_t2_2 = $casual_t1_2;
            $casual_holiday_2 = $casual_t1_2;
            $casual_early_2 = $casual_t1_2;
            $casual_afternoon_2 = $casual_t1_2;
            $casual_night_2 = $casual_t1_2;
            $casual_saturday_2 = $casual_t1_2;
            $casual_sunday_2 = $casual_t1_2;

            $pay_rate_t1_2 = number_format(($hourly_rate2 + $casual_t1_2), 2);
            $pay_rate_t15_2 = number_format(($fulltime_t15_2 + $casual_t15_2), 2);
            $pay_rate_t2_2 = number_format(($fulltime_t2_2 + $casual_t2_2), 2);
            $pay_rate_holiday_2 = number_format(($fulltime_holiday_2 + $casual_holiday_2), 2);
            $pay_rate_early_2 = number_format(($fulltime_early_2 + $casual_early_2), 2);
            $pay_rate_afternoon_2 = number_format(($fulltime_afternoon_2 + $casual_afternoon_2), 2);
            $pay_rate_night_2 = number_format(($fulltime_night_2 + $casual_night_2), 2);
            $pay_rate_saturday_2 = number_format(($fulltime_saturday_2 + $casual_saturday_2), 2);
            $pay_rate_sunday_2 = number_format(($fulltime_sunday_2 + $casual_sunday_2), 2);
        }else {
            $casual_t1_2 = number_format(($hourly_rate2 * self::CASUAL_LOADING), 2);
            $casual_t15_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_t2_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);
            $casual_holiday_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T25_OVERTIME), 2);
            $casual_afternoon_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1)), 2);
            $casual_night_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1)), 2);
            $casual_saturday_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_sunday_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);

            $pay_rate_t1_2 = number_format(($hourly_rate2 + ($hourly_rate2 * self::CASUAL_LOADING)), 2);
            $pay_rate_t15_2 = number_format((($hourly_rate2 * self::T15_OVERTIME) + (($hourly_rate2 * self::CASUAL_LOADING) * self::T15_OVERTIME)), 2);
            $pay_rate_t2_2 = number_format((($hourly_rate2 * self::T2_OVERTIME) + (($hourly_rate2 * self::CASUAL_LOADING) * self::T2_OVERTIME)), 2);
            $pay_rate_holiday_2 = number_format((($hourly_rate2 * self::T25_OVERTIME) + (($hourly_rate2 * self::CASUAL_LOADING) * self::T25_OVERTIME)), 2);
            $pay_rate_afternoon_2 = number_format((($hourly_rate2 * (1 + self::AFTERNOON_LOADING)) + (($hourly_rate2 * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1))), 2);
            $pay_rate_night_2 = number_format((($hourly_rate2 * (1 + self::NIGHT_LOADING)) + (($hourly_rate2 * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1))), 2);
            $pay_rate_saturday_2 = number_format((($hourly_rate2 * (self::T15_OVERTIME)) + (($hourly_rate2 * self::CASUAL_LOADING) * self::T15_OVERTIME)), 2);
            $pay_rate_sunday_2 = number_format((($hourly_rate2 * (self::T2_OVERTIME)) + (($hourly_rate2 * self::CASUAL_LOADING) * self::T2_OVERTIME)), 2);
        }
        $super_t1_2 = number_format((($pay_rate_t1_2) * $this->superannuation2),2);
        $super_t15_2 = number_format((0),2);
        $super_t2_2 = number_format((0),2);
        $super_holiday_2 = number_format((($pay_rate_holiday_2) * $this->superannuation2),2);
        $super_early_2 = number_format((($pay_rate_early_2) * $this->superannuation2),2);
        $super_afternoon_2 = number_format((($pay_rate_afternoon_2) * $this->superannuation2),2);
        $super_night_2 = number_format((($pay_rate_night_2) * $this->superannuation2),2);
        $super_saturday_2 = number_format((($pay_rate_saturday_2) * $this->superannuation2),2);
        $super_sunday_2 = number_format((($pay_rate_sunday_2) * $this->superannuation2),2);

        $payrollTax_t1_2 = number_format((($pay_rate_t1_2 + $super_t1_2) * $this->payroll_tax2),2);
        $payrollTax_t15_2 = number_format((($pay_rate_t15_2 + $super_t15_2) * $this->payroll_tax2),2);
        $payrollTax_t2_2 = number_format((($pay_rate_t2_2 + $super_t2_2) * $this->payroll_tax2),2);
        $payrollTax_holiday_2 = number_format((($pay_rate_holiday_2 + $super_holiday_2) * $this->payroll_tax2),2);
        $payrollTax_early_2 = number_format((($pay_rate_early_2 + $super_early_2) * $this->payroll_tax2),2);
        $payrollTax_afternoon_2 = number_format((($pay_rate_afternoon_2 + $super_afternoon_2) * $this->payroll_tax2),2);
        $payrollTax_night_2 = number_format((($pay_rate_night_2 + $super_night_2) * $this->payroll_tax2),2);
        $payrollTax_saturday_2 = number_format((($pay_rate_saturday_2 + $super_saturday_2) * $this->payroll_tax2),2);
        $payrollTax_sunday_2 = number_format((($pay_rate_sunday_2 + $super_sunday_2) * $this->payroll_tax2),2);

        $mhws_t1_2 = number_format((($pay_rate_t1_2 + $super_t1_2) * $this->mhws2),2);
        $mhws_t15_2 = number_format((($pay_rate_t15_2 + $super_t15_2) * $this->mhws2),2);
        $mhws_t2_2 = number_format((($pay_rate_t2_2 + $super_t2_2) * $this->mhws2),2);
        $mhws_holiday_2 = number_format((($pay_rate_holiday_2 + $super_holiday_2) * $this->mhws2),2);
        $mhws_early_2 = number_format((($pay_rate_early_2 + $super_early_2) * $this->mhws2),2);
        $mhws_afternoon_2 = number_format((($pay_rate_afternoon_2 + $super_afternoon_2) * $this->mhws2),2);
        $mhws_night_2 = number_format((($pay_rate_night_2 + $super_night_2) * $this->mhws2),2);
        $mhws_saturday_2 = number_format((($pay_rate_saturday_2 + $super_saturday_2) * $this->mhws2),2);
        $mhws_sunday_2 = number_format((($pay_rate_sunday_2 + $super_sunday_2) * $this->mhws2),2);

        $workcover_t1_2 = number_format((($pay_rate_t1_2 + $super_t1_2) * $this->workcover2),2);
        $workcover_t15_2 = number_format((($pay_rate_t15_2 + $super_t15_2) * $this->workcover2),2);
        $workcover_t2_2 = number_format((($pay_rate_t2_2 + $super_t2_2) * $this->workcover2),2);
        $workcover_holiday_2 = number_format((($pay_rate_holiday_2 + $super_holiday_2) * $this->workcover2),2);
        $workcover_early_2 = number_format((($pay_rate_early_2 + $super_early_2) * $this->workcover2),2);
        $workcover_afternoon_2 = number_format((($pay_rate_afternoon_2 + $super_afternoon_2) * $this->workcover2),2);
        $workcover_night_2 = number_format((($pay_rate_night_2 + $super_night_2) * $this->workcover2),2);
        $workcover_saturday_2 = number_format((($pay_rate_saturday_2 + $super_saturday_2) * $this->workcover2),2);
        $workcover_sunday_2 = number_format((($pay_rate_sunday_2 + $super_sunday_2) * $this->workcover2),2);

        $loadedcost_t1_2 = number_format(($pay_rate_t1_2 + $super_t1_2 + $payrollTax_t1_2 + $mhws_t1_2 + $workcover_t1_2),2);
        $loadedcost_t15_2 = number_format(($pay_rate_t15_2 + $super_t15_2 + $payrollTax_t15_2 + $mhws_t15_2 + $workcover_t15_2),2);
        $loadedcost_t2_2 = number_format(($pay_rate_t2_2 + $super_t2_2 + $payrollTax_t2_2 + $mhws_t2_2 + $workcover_t2_2),2);
        $loadedcost_holiday_2 = number_format(($pay_rate_holiday_2 + $super_holiday_2 + $payrollTax_holiday_2 + $mhws_holiday_2 + $workcover_holiday_2),2);
        $loadedcost_early_2 = number_format(($pay_rate_early_2 + $super_early_2 + $payrollTax_early_2 + $mhws_early_2 + $workcover_early_2),2);
        $loadedcost_afternoon_2 = number_format(($pay_rate_afternoon_2 + $super_afternoon_2 + $payrollTax_afternoon_2 + $mhws_afternoon_2 + $workcover_afternoon_2),2);
        $loadedcost_night_2 = number_format(($pay_rate_night_2 + $super_night_2 + $payrollTax_night_2 + $mhws_night_2 + $workcover_night_2),2);
        $loadedcost_saturday_2 = number_format(($pay_rate_saturday_2 + $super_saturday_2 + $payrollTax_saturday_2 + $mhws_saturday_2 + $workcover_saturday_2),2);
        $loadedcost_sunday_2 = number_format(($pay_rate_sunday_2 + $super_sunday_2 + $payrollTax_sunday_2 + $mhws_sunday_2 + $workcover_sunday_2),2);

        $placement_fee_2 = number_format($this->margin2,2);

        $chargeRate_t1_2 = number_format(($placement_fee_2 + $loadedcost_t1_2),2);
        $chargeRate_t15_2 = number_format(($placement_fee_2 + $loadedcost_t15_2),2);
        $chargeRate_t2_2 = number_format(($placement_fee_2 + $loadedcost_t2_2),2);
        $chargeRate_holiday_2 = number_format(($placement_fee_2 + $loadedcost_holiday_2),2);
        $chargeRate_early_2 = number_format(($placement_fee_2 + $loadedcost_early_2),2);
        $chargeRate_afternoon_2 = number_format(($placement_fee_2 + $loadedcost_afternoon_2),2);
        $chargeRate_night_2 = number_format(($placement_fee_2 + $loadedcost_night_2),2);
        $chargeRate_saturday_2 = number_format(($placement_fee_2 + $loadedcost_saturday_2),2);
        $chargeRate_sunday_2 = number_format(($placement_fee_2 + $loadedcost_sunday_2),2);

        $gst_t1_2 = number_format(($chargeRate_t1_2 * 0.1),2);
        $gst_t15_2 = number_format(($chargeRate_t15_2 * 0.1),2);
        $gst_t2_2 = number_format(($chargeRate_t2_2 * 0.1),2);
        $gst_holiday_2 = number_format(($chargeRate_holiday_2 * 0.1),2);
        $gst_early_2 = number_format(($chargeRate_early_2 * 0.1),2);
        $gst_afternoon_2 = number_format(($chargeRate_afternoon_2 * 0.1),2);
        $gst_night_2 = number_format(($chargeRate_night_2 * 0.1),2);
        $gst_saturday_2 = number_format(($chargeRate_saturday_2 * 0.1),2);
        $gst_sunday_2 = number_format(($chargeRate_sunday_2 * 0.1),2);

        $chg_gst_t1_2 = number_format(($chargeRate_t1_2),2);
        $chg_gst_t15_2 = number_format(($chargeRate_t15_2),2);
        $chg_gst_t2_2 = number_format(($chargeRate_t2_2),2);
        $chg_gst_holiday_2 = number_format(($chargeRate_holiday_2),2);
        $chg_gst_early_2 = number_format(($chargeRate_early_2),2);
        $chg_gst_afternoon_2 = number_format(($chargeRate_afternoon_2),2);
        $chg_gst_night_2 = number_format(($chargeRate_night_2),2);
        $chg_gst_saturday_2 = number_format(($chargeRate_saturday_2),2);
        $chg_gst_sunday_2 = number_format(($chargeRate_sunday_2),2);


        $html = $html.'<table cellspacing="0" style="background-color: #FFFFFF; border: none; border-color: #FFFFFF">
            <thead style="border: none; background-color: #FFFFFF; background: #FFFFFF">
              <tr style="border: none; background-color: #FFFFFF; background: #FFFFFF">
                <th style="border: none; background-color: #FFFFFF; background: #FFFFFF"></th>
                <th style="border: none; background-color: #FFFFFF; background: #FFFFFF"></th>
                <th style="border: none; background-color: #FFFFFF; background: #FFFFFF"></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="text-align: right; width: 35%"><span style="font-size: 12pt; color: #0d0f12; font-weight: bold; border: 1px solid red">Rates presented to:</span>
                    <br>
                        <span style="font-size: 10pt; color: #0AA699; font-weight: bold;">'.$this->client.'</span>
                    <br>
                    <br>
                        <span style="font-size: 12pt; color: #0d0f12; font-weight: bold">Award/EBA:</span>
                        <br>
                        <span style="font-size: 10pt; color: #0AA699; font-weight: bold">'.$this->award.'</span></td>
                <td></td>
                <td><img src="img/logo.png" width="220" height="50" border="0"/>
                    <br>
                    <img src="'.$this->client_logo.'"  alt="">
                </td>
              </tr>
            </tbody>
          </table>';
        $html = $html.'<br>';
        $html = $html.'';
        $html = $html.'<br></div>';

        $html = $html.'<br>';
        $html = $html.'<div style="font-size: 22pt; color: red; font-weight: bold">Labour <br>';
        $html = $html.'Hire Rates</div>';
        $html = $html.'<br>';
        $html = $html.'<div style="font-size: 14pt; font-weight: bold; font-style: italic"><b>Classification: '.$this->position.'</b></div>';
        $html = $html.'<br>';

        if($this->breakdown == 'CHARGE RATE'){
            $html = $html . '<div align="center"><table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <thead>
              <tr>
                <th style="width: 17%">Description</th>
                <th>T1.0 Ordinary</th>
                <th>T1.5 Overtime</th>
                <th>T2.0 Overtime</th>
                <th>Public Holiday</th>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<th>Early Morning</th>';
            }
            $html = $html.'<th>Afternoon</th>
                <th>Night</th>
                <th>Saturday</th>
                <th>Sunday</th>
              </tr>
            </thead>
            <tbody>';
            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Charge Rate</td>
                <td>' . $chg_gst_t1 . '</td>
                <td>' . $chg_gst_t15 . '</td>
                <td>' . $chg_gst_t2 . '</td>
                <td>' . $chg_gst_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>' . $chg_gst_early . '</td>';
            }
            $html = $html.'<td>' . $chg_gst_afternoon . '</td>
                <td>' . $chg_gst_night . '</td>
                <td>' . $chg_gst_saturday . '</td>
                <td>' . $chg_gst_sunday . '</td></tr>';
            $html = $html . '</tbody>
          </table></div><br>';
        }else {
            $html = $html . '<div align="center"><table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <thead>
              <tr>
                <th style="width: 17%">Description</th>
                <th>T1.0 Ordinary</th>
                <th>T1.5 Overtime</th>
                <th>T2.0 Overtime</th>
                <th>Public Holiday</th>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<th>Early Morning</th>';
            }
            $html = $html.'<th>Afternoon</th>
                <th>Night</th>
                <th>Saturday</th>
                <th>Sunday</th>
              </tr>
            </thead>
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Fulltime Rate</td>
                <td>' . $hourly_rate . '</td>
                <td>' . $fulltime_t15 . '</td>
                <td>' . $fulltime_t2 . '</td>
                <td>' . $fulltime_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>' . $fulltime_early . '</td>';
            }
            $html = $html.'<td>' . $fulltime_afternoon . '</td>
                <td>' . $fulltime_night . '</td>
                <td>' . $fulltime_saturday . '</td>
                <td>' . $fulltime_sunday . '</td></tr>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Casual Loading</td>
                <td>' . $casual_t1 . '</td>
                <td>' . $casual_t15 . '</td>
                <td>' . $casual_t2 . '</td>
                <td>' . $casual_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>' . $casual_early . '</td>';
            }
            $html = $html.'<td>' . $casual_afternoon . '</td>
                <td>' . $casual_night . '</td>
                <td>' . $casual_saturday . '</td>
                <td>' . $casual_sunday . '</td></tr>';

            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Pay Rate</td>
                <td>' . $pay_rate_t1 . '</td>
                <td>' . $pay_rate_t15 . '</td>
                <td>' . $pay_rate_t2 . '</td>
                <td>' . $pay_rate_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>' . $pay_rate_early . '</td>';
            }
            $html =$html.'<td>' . $pay_rate_afternoon . '</td>
                <td>' . $pay_rate_night . '</td>
                <td>' . $pay_rate_saturday . '</td>
                <td>' . $pay_rate_sunday . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Superannuation</td>
                <td>' . $super_t1 . '</td>
                <td>' . $super_t15 . '</td>
                <td>' . $super_t2 . '</td>
                <td>' . $super_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>' . $super_early . '</td>';
            }
            $html = $html.'<td>' . $super_afternoon . '</td>
                <td>' . $super_night . '</td>
                <td>' . $super_saturday . '</td>
                <td>' . $super_sunday . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Payroll Tax</td>
                <td>' . $payrollTax_t1 . '</td>
                <td>' . $payrollTax_t15 . '</td>
                <td>' . $payrollTax_t2 . '</td>
                <td>' . $payrollTax_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>' . $payrollTax_early . '</td>';
            }
            $html = $html.'<td>' . $payrollTax_afternoon . '</td>
                <td>' . $payrollTax_night . '</td>
                <td>' . $payrollTax_saturday . '</td>
                <td>' . $payrollTax_sunday . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">MHWS</td>
                <td>' . $mhws_t1 . '</td>
                <td>' . $mhws_t15 . '</td>
                <td>' . $mhws_t2 . '</td>
                <td>' . $mhws_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>' . $mhws_early . '</td>';
            }
            $html = $html.'<td>' . $mhws_afternoon . '</td>
                <td>' . $mhws_night . '</td>
                <td>' . $mhws_saturday . '</td>
                <td>' . $mhws_sunday . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">WorkCover</td>
                <td>' . $workcover_t1 . '</td>
                <td>' . $workcover_t15 . '</td>
                <td>' . $workcover_t2 . '</td>
                <td>' . $workcover_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$workcover_early.'</td>';
            }
            $html = $html.'<td>' . $workcover_afternoon . '</td>
                <td>' . $workcover_night . '</td>
                <td>' . $workcover_saturday . '</td>
                <td>' . $workcover_sunday . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Loaded Cost</td>
                <td>' . $loadedcost_t1 . '</td>
                <td>' . $loadedcost_t15 . '</td>
                <td>' . $loadedcost_t2 . '</td>
                <td>' . $loadedcost_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$loadedcost_early.'</td>';
            }
            $html = $html.'<td>' . $loadedcost_afternoon . '</td>
                <td>' . $loadedcost_night . '</td>
                <td>' . $loadedcost_saturday . '</td>
                <td>' . $loadedcost_sunday . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Placement Fee</td>
                <td>' . $placement_fee . '</td>
                <td>' . $placement_fee . '</td>
                <td>' . $placement_fee . '</td>
                <td>' . $placement_fee . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$placement_fee.'</td>';
            }
            $html = $html.'<td>' . $placement_fee . '</td>
                <td>' . $placement_fee . '</td>
                <td>' . $placement_fee . '</td>
                <td>' . $placement_fee . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Charge Rate</td>
                <td>' . $chargeRate_t1 . '</td>
                <td>' . $chargeRate_t15 . '</td>
                <td>' . $chargeRate_t2 . '</td>
                <td>' . $chargeRate_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$chargeRate_early.'</td>';
            }
            $html = $html.'<td>' . $chargeRate_afternoon . '</td>
                <td>' . $chargeRate_night . '</td>
                <td>' . $chargeRate_saturday . '</td>
                <td>' . $chargeRate_sunday . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br>';

            $html = $html.'</div>';
        }

        $html = $html.'<br><div style="font-size: 14pt; font-weight: bold; font-style: italic"><b>Classification: '.$this->position2.'</b></div>';
        $html = $html.'<br>';
        /*  --------------------------   hire rate 2 html   ----------------------- */
        if($this->breakdown == 'CHARGE RATE'){
            $html = $html . '<div align="center"><table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <thead>
              <tr>
                <th style="width: 17%">Description</th>
                <th>T1.0 Ordinary</th>
                <th>T1.5 Overtime</th>
                <th>T2.0 Overtime</th>
                <th>Public Holiday</th>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<th>Early Morning</th>';
            }
            $html = $html.'<th>Afternoon</th>
                <th>Night</th>
                <th>Saturday</th>
                <th>Sunday</th>
              </tr>
            </thead>
            <tbody>';
            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Charge Rate</td>
                <td>' . $chg_gst_t1_2 . '</td>
                <td>' . $chg_gst_t15_2 . '</td>
                <td>' . $chg_gst_t2_2 . '</td>
                <td>' . $chg_gst_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$chg_gst_early_2.'</td>';
            }
            $html = $html.'<td>' . $chg_gst_afternoon_2 . '</td>
                <td>' . $chg_gst_night_2 . '</td>
                <td>' . $chg_gst_saturday_2 . '</td>
                <td>' . $chg_gst_sunday_2 . '</td></tr>';
            $html = $html . '</tbody>
          </table></div><br>';
        }else {
            $html = $html . '<div align="center"><table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <thead>
              <tr>
                <th style="width: 17%">Description</th>
                <th>T1.0 Ordinary</th>
                <th>T1.5 Overtime</th>
                <th>T2.0 Overtime</th>
                <th>Public Holiday</th>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<th>Early Morning</th>';
            }
            $html = $html.'<th>Afternoon</th>
                <th>Night</th>
                <th>Saturday</th>
                <th>Sunday</th>
              </tr>
            </thead>
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Fulltime Rate</td>
                <td>' . $hourly_rate2 . '</td>
                <td>' . $fulltime_t15_2 . '</td>
                <td>' . $fulltime_t2_2 . '</td>
                <td>' . $fulltime_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$fulltime_early_2.'</td>';
            }
            $html = $html.'<td>' . $fulltime_afternoon_2 . '</td>
                <td>' . $fulltime_night_2 . '</td>
                <td>' . $fulltime_saturday_2 . '</td>
                <td>' . $fulltime_sunday_2 . '</td></tr>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Casual Loading</td>
                <td>' . $casual_t1_2 . '</td>
                <td>' . $casual_t15_2 . '</td>
                <td>' . $casual_t2_2 . '</td>
                <td>' . $casual_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$casual_early_2.'</td>';
            }
            $html = $html.'<td>' . $casual_afternoon_2 . '</td>
                <td>' . $casual_night_2 . '</td>
                <td>' . $casual_saturday_2 . '</td>
                <td>' . $casual_sunday_2 . '</td></tr>';

            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Pay Rate</td>
                <td>' . $pay_rate_t1_2 . '</td>
                <td>' . $pay_rate_t15_2 . '</td>
                <td>' . $pay_rate_t2_2 . '</td>
                <td>' . $pay_rate_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$pay_rate_early_2.'</td>';
            }
            $html = $html.'<td>' . $pay_rate_afternoon_2 . '</td>
                <td>' . $pay_rate_night_2 . '</td>
                <td>' . $pay_rate_saturday_2 . '</td>
                <td>' . $pay_rate_sunday_2 . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Superannuation</td>
                <td>' . $super_t1_2 . '</td>
                <td>' . $super_t15_2 . '</td>
                <td>' . $super_t2_2 . '</td>
                <td>' . $super_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$super_early_2.'</td>';
            }
            $html = $html.'<td>' . $super_afternoon_2 . '</td>
                <td>' . $super_night_2 . '</td>
                <td>' . $super_saturday_2 . '</td>
                <td>' . $super_sunday_2 . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Payroll Tax</td>
                <td>' . $payrollTax_t1_2 . '</td>
                <td>' . $payrollTax_t15_2 . '</td>
                <td>' . $payrollTax_t2_2 . '</td>
                <td>' . $payrollTax_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$payrollTax_early_2.'</td>';
            }
            $html = $html.'<td>' . $payrollTax_afternoon_2 . '</td>
                <td>' . $payrollTax_night_2 . '</td>
                <td>' . $payrollTax_saturday_2 . '</td>
                <td>' . $payrollTax_sunday_2 . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">MHWS</td>
                <td>' . $mhws_t1_2 . '</td>
                <td>' . $mhws_t15_2 . '</td>
                <td>' . $mhws_t2_2 . '</td>
                <td>' . $mhws_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$mhws_early_2.'</td>';
            }
            $html = $html.'<td>' . $mhws_afternoon_2 . '</td>
                <td>' . $mhws_night_2 . '</td>
                <td>' . $mhws_saturday_2 . '</td>
                <td>' . $mhws_sunday_2 . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">WorkCover</td>
                <td>' . $workcover_t1_2 . '</td>
                <td>' . $workcover_t15_2 . '</td>
                <td>' . $workcover_t2_2 . '</td>
                <td>' . $workcover_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$workcover_early_2.'</td>';
            }
            $html = $html.'<td>' . $workcover_afternoon_2 . '</td>
                <td>' . $workcover_night_2 . '</td>
                <td>' . $workcover_saturday_2 . '</td>
                <td>' . $workcover_sunday_2 . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Loaded Cost</td>
                <td>' . $loadedcost_t1_2 . '</td>
                <td>' . $loadedcost_t15_2 . '</td>
                <td>' . $loadedcost_t2_2 . '</td>
                <td>' . $loadedcost_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$loadedcost_early_2.'</td>';
            }
            $html = $html.'<td>' . $loadedcost_afternoon_2 . '</td>
                <td>' . $loadedcost_night_2 . '</td>
                <td>' . $loadedcost_saturday_2 . '</td>
                <td>' . $loadedcost_sunday_2 . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Placement Fee</td>
                <td>' . $placement_fee_2 . '</td>
                <td>' . $placement_fee_2 . '</td>
                <td>' . $placement_fee_2 . '</td>
                <td>' . $placement_fee_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$placement_fee_2.'</td>';
            }
            $html = $html.'<td>' . $placement_fee_2 . '</td>
                <td>' . $placement_fee_2 . '</td>
                <td>' . $placement_fee_2 . '</td>
                <td>' . $placement_fee_2 . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Charge Rate</td>
                <td>' . $chargeRate_t1_2 . '</td>
                <td>' . $chargeRate_t15_2 . '</td>
                <td>' . $chargeRate_t2_2 . '</td>
                <td>' . $chargeRate_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$chargeRate_early_2.'</td>';
            }
            $html = $html.'<td>' . $chargeRate_afternoon_2 . '</td>
                <td>' . $chargeRate_night_2 . '</td>
                <td>' . $chargeRate_saturday_2 . '</td>
                <td>' . $chargeRate_sunday_2 . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br>';

            $html = $html .'</div>';
        }


        $html = $html.'<div style="text-align: right">Please note charge rates expressed are exclusive of GST.<br>Payment terms - '.$this->payment_terms.' Days from Invoice date</div><br>';
        $html = $html.'I agree to the rates and terms proposed by Chandler Services and understand the rules and regulations according to the '.$this->award;
        $html = $html.'<br><br>';
        /*$html = $html.'<table style="background-color: #FFFFFF; border: none; border-color: #FFFFFF">
                        <thead style="border: none; background-color: #FFFFFF; background: #FFFFFF">
                             <tr style="border: none; background-color: #FFFFFF; background: #FFFFFF">
                                <th style="border: none; background-color: #FFFFFF; background: #FFFFFF"></th>
                                <th style="border: none; background-color: #FFFFFF; background: #FFFFFF"></th>
                                <th style="border: none; background-color: #FFFFFF; background: #FFFFFF"></th>
                                <th style="border: none; background-color: #FFFFFF; background: #FFFFFF"></th>
                             </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td style="width: 45%">Name: ..............................................................................</td>
                            <td style="width: 5%"></td>
                            <td style="width: 20%">Received T&C s</td>
                            <td style="width: 20%"><input type="checkbox" name="box1" value="1" checked="" style="border: 1px solid black"/>Yes<input type="checkbox" name="box2" value="1" checked="" style="border: 1px solid black"/> No</td>
                          </tr>
                          <tr><td colspan="4"></td></tr>
                          <tr>
                            <td style="width: 45%">Title: ..............................................................................</td>
                            <td style="width: 5%"></td>
                            <td style="width: 20%"></td>
                            <td style="width: 20%"></td>
                          </tr>
                          <tr><td colspan="4"></td></tr>
                           <tr>
                            <td style="width: 45%">Company: ..........................................................................</td>
                            <td style="width: 5%"></td>
                            <td style="width: 20%"></td>
                            <td style="width: 20%"></td>
                          </tr>
                          <tr><td colspan="4"></td></tr>
                            <tr>
                            <td style="width: 45%">Signature: ........................................................................</td>
                            <td style="width: 5%"></td>
                            <td style="width: 20%">Date:</td>
                            <td style="width: 20%"></td>
                          </tr>
                        </tbody>
                      </table>';*/

        $fileName = 'hireRate_'.time().'-'.mt_rand();
        $filePathPDF = './rates/'.$fileName.'.pdf';
        $pdf->writeHTML($html, true, false, false, false, '');

        $pdf->lastPage();
        $pdf->Output(__DIR__.'/rates/'.$fileName.'.pdf', 'F');
        echo $filePathPDF;
    }
    public function calculateHireRateForThreePositions(){

        $pdf = new HIRERATEPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setHeaderTemplateAutoreset(true);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('');
        $pdf->SetTitle('');
        $pdf->SetSubject('');
        $pdf->SetKeywords('');
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 8));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        define('PDF_CUSTOM_HEADER_STRING',' ');
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_CUSTOM_HEADER_STRING);
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        $pdf->SetFont('calibri', '', 9);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        $pdf->AddPage();
        $html = '';
        $html = $html.'<style>
tr{ 
text-transform: uppercase;
}

table {
    border-color:gray;
}
th{
    text-align: center;
    background-color: #5b9ad5;
    color: #FFFFFF;
}
.zebra0{
    background-color: #cbd2d5;
}
.zebra1{
    background-color: white;
}
</style>';

        $hourly_rate = $this->hourly_rate;
        $fulltime_t15 = number_format(($hourly_rate * self::T15_OVERTIME),2);
        $fulltime_t2 = number_format(($hourly_rate * self::T2_OVERTIME),2);
        $fulltime_holiday = number_format(($hourly_rate * self::T25_OVERTIME),2);
        $fulltime_early = number_format(($hourly_rate * (1 + self::EARLY_MORNING_LOADING)),2);
        $fulltime_afternoon = number_format(($hourly_rate * (1 + self::AFTERNOON_LOADING)),2);
        $fulltime_night = number_format(($hourly_rate * (1 + self::NIGHT_LOADING)),2);
        $fulltime_saturday = number_format(($hourly_rate * (self::T15_OVERTIME)),2);
        $fulltime_sunday = number_format(($hourly_rate * (self::T2_OVERTIME)),2);

        if($this->award == self::STORAGE_AWD){
            $casual_t1 = number_format(($hourly_rate * self::CASUAL_LOADING), 2);
            $casual_t15 = $casual_t1;
            $casual_t2 = $casual_t1;
            $casual_holiday = $casual_t1;
            $casual_early = $casual_t1;
            $casual_afternoon = $casual_t1;
            $casual_night = $casual_t1;
            $casual_saturday = $casual_t1;
            $casual_sunday = $casual_t1;

            $pay_rate_t1 = number_format(($hourly_rate + $casual_t1), 2);
            $pay_rate_t15 = number_format(($fulltime_t15 + $casual_t15), 2);
            $pay_rate_t2 = number_format(($fulltime_t2 + $casual_t2), 2);
            $pay_rate_holiday = number_format(($fulltime_holiday + $casual_holiday), 2);
            $pay_rate_early = number_format(($fulltime_early + $casual_early), 2);
            $pay_rate_afternoon = number_format(($fulltime_afternoon + $casual_afternoon), 2);
            $pay_rate_night = number_format(($fulltime_night + $casual_night), 2);
            $pay_rate_saturday = number_format(($fulltime_saturday + $casual_saturday), 2);
            $pay_rate_sunday = number_format(($fulltime_sunday + $casual_sunday), 2);

        }elseif($this->award == self::FOOD_AWD){
            $casual_t1 = number_format(($hourly_rate * self::CASUAL_LOADING), 2);
            $casual_t15 = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_t2 = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);
            $casual_holiday = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T25_OVERTIME), 2);
            $casual_early = number_format(($casual_t1 * (1 + self::EARLY_MORNING_LOADING) + 0.01),2);
            $casual_afternoon = number_format((($hourly_rate * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1)), 2);
            $casual_night = number_format((($hourly_rate * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1)), 2);
            $casual_saturday = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_sunday = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);

            $pay_rate_t1 = number_format(($hourly_rate + $casual_t1), 2);
            $pay_rate_t15 = number_format(($fulltime_t15 + $casual_t15), 2);
            $pay_rate_t2 = number_format(($fulltime_t2 + $casual_t2), 2);
            $pay_rate_holiday = number_format(($fulltime_holiday + $casual_holiday), 2);
            $pay_rate_early = number_format(($fulltime_early + $casual_early), 2);
            $pay_rate_afternoon = number_format(($fulltime_afternoon + $casual_afternoon), 2);
            $pay_rate_night = number_format(($fulltime_night + $casual_night), 2);
            $pay_rate_saturday = number_format(($fulltime_saturday + $casual_saturday), 2);
            $pay_rate_sunday = number_format(($fulltime_sunday + $casual_sunday), 2);
        }elseif($this->award == self::RETAIL_AWD){
            $fulltime_t2 = number_format(($hourly_rate * self::RTT2_OVERTIME),2);
            $fulltime_holiday = number_format(($hourly_rate * self::RTT25_OVERTIME),2);
            $fulltime_afternoon = number_format(($hourly_rate * (1 + self::RT_AFTERNOON_LOADING)),2);
            $fulltime_sunday = number_format(($hourly_rate * (self::RTT2_OVERTIME)),2);

            $casual_t1 = number_format(($hourly_rate * self::CASUAL_LOADING), 2);
            $casual_t15 = $casual_t1;
            $casual_t2 = $casual_t1;
            $casual_holiday = $casual_t1;
            $casual_early = $casual_t1;
            $casual_afternoon = $casual_t1;
            $casual_night = $casual_t1;
            $casual_saturday = $casual_t1;
            $casual_sunday = $casual_t1;

            $pay_rate_t1 = number_format(($hourly_rate + $casual_t1), 2);
            $pay_rate_t15 = number_format(($fulltime_t15 + $casual_t15), 2);
            $pay_rate_t2 = number_format(($fulltime_t2 + $casual_t2), 2);
            $pay_rate_holiday = number_format(($fulltime_holiday + $casual_holiday), 2);
            $pay_rate_early = number_format(($fulltime_early + $casual_early), 2);
            $pay_rate_afternoon = number_format(($fulltime_afternoon + $casual_afternoon), 2);
            $pay_rate_night = number_format(($fulltime_night + $casual_night), 2);
            $pay_rate_saturday = number_format(($fulltime_saturday + $casual_saturday), 2);
            $pay_rate_sunday = number_format(($fulltime_sunday + $casual_sunday), 2);
        }else {
            $casual_t1 = number_format(($hourly_rate * self::CASUAL_LOADING), 2);
            $casual_t15 = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_t2 = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);
            $casual_holiday = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T25_OVERTIME), 2);
            $casual_afternoon = number_format((($hourly_rate * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1)), 2);
            $casual_night = number_format((($hourly_rate * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1)), 2);
            $casual_saturday = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_sunday = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);

            $pay_rate_t1 = number_format(($hourly_rate + ($hourly_rate * self::CASUAL_LOADING)), 2);
            $pay_rate_t15 = number_format((($hourly_rate * self::T15_OVERTIME) + (($hourly_rate * self::CASUAL_LOADING) * self::T15_OVERTIME)), 2);
            $pay_rate_t2 = number_format((($hourly_rate * self::T2_OVERTIME) + (($hourly_rate * self::CASUAL_LOADING) * self::T2_OVERTIME)), 2);
            $pay_rate_holiday = number_format((($hourly_rate * self::T25_OVERTIME) + (($hourly_rate * self::CASUAL_LOADING) * self::T25_OVERTIME)), 2);
            $pay_rate_afternoon = number_format((($hourly_rate * (1 + self::AFTERNOON_LOADING)) + (($hourly_rate * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1))), 2);
            $pay_rate_night = number_format((($hourly_rate * (1 + self::NIGHT_LOADING)) + (($hourly_rate * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1))), 2);
            $pay_rate_saturday = number_format((($hourly_rate * (self::T15_OVERTIME)) + (($hourly_rate * self::CASUAL_LOADING) * self::T15_OVERTIME)), 2);
            $pay_rate_sunday = number_format((($hourly_rate * (self::T2_OVERTIME)) + (($hourly_rate * self::CASUAL_LOADING) * self::T2_OVERTIME)), 2);
        }
        $super_t1 = number_format((($pay_rate_t1) * $this->superannuation),2);
        $super_t15 = number_format((0),2);
        $super_t2 = number_format((0),2);
        $super_holiday = number_format((($pay_rate_holiday) * $this->superannuation),2);
        $super_early = number_format((($pay_rate_early) * $this->superannuation),2);
        $super_afternoon = number_format((($pay_rate_afternoon) * $this->superannuation),2);
        $super_night = number_format((($pay_rate_night) * $this->superannuation),2);
        $super_saturday = number_format((($pay_rate_saturday) * $this->superannuation),2);
        $super_sunday = number_format((($pay_rate_sunday) * $this->superannuation),2);

        $payrollTax_t1 = number_format((($pay_rate_t1 + $super_t1) * $this->payroll_tax),2);
        $payrollTax_t15 = number_format((($pay_rate_t15 + $super_t15) * $this->payroll_tax),2);
        $payrollTax_t2 = number_format((($pay_rate_t2 + $super_t2) * $this->payroll_tax),2);
        $payrollTax_holiday = number_format((($pay_rate_holiday + $super_holiday) * $this->payroll_tax),2);
        $payrollTax_early = number_format((($pay_rate_early + $super_early) * $this->payroll_tax),2);
        $payrollTax_afternoon = number_format((($pay_rate_afternoon + $super_afternoon) * $this->payroll_tax),2);
        $payrollTax_night = number_format((($pay_rate_night + $super_night) * $this->payroll_tax),2);
        $payrollTax_saturday = number_format((($pay_rate_saturday + $super_saturday) * $this->payroll_tax),2);
        $payrollTax_sunday = number_format((($pay_rate_sunday + $super_sunday) * $this->payroll_tax),2);

        $mhws_t1 = number_format((($pay_rate_t1 + $super_t1) * $this->mhws),2);
        $mhws_t15 = number_format((($pay_rate_t15 + $super_t15) * $this->mhws),2);
        $mhws_t2 = number_format((($pay_rate_t2 + $super_t2) * $this->mhws),2);
        $mhws_holiday = number_format((($pay_rate_holiday + $super_holiday) * $this->mhws),2);
        $mhws_early = number_format((($pay_rate_early + $super_early) * $this->mhws),2);
        $mhws_afternoon = number_format((($pay_rate_afternoon + $super_afternoon) * $this->mhws),2);
        $mhws_night = number_format((($pay_rate_night + $super_night) * $this->mhws),2);
        $mhws_saturday = number_format((($pay_rate_saturday + $super_saturday) * $this->mhws),2);
        $mhws_sunday = number_format((($pay_rate_sunday + $super_sunday) * $this->mhws),2);

        $workcover_t1 = number_format((($pay_rate_t1 + $super_t1) * $this->workcover),2);
        $workcover_t15 = number_format((($pay_rate_t15 + $super_t15) * $this->workcover),2);
        $workcover_t2 = number_format((($pay_rate_t2 + $super_t2) * $this->workcover),2);
        $workcover_holiday = number_format((($pay_rate_holiday + $super_holiday) * $this->workcover),2);
        $workcover_early = number_format((($pay_rate_early + $super_early) * $this->workcover),2);
        $workcover_afternoon = number_format((($pay_rate_afternoon + $super_afternoon) * $this->workcover),2);
        $workcover_night = number_format((($pay_rate_night + $super_night) * $this->workcover),2);
        $workcover_saturday = number_format((($pay_rate_saturday + $super_saturday) * $this->workcover),2);
        $workcover_sunday = number_format((($pay_rate_sunday + $super_sunday) * $this->workcover),2);

        $loadedcost_t1 = number_format(($pay_rate_t1 + $super_t1 + $payrollTax_t1 + $mhws_t1 + $workcover_t1),2);
        $loadedcost_t15 = number_format(($pay_rate_t15 + $super_t15 + $payrollTax_t15 + $mhws_t15 + $workcover_t15),2);
        $loadedcost_t2 = number_format(($pay_rate_t2 + $super_t2 + $payrollTax_t2 + $mhws_t2 + $workcover_t2),2);
        $loadedcost_holiday = number_format(($pay_rate_holiday + $super_holiday + $payrollTax_holiday + $mhws_holiday + $workcover_holiday),2);
        $loadedcost_early = number_format(($pay_rate_early + $super_early + $payrollTax_early + $mhws_early + $workcover_early),2);
        $loadedcost_afternoon = number_format(($pay_rate_afternoon + $super_afternoon + $payrollTax_afternoon + $mhws_afternoon + $workcover_afternoon),2);
        $loadedcost_night = number_format(($pay_rate_night + $super_night + $payrollTax_night + $mhws_night + $workcover_night),2);
        $loadedcost_saturday = number_format(($pay_rate_saturday + $super_saturday + $payrollTax_saturday + $mhws_saturday + $workcover_saturday),2);
        $loadedcost_sunday = number_format(($pay_rate_sunday + $super_sunday + $payrollTax_sunday + $mhws_sunday + $workcover_sunday),2);

        $placement_fee = number_format($this->margin,2);

        $chargeRate_t1 = number_format(($placement_fee + $loadedcost_t1),2);
        $chargeRate_t15 = number_format(($placement_fee + $loadedcost_t15),2);
        $chargeRate_t2 = number_format(($placement_fee + $loadedcost_t2),2);
        $chargeRate_holiday = number_format(($placement_fee + $loadedcost_holiday),2);
        $chargeRate_early = number_format(($placement_fee + $loadedcost_early),2);
        $chargeRate_afternoon = number_format(($placement_fee + $loadedcost_afternoon),2);
        $chargeRate_night = number_format(($placement_fee + $loadedcost_night),2);
        $chargeRate_saturday = number_format(($placement_fee + $loadedcost_saturday),2);
        $chargeRate_sunday = number_format(($placement_fee + $loadedcost_sunday),2);

        $gst_t1 = number_format(($chargeRate_t1 * 0.1),2);
        $gst_t15 = number_format(($chargeRate_t15 * 0.1),2);
        $gst_t2 = number_format(($chargeRate_t2 * 0.1),2);
        $gst_holiday = number_format(($chargeRate_holiday * 0.1),2);
        $gst_early = number_format(($chargeRate_early * 0.1),2);
        $gst_afternoon = number_format(($chargeRate_afternoon * 0.1),2);
        $gst_night = number_format(($chargeRate_night * 0.1),2);
        $gst_saturday = number_format(($chargeRate_saturday * 0.1),2);
        $gst_sunday = number_format(($chargeRate_sunday * 0.1),2);

        $chg_gst_t1 = number_format(($chargeRate_t1),2);
        $chg_gst_t15 = number_format(($chargeRate_t15),2);
        $chg_gst_t2 = number_format(($chargeRate_t2),2);
        $chg_gst_holiday = number_format(($chargeRate_holiday),2);
        $chg_gst_early = number_format(($chargeRate_early),2);
        $chg_gst_afternoon = number_format(($chargeRate_afternoon),2);
        $chg_gst_night = number_format(($chargeRate_night),2);
        $chg_gst_saturday = number_format(($chargeRate_saturday),2);
        $chg_gst_sunday = number_format(($chargeRate_sunday),2);

        /* --------------------------    hire rate 2    ---------------------------- */

        $hourly_rate2 = $this->hourly_rate2;
        $fulltime_t15_2 = number_format(($hourly_rate2 * self::T15_OVERTIME),2);
        $fulltime_t2_2 = number_format(($hourly_rate2 * self::T2_OVERTIME),2);
        $fulltime_holiday_2 = number_format(($hourly_rate2 * self::T25_OVERTIME),2);
        $fulltime_early_2 =  number_format(($hourly_rate2 * (1 + self::EARLY_MORNING_LOADING)),2);
        $fulltime_afternoon_2 = number_format(($hourly_rate2 * (1 + self::AFTERNOON_LOADING)),2);
        $fulltime_night_2 = number_format(($hourly_rate2 * (1 + self::NIGHT_LOADING)),2);
        $fulltime_saturday_2 = number_format(($hourly_rate2 * (self::T15_OVERTIME)),2);
        $fulltime_sunday_2 = number_format(($hourly_rate2 * (self::T2_OVERTIME)),2);

        if($this->award == self::STORAGE_AWD){
            $casual_t1_2 = number_format(($hourly_rate2 * self::CASUAL_LOADING), 2);
            $casual_t15_2 = $casual_t1_2;
            $casual_t2_2 = $casual_t1_2;
            $casual_holiday_2 = $casual_t1_2;
            $casual_early_2 = $casual_t1_2;
            $casual_afternoon_2 = $casual_t1_2;
            $casual_night_2 = $casual_t1_2;
            $casual_saturday_2 = $casual_t1_2;
            $casual_sunday_2 = $casual_t1_2;

            $pay_rate_t1_2 = number_format(($hourly_rate2 + $casual_t1_2), 2);
            $pay_rate_t15_2 = number_format(($fulltime_t15_2 + $casual_t15_2), 2);
            $pay_rate_t2_2 = number_format(($fulltime_t2_2 + $casual_t2_2), 2);
            $pay_rate_holiday_2 = number_format(($fulltime_holiday_2 + $casual_holiday_2), 2);
            $pay_rate_early_2 = number_format(($fulltime_early_2 + $casual_early_2), 2);
            $pay_rate_afternoon_2 = number_format(($fulltime_afternoon_2 + $casual_afternoon_2), 2);
            $pay_rate_night_2 = number_format(($fulltime_night_2 + $casual_night_2), 2);
            $pay_rate_saturday_2 = number_format(($fulltime_saturday_2 + $casual_saturday_2), 2);
            $pay_rate_sunday_2 = number_format(($fulltime_sunday_2 + $casual_sunday_2), 2);
        }elseif($this->award == self::FOOD_AWD){
            $casual_t1_2 = number_format(($hourly_rate2 * self::CASUAL_LOADING), 2);
            $casual_t15_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_t2_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);
            $casual_holiday_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T25_OVERTIME), 2);
            $casual_early_2 = number_format(($casual_t1_2 * (1 + self::EARLY_MORNING_LOADING) + 0.01),2);
            $casual_afternoon_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1)), 2);
            $casual_night_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1)), 2);
            $casual_saturday_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_sunday_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);

            $pay_rate_t1_2 = number_format(($hourly_rate2 + $casual_t1_2), 2);
            $pay_rate_t15_2 = number_format(($fulltime_t15_2 + $casual_t15_2), 2);
            $pay_rate_t2_2 = number_format(($fulltime_t2_2 + $casual_t2_2), 2);
            $pay_rate_holiday_2 = number_format(($fulltime_holiday_2 + $casual_holiday_2), 2);
            $pay_rate_early_2 = number_format(($fulltime_early_2 + $casual_early_2), 2);
            $pay_rate_afternoon_2 = number_format(($fulltime_afternoon_2 + $casual_afternoon_2), 2);
            $pay_rate_night_2 = number_format(($fulltime_night_2 + $casual_night_2), 2);
            $pay_rate_saturday_2 = number_format(($fulltime_saturday_2 + $casual_saturday_2), 2);
            $pay_rate_sunday_2 = number_format(($fulltime_sunday_2 + $casual_sunday_2), 2);
        }elseif($this->award == self::RETAIL_AWD){
            $fulltime_t2_2 = number_format(($hourly_rate2 * self::RTT2_OVERTIME),2);
            $fulltime_holiday_2 = number_format(($hourly_rate2 * self::RTT25_OVERTIME),2);
            $fulltime_afternoon_2 = number_format(($hourly_rate2 * (1 + self::RT_AFTERNOON_LOADING)),2);
            $fulltime_sunday_2 = number_format(($hourly_rate2 * (self::RTT2_OVERTIME)),2);

            $casual_t1_2 = number_format(($hourly_rate2 * self::CASUAL_LOADING), 2);
            $casual_t15_2 = $casual_t1_2;
            $casual_t2_2 = $casual_t1_2;
            $casual_holiday_2 = $casual_t1_2;
            $casual_early_2 = $casual_t1_2;
            $casual_afternoon_2 = $casual_t1_2;
            $casual_night_2 = $casual_t1_2;
            $casual_saturday_2 = $casual_t1_2;
            $casual_sunday_2 = $casual_t1_2;

            $pay_rate_t1_2 = number_format(($hourly_rate2 + $casual_t1_2), 2);
            $pay_rate_t15_2 = number_format(($fulltime_t15_2 + $casual_t15_2), 2);
            $pay_rate_t2_2 = number_format(($fulltime_t2_2 + $casual_t2_2), 2);
            $pay_rate_holiday_2 = number_format(($fulltime_holiday_2 + $casual_holiday_2), 2);
            $pay_rate_early_2 = number_format(($fulltime_early_2 + $casual_early_2), 2);
            $pay_rate_afternoon_2 = number_format(($fulltime_afternoon_2 + $casual_afternoon_2), 2);
            $pay_rate_night_2 = number_format(($fulltime_night_2 + $casual_night_2), 2);
            $pay_rate_saturday_2 = number_format(($fulltime_saturday_2 + $casual_saturday_2), 2);
            $pay_rate_sunday_2 = number_format(($fulltime_sunday_2 + $casual_sunday_2), 2);
        }else {
            $casual_t1_2 = number_format(($hourly_rate2 * self::CASUAL_LOADING), 2);
            $casual_t15_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_t2_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);
            $casual_holiday_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T25_OVERTIME), 2);
            $casual_afternoon_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1)), 2);
            $casual_night_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1)), 2);
            $casual_saturday_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_sunday_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);

            $pay_rate_t1_2 = number_format(($hourly_rate2 + ($hourly_rate2 * self::CASUAL_LOADING)), 2);
            $pay_rate_t15_2 = number_format((($hourly_rate2 * self::T15_OVERTIME) + (($hourly_rate2 * self::CASUAL_LOADING) * self::T15_OVERTIME)), 2);
            $pay_rate_t2_2 = number_format((($hourly_rate2 * self::T2_OVERTIME) + (($hourly_rate2 * self::CASUAL_LOADING) * self::T2_OVERTIME)), 2);
            $pay_rate_holiday_2 = number_format((($hourly_rate2 * self::T25_OVERTIME) + (($hourly_rate2 * self::CASUAL_LOADING) * self::T25_OVERTIME)), 2);
            $pay_rate_afternoon_2 = number_format((($hourly_rate2 * (1 + self::AFTERNOON_LOADING)) + (($hourly_rate2 * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1))), 2);
            $pay_rate_night_2 = number_format((($hourly_rate2 * (1 + self::NIGHT_LOADING)) + (($hourly_rate2 * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1))), 2);
            $pay_rate_saturday_2 = number_format((($hourly_rate2 * (self::T15_OVERTIME)) + (($hourly_rate2 * self::CASUAL_LOADING) * self::T15_OVERTIME)), 2);
            $pay_rate_sunday_2 = number_format((($hourly_rate2 * (self::T2_OVERTIME)) + (($hourly_rate2 * self::CASUAL_LOADING) * self::T2_OVERTIME)), 2);
        }
        $super_t1_2 = number_format((($pay_rate_t1_2) * $this->superannuation2),2);
        $super_t15_2 = number_format((0),2);
        $super_t2_2 = number_format((0),2);
        $super_holiday_2 = number_format((($pay_rate_holiday_2) * $this->superannuation2),2);
        $super_early_2 = number_format((($pay_rate_early_2) * $this->superannuation2),2);
        $super_afternoon_2 = number_format((($pay_rate_afternoon_2) * $this->superannuation2),2);
        $super_night_2 = number_format((($pay_rate_night_2) * $this->superannuation2),2);
        $super_saturday_2 = number_format((($pay_rate_saturday_2) * $this->superannuation2),2);
        $super_sunday_2 = number_format((($pay_rate_sunday_2) * $this->superannuation2),2);

        $payrollTax_t1_2 = number_format((($pay_rate_t1_2 + $super_t1_2) * $this->payroll_tax2),2);
        $payrollTax_t15_2 = number_format((($pay_rate_t15_2 + $super_t15_2) * $this->payroll_tax2),2);
        $payrollTax_t2_2 = number_format((($pay_rate_t2_2 + $super_t2_2) * $this->payroll_tax2),2);
        $payrollTax_holiday_2 = number_format((($pay_rate_holiday_2 + $super_holiday_2) * $this->payroll_tax2),2);
        $payrollTax_early_2 = number_format((($pay_rate_early_2 + $super_early_2) * $this->payroll_tax2),2);
        $payrollTax_afternoon_2 = number_format((($pay_rate_afternoon_2 + $super_afternoon_2) * $this->payroll_tax2),2);
        $payrollTax_night_2 = number_format((($pay_rate_night_2 + $super_night_2) * $this->payroll_tax2),2);
        $payrollTax_saturday_2 = number_format((($pay_rate_saturday_2 + $super_saturday_2) * $this->payroll_tax2),2);
        $payrollTax_sunday_2 = number_format((($pay_rate_sunday_2 + $super_sunday_2) * $this->payroll_tax2),2);

        $mhws_t1_2 = number_format((($pay_rate_t1_2 + $super_t1_2) * $this->mhws2),2);
        $mhws_t15_2 = number_format((($pay_rate_t15_2 + $super_t15_2) * $this->mhws2),2);
        $mhws_t2_2 = number_format((($pay_rate_t2_2 + $super_t2_2) * $this->mhws2),2);
        $mhws_holiday_2 = number_format((($pay_rate_holiday_2 + $super_holiday_2) * $this->mhws2),2);
        $mhws_early_2 = number_format((($pay_rate_early_2 + $super_early_2) * $this->mhws2),2);
        $mhws_afternoon_2 = number_format((($pay_rate_afternoon_2 + $super_afternoon_2) * $this->mhws2),2);
        $mhws_night_2 = number_format((($pay_rate_night_2 + $super_night_2) * $this->mhws2),2);
        $mhws_saturday_2 = number_format((($pay_rate_saturday_2 + $super_saturday_2) * $this->mhws2),2);
        $mhws_sunday_2 = number_format((($pay_rate_sunday_2 + $super_sunday_2) * $this->mhws2),2);

        $workcover_t1_2 = number_format((($pay_rate_t1_2 + $super_t1_2) * $this->workcover2),2);
        $workcover_t15_2 = number_format((($pay_rate_t15_2 + $super_t15_2) * $this->workcover2),2);
        $workcover_t2_2 = number_format((($pay_rate_t2_2 + $super_t2_2) * $this->workcover2),2);
        $workcover_holiday_2 = number_format((($pay_rate_holiday_2 + $super_holiday_2) * $this->workcover2),2);
        $workcover_early_2 = number_format((($pay_rate_early_2 + $super_early_2) * $this->workcover2),2);
        $workcover_afternoon_2 = number_format((($pay_rate_afternoon_2 + $super_afternoon_2) * $this->workcover2),2);
        $workcover_night_2 = number_format((($pay_rate_night_2 + $super_night_2) * $this->workcover2),2);
        $workcover_saturday_2 = number_format((($pay_rate_saturday_2 + $super_saturday_2) * $this->workcover2),2);
        $workcover_sunday_2 = number_format((($pay_rate_sunday_2 + $super_sunday_2) * $this->workcover2),2);

        $loadedcost_t1_2 = number_format(($pay_rate_t1_2 + $super_t1_2 + $payrollTax_t1_2 + $mhws_t1_2 + $workcover_t1_2),2);
        $loadedcost_t15_2 = number_format(($pay_rate_t15_2 + $super_t15_2 + $payrollTax_t15_2 + $mhws_t15_2 + $workcover_t15_2),2);
        $loadedcost_t2_2 = number_format(($pay_rate_t2_2 + $super_t2_2 + $payrollTax_t2_2 + $mhws_t2_2 + $workcover_t2_2),2);
        $loadedcost_holiday_2 = number_format(($pay_rate_holiday_2 + $super_holiday_2 + $payrollTax_holiday_2 + $mhws_holiday_2 + $workcover_holiday_2),2);
        $loadedcost_early_2 = number_format(($pay_rate_early_2 + $super_early_2 + $payrollTax_early_2 + $mhws_early_2 + $workcover_early_2),2);
        $loadedcost_afternoon_2 = number_format(($pay_rate_afternoon_2 + $super_afternoon_2 + $payrollTax_afternoon_2 + $mhws_afternoon_2 + $workcover_afternoon_2),2);
        $loadedcost_night_2 = number_format(($pay_rate_night_2 + $super_night_2 + $payrollTax_night_2 + $mhws_night_2 + $workcover_night_2),2);
        $loadedcost_saturday_2 = number_format(($pay_rate_saturday_2 + $super_saturday_2 + $payrollTax_saturday_2 + $mhws_saturday_2 + $workcover_saturday_2),2);
        $loadedcost_sunday_2 = number_format(($pay_rate_sunday_2 + $super_sunday_2 + $payrollTax_sunday_2 + $mhws_sunday_2 + $workcover_sunday_2),2);

        $placement_fee_2 = number_format($this->margin2,2);

        $chargeRate_t1_2 = number_format(($placement_fee_2 + $loadedcost_t1_2),2);
        $chargeRate_t15_2 = number_format(($placement_fee_2 + $loadedcost_t15_2),2);
        $chargeRate_t2_2 = number_format(($placement_fee_2 + $loadedcost_t2_2),2);
        $chargeRate_holiday_2 = number_format(($placement_fee_2 + $loadedcost_holiday_2),2);
        $chargeRate_early_2 = number_format(($placement_fee_2 + $loadedcost_early_2),2);
        $chargeRate_afternoon_2 = number_format(($placement_fee_2 + $loadedcost_afternoon_2),2);
        $chargeRate_night_2 = number_format(($placement_fee_2 + $loadedcost_night_2),2);
        $chargeRate_saturday_2 = number_format(($placement_fee_2 + $loadedcost_saturday_2),2);
        $chargeRate_sunday_2 = number_format(($placement_fee_2 + $loadedcost_sunday_2),2);

        $gst_t1_2 = number_format(($chargeRate_t1_2 * 0.1),2);
        $gst_t15_2 = number_format(($chargeRate_t15_2 * 0.1),2);
        $gst_t2_2 = number_format(($chargeRate_t2_2 * 0.1),2);
        $gst_holiday_2 = number_format(($chargeRate_holiday_2 * 0.1),2);
        $gst_early_2 = number_format(($chargeRate_early_2 * 0.1),2);
        $gst_afternoon_2 = number_format(($chargeRate_afternoon_2 * 0.1),2);
        $gst_night_2 = number_format(($chargeRate_night_2 * 0.1),2);
        $gst_saturday_2 = number_format(($chargeRate_saturday_2 * 0.1),2);
        $gst_sunday_2 = number_format(($chargeRate_sunday_2 * 0.1),2);

        $chg_gst_t1_2 = number_format(($chargeRate_t1_2),2);
        $chg_gst_t15_2 = number_format(($chargeRate_t15_2),2);
        $chg_gst_t2_2 = number_format(($chargeRate_t2_2),2);
        $chg_gst_holiday_2 = number_format(($chargeRate_holiday_2),2);
        $chg_gst_early_2 = number_format(($chargeRate_early_2),2);
        $chg_gst_afternoon_2 = number_format(($chargeRate_afternoon_2),2);
        $chg_gst_night_2 = number_format(($chargeRate_night_2),2);
        $chg_gst_saturday_2 = number_format(($chargeRate_saturday_2),2);
        $chg_gst_sunday_2 = number_format(($chargeRate_sunday_2),2);

        /* --------------------------    hire rate 3    ---------------------------- */

        $hourly_rate3 = $this->hourly_rate3;
        $fulltime_t15_3 = number_format(($hourly_rate3 * self::T15_OVERTIME),2);
        $fulltime_t2_3 = number_format(($hourly_rate3 * self::T2_OVERTIME),2);
        $fulltime_holiday_3 = number_format(($hourly_rate3 * self::T25_OVERTIME),2);
        $fulltime_early_3 = number_format(($hourly_rate3 * (1 + self::EARLY_MORNING_LOADING)),2);
        $fulltime_afternoon_3 = number_format(($hourly_rate3 * (1 + self::AFTERNOON_LOADING)),2);
        $fulltime_night_3 = number_format(($hourly_rate3 * (1 + self::NIGHT_LOADING)),2);
        $fulltime_saturday_3 = number_format(($hourly_rate3 * (self::T15_OVERTIME)),2);
        $fulltime_sunday_3 = number_format(($hourly_rate3 * (self::T2_OVERTIME)),2);
        if($this->award == self::STORAGE_AWD){
            $casual_t1_3 = number_format(($hourly_rate3 * self::CASUAL_LOADING), 2);
            $casual_t15_3 = $casual_t1_3;
            $casual_t2_3 = $casual_t1_3;
            $casual_holiday_3 = $casual_t1_3;
            $casual_early_3 = $casual_t1_3;
            $casual_afternoon_3 = $casual_t1_3;
            $casual_night_3 = $casual_t1_3;
            $casual_saturday_3 = $casual_t1_3;
            $casual_sunday_3 = $casual_t1_3;

            $pay_rate_t1_3 = number_format(($hourly_rate3 + $casual_t1_3), 2);
            $pay_rate_t15_3 = number_format(($fulltime_t15_3 + $casual_t15_3), 2);
            $pay_rate_t2_3 = number_format(($fulltime_t2_3 + $casual_t2_3), 2);
            $pay_rate_holiday_3 = number_format(($fulltime_holiday_3 + $casual_holiday_3), 2);
            $pay_rate_early_3 = number_format(($fulltime_early_3 + $casual_early_3), 2);
            $pay_rate_afternoon_3 = number_format(($fulltime_afternoon_3 + $casual_afternoon_3), 2);
            $pay_rate_night_3 = number_format(($fulltime_night_3 + $casual_night_3), 2);
            $pay_rate_saturday_3 = number_format(($fulltime_saturday_3 + $casual_saturday_3), 2);
            $pay_rate_sunday_3 = number_format(($fulltime_sunday_3 + $casual_sunday_3), 2);
        }elseif($this->award == self::FOOD_AWD){
            $casual_t1_3 = number_format(($hourly_rate3 * self::CASUAL_LOADING), 2);
            $casual_t15_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_t2_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);
            $casual_holiday_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * self::T25_OVERTIME), 2);
            $casual_early_3 = number_format(($casual_t1_3 * (1 + self::EARLY_MORNING_LOADING) + 0.01),2);
            $casual_afternoon_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1)), 2);
            $casual_night_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1)), 2);
            $casual_saturday_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_sunday_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);

            $pay_rate_t1_3 = number_format(($hourly_rate3 + $casual_t1_3), 2);
            $pay_rate_t15_3 = number_format(($fulltime_t15_3 + $casual_t15_3), 2);
            $pay_rate_t2_3 = number_format(($fulltime_t2_3 + $casual_t2_3), 2);
            $pay_rate_holiday_3 = number_format(($fulltime_holiday_3 + $casual_holiday_3), 2);
            $pay_rate_early_3 = number_format(($fulltime_early_3 + $casual_early_3), 2);
            $pay_rate_afternoon_3 = number_format(($fulltime_afternoon_3 + $casual_afternoon_3), 2);
            $pay_rate_night_3 = number_format(($fulltime_night_3 + $casual_night_3), 2);
            $pay_rate_saturday_3 = number_format(($fulltime_saturday_3 + $casual_saturday_3), 2);
            $pay_rate_sunday_3 = number_format(($fulltime_sunday_3 + $casual_sunday_3), 2);
        }elseif($this->award == self::RETAIL_AWD){
            $fulltime_t2_3 = number_format(($hourly_rate3 * self::RTT2_OVERTIME),2);
            $fulltime_holiday_3 = number_format(($hourly_rate3 * self::RTT25_OVERTIME),2);
            $fulltime_afternoon_3 = number_format(($hourly_rate3 * (1 + self::RT_AFTERNOON_LOADING)),2);
            $fulltime_sunday_3 = number_format(($hourly_rate3 * (self::RTT2_OVERTIME)),2);

            $casual_t1_3 = number_format(($hourly_rate3 * self::CASUAL_LOADING), 2);
            $casual_t15_3 = $casual_t1_3;
            $casual_t2_3 = $casual_t1_3;
            $casual_holiday_3 = $casual_t1_3;
            $casual_early_3 = $casual_t1_3;
            $casual_afternoon_3 = $casual_t1_3;
            $casual_night_3 = $casual_t1_3;
            $casual_saturday_3 = $casual_t1_3;
            $casual_sunday_3 = $casual_t1_3;

            $pay_rate_t1_3 = number_format(($hourly_rate3 + $casual_t1_3), 2);
            $pay_rate_t15_3 = number_format(($fulltime_t15_3 + $casual_t15_3), 2);
            $pay_rate_t2_3 = number_format(($fulltime_t2_3 + $casual_t2_3), 2);
            $pay_rate_holiday_3 = number_format(($fulltime_holiday_3 + $casual_holiday_3), 2);
            $pay_rate_early_3 = number_format(($fulltime_early_3 + $casual_early_3), 2);
            $pay_rate_afternoon_3 = number_format(($fulltime_afternoon_3 + $casual_afternoon_3), 2);
            $pay_rate_night_3 = number_format(($fulltime_night_3 + $casual_night_3), 2);
            $pay_rate_saturday_3 = number_format(($fulltime_saturday_3 + $casual_saturday_3), 2);
            $pay_rate_sunday_3 = number_format(($fulltime_sunday_3 + $casual_sunday_3), 2);
        }else {
            $casual_t1_3 = number_format(($hourly_rate3 * self::CASUAL_LOADING), 2);
            $casual_t15_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_t2_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);
            $casual_holiday_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * self::T25_OVERTIME), 2);
            $casual_afternoon_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1)), 2);
            $casual_night_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1)), 2);
            $casual_saturday_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_sunday_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);

            $pay_rate_t1_3 = number_format(($hourly_rate3 + ($hourly_rate3 * self::CASUAL_LOADING)), 2);
            $pay_rate_t15_3 = number_format((($hourly_rate3 * self::T15_OVERTIME) + (($hourly_rate3 * self::CASUAL_LOADING) * self::T15_OVERTIME)), 2);
            $pay_rate_t2_3 = number_format((($hourly_rate3 * self::T2_OVERTIME) + (($hourly_rate3 * self::CASUAL_LOADING) * self::T2_OVERTIME)), 2);
            $pay_rate_holiday_3 = number_format((($hourly_rate3 * self::T25_OVERTIME) + (($hourly_rate3 * self::CASUAL_LOADING) * self::T25_OVERTIME)), 2);
            $pay_rate_afternoon_3 = number_format((($hourly_rate3 * (1 + self::AFTERNOON_LOADING)) + (($hourly_rate3 * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1))), 2);
            $pay_rate_night_3 = number_format((($hourly_rate3 * (1 + self::NIGHT_LOADING)) + (($hourly_rate3 * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1))), 2);
            $pay_rate_saturday_3 = number_format((($hourly_rate3 * (self::T15_OVERTIME)) + (($hourly_rate3 * self::CASUAL_LOADING) * self::T15_OVERTIME)), 2);
            $pay_rate_sunday_3 = number_format((($hourly_rate3 * (self::T2_OVERTIME)) + (($hourly_rate3 * self::CASUAL_LOADING) * self::T2_OVERTIME)), 2);
        }
        $super_t1_3 = number_format((($pay_rate_t1_3) * $this->superannuation3),2);
        $super_t15_3 = number_format(0,2);
        $super_t2_3 = number_format(0,2);
        $super_holiday_3 = number_format((($pay_rate_holiday_3) * $this->superannuation3),2);
        $super_early_3 = number_format((($pay_rate_early_3) * $this->superannuation3),2);
        $super_afternoon_3 = number_format((($pay_rate_afternoon_3) * $this->superannuation3),2);
        $super_night_3 = number_format((($pay_rate_night_3) * $this->superannuation3),2);
        $super_saturday_3 = number_format((($pay_rate_saturday_3) * $this->superannuation3),2);
        $super_sunday_3 = number_format((($pay_rate_sunday_3) * $this->superannuation3),2);

        $payrollTax_t1_3 = number_format((($pay_rate_t1_3 + $super_t1_3) * $this->payroll_tax3),2);
        $payrollTax_t15_3 = number_format((($pay_rate_t15_3 + $super_t15_3) * $this->payroll_tax3),2);
        $payrollTax_t2_3 = number_format((($pay_rate_t2_3 + $super_t2_3) * $this->payroll_tax3),2);
        $payrollTax_holiday_3 = number_format((($pay_rate_holiday_3 + $super_holiday_3) * $this->payroll_tax3),2);
        $payrollTax_early_3 = number_format((($pay_rate_early_3 + $super_early_3) * $this->payroll_tax3),2);
        $payrollTax_afternoon_3 = number_format((($pay_rate_afternoon_3 + $super_afternoon_3) * $this->payroll_tax3),2);
        $payrollTax_night_3 = number_format((($pay_rate_night_3 + $super_night_3) * $this->payroll_tax3),2);
        $payrollTax_saturday_3 = number_format((($pay_rate_saturday_3 + $super_saturday_3) * $this->payroll_tax3),2);
        $payrollTax_sunday_3 = number_format((($pay_rate_sunday_3 + $super_sunday_3) * $this->payroll_tax3),2);

        $mhws_t1_3 = number_format((($pay_rate_t1_3 + $super_t1_3) * $this->mhws3),2);
        $mhws_t15_3 = number_format((($pay_rate_t15_3 + $super_t15_3) * $this->mhws3),2);
        $mhws_t2_3 = number_format((($pay_rate_t2_3 + $super_t2_3) * $this->mhws3),2);
        $mhws_holiday_3 = number_format((($pay_rate_holiday_3 + $super_holiday_3) * $this->mhws3),2);
        $mhws_early_3 = number_format((($pay_rate_early_3 + $super_early_3) * $this->mhws3),2);
        $mhws_afternoon_3 = number_format((($pay_rate_afternoon_3 + $super_afternoon_3) * $this->mhws3),2);
        $mhws_night_3 = number_format((($pay_rate_night_3 + $super_night_3) * $this->mhws3),2);
        $mhws_saturday_3 = number_format((($pay_rate_saturday_3 + $super_saturday_3) * $this->mhws3),2);
        $mhws_sunday_3 = number_format((($pay_rate_sunday_3 + $super_sunday_3) * $this->mhws3),2);

        $workcover_t1_3 = number_format((($pay_rate_t1_3 + $super_t1_3) * $this->workcover3),2);
        $workcover_t15_3 = number_format((($pay_rate_t15_3 + $super_t15_3) * $this->workcover3),2);
        $workcover_t2_3 = number_format((($pay_rate_t2_3 + $super_t2_3) * $this->workcover3),2);
        $workcover_holiday_3 = number_format((($pay_rate_holiday_3 + $super_holiday_3) * $this->workcover3),2);
        $workcover_early_3 = number_format((($pay_rate_early_3 + $super_early_3) * $this->workcover3),2);
        $workcover_afternoon_3 = number_format((($pay_rate_afternoon_3 + $super_afternoon_3) * $this->workcover3),2);
        $workcover_night_3 = number_format((($pay_rate_night_3 + $super_night_3) * $this->workcover3),2);
        $workcover_saturday_3 = number_format((($pay_rate_saturday_3 + $super_saturday_3) * $this->workcover3),2);
        $workcover_sunday_3 = number_format((($pay_rate_sunday_3 + $super_sunday_3) * $this->workcover3),2);

        $loadedcost_t1_3 = number_format(($pay_rate_t1_3 + $super_t1_3 + $payrollTax_t1_3 + $mhws_t1_3 + $workcover_t1_3),2);
        $loadedcost_t15_3 = number_format(($pay_rate_t15_3 + $super_t15_3 + $payrollTax_t15_3 + $mhws_t15_3 + $workcover_t15_3),2);
        $loadedcost_t2_3 = number_format(($pay_rate_t2_3 + $super_t2_3 + $payrollTax_t2_3 + $mhws_t2_3 + $workcover_t2_3),2);
        $loadedcost_holiday_3 = number_format(($pay_rate_holiday_3 + $super_holiday_3 + $payrollTax_holiday_3 + $mhws_holiday_3 + $workcover_holiday_3),2);
        $loadedcost_early_3 = number_format(($pay_rate_early_3 + $super_early_3 + $payrollTax_early_3 + $mhws_early_3 + $workcover_early_3),2);
        $loadedcost_afternoon_3 = number_format(($pay_rate_afternoon_3 + $super_afternoon_3 + $payrollTax_afternoon_3 + $mhws_afternoon_3 + $workcover_afternoon_3),2);
        $loadedcost_night_3 = number_format(($pay_rate_night_3 + $super_night_3 + $payrollTax_night_3 + $mhws_night_3 + $workcover_night_3),2);
        $loadedcost_saturday_3 = number_format(($pay_rate_saturday_3 + $super_saturday_3 + $payrollTax_saturday_3 + $mhws_saturday_3 + $workcover_saturday_3),2);
        $loadedcost_sunday_3 = number_format(($pay_rate_sunday_3 + $super_sunday_3 + $payrollTax_sunday_3 + $mhws_sunday_3 + $workcover_sunday_3),2);

        $placement_fee_3 = number_format($this->margin3,2);

        $chargeRate_t1_3 = number_format(($placement_fee_3 + $loadedcost_t1_3),2);
        $chargeRate_t15_3 = number_format(($placement_fee_3 + $loadedcost_t15_3),2);
        $chargeRate_t2_3 = number_format(($placement_fee_3 + $loadedcost_t2_3),2);
        $chargeRate_holiday_3 = number_format(($placement_fee_3 + $loadedcost_holiday_3),2);
        $chargeRate_early_3 = number_format(($placement_fee_3 + $loadedcost_early_3),2);
        $chargeRate_afternoon_3 = number_format(($placement_fee_3 + $loadedcost_afternoon_3),2);
        $chargeRate_night_3 = number_format(($placement_fee_3 + $loadedcost_night_3),2);
        $chargeRate_saturday_3 = number_format(($placement_fee_3 + $loadedcost_saturday_3),2);
        $chargeRate_sunday_3 = number_format(($placement_fee_3 + $loadedcost_sunday_3),2);

        $gst_t1_3 = number_format(($chargeRate_t1_3 * 0.1),2);
        $gst_t15_3 = number_format(($chargeRate_t15_3 * 0.1),2);
        $gst_t2_3 = number_format(($chargeRate_t2_3 * 0.1),2);
        $gst_holiday_3 = number_format(($chargeRate_holiday_3 * 0.1),2);
        $gst_early_3 = number_format(($chargeRate_early_3 * 0.1),2);
        $gst_afternoon_3 = number_format(($chargeRate_afternoon_3 * 0.1),2);
        $gst_night_3 = number_format(($chargeRate_night_3 * 0.1),2);
        $gst_saturday_3 = number_format(($chargeRate_saturday_3 * 0.1),2);
        $gst_sunday_3 = number_format(($chargeRate_sunday_3 * 0.1),2);

        $chg_gst_t1_3 = number_format(($chargeRate_t1_3),2);
        $chg_gst_t15_3 = number_format(($chargeRate_t15_3),2);
        $chg_gst_t2_3 = number_format(($chargeRate_t2_3),2);
        $chg_gst_holiday_3 = number_format(($chargeRate_holiday_3),2);
        $chg_gst_early_3 = number_format(($chargeRate_early_3),2);
        $chg_gst_afternoon_3 = number_format(($chargeRate_afternoon_3),2);
        $chg_gst_night_3 = number_format(($chargeRate_night_3),2);
        $chg_gst_saturday_3 = number_format(($chargeRate_saturday_3),2);
        $chg_gst_sunday_3 = number_format(($chargeRate_sunday_3),2);

        $html = $html.'<table cellspacing="0" style="background-color: #FFFFFF; border: none; border-color: #FFFFFF">
            <thead style="border: none; background-color: #FFFFFF; background: #FFFFFF">
              <tr style="border: none; background-color: #FFFFFF; background: #FFFFFF">
                <th style="border: none; background-color: #FFFFFF; background: #FFFFFF"></th>
                <th style="border: none; background-color: #FFFFFF; background: #FFFFFF"></th>
                <th style="border: none; background-color: #FFFFFF; background: #FFFFFF"></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="text-align: right; width: 35%"><span style="font-size: 12pt; color: #0d0f12; font-weight: bold; border: 1px solid red">Rates presented to:</span>
                    <br>
                        <span style="font-size: 10pt; color: #0AA699; font-weight: bold;">'.$this->client.'</span>
                    <br>
                    <br>
                        <span style="font-size: 12pt; color: #0d0f12; font-weight: bold">Award/EBA:</span>
                        <br>
                        <span style="font-size: 10pt; color: #0AA699; font-weight: bold">'.$this->award.'</span></td>
                <td></td>
                <td><img src="img/logo.png" width="220" height="50" border="0" alt=""/>
                    <br>
                    <img src="'.$this->client_logo.'"  alt="">
                </td>
              </tr>
            </tbody>
          </table>';
        $html = $html.'<br>';
        $html = $html.'';
        $html = $html.'<br></div>';

        $html = $html.'<br>';
        $html = $html.'<div style="font-size: 22pt; color: red; font-weight: bold">Labour <br>';
        $html = $html.'Hire Rates</div>';
        $html = $html.'<br>';
        $html = $html.'<div style="font-size: 14pt; font-weight: bold; font-style: italic"><b>Classification: '.$this->position.'</b></div>';
        $html = $html.'<br>';

        if($this->breakdown == 'CHARGE RATE'){
            $html = $html . '<div align="center"><table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <thead>
              <tr>
                <th style="width: 17%">Description</th>
                <th>T1.0 Ordinary</th>
                <th>T1.5 Overtime</th>
                <th>T2.0 Overtime</th>
                <th>Public Holiday</th>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<th>Early Morning</th>';
            }
            $html = $html.'<th>Afternoon</th>
                <th>Night</th>
                <th>Saturday</th>
                <th>Sunday</th>
              </tr>
            </thead>
            <tbody>';
            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Charge Rate</td>
                <td>' . $chg_gst_t1 . '</td>
                <td>' . $chg_gst_t15 . '</td>
                <td>' . $chg_gst_t2 . '</td>
                <td>' . $chg_gst_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>' . $chg_gst_early . '</td>';
            }
            $html = $html.'<td>' . $chg_gst_afternoon . '</td>
                <td>' . $chg_gst_night . '</td>
                <td>' . $chg_gst_saturday . '</td>
                <td>' . $chg_gst_sunday . '</td></tr>';
            $html = $html . '</tbody>
          </table></div><br>';
        }else {
            $html = $html . '<div align="center"><table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <thead>
              <tr>
                <th style="width: 17%">Description</th>
                <th>T1.0 Ordinary</th>
                <th>T1.5 Overtime</th>
                <th>T2.0 Overtime</th>
                <th>Public Holiday</th>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<th>Early Morning</th>';
            }
            $html = $html.'<th>Afternoon</th>
                <th>Night</th>
                <th>Saturday</th>
                <th>Sunday</th>
              </tr>
            </thead>
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Fulltime Rate</td>
                <td>' . $hourly_rate . '</td>
                <td>' . $fulltime_t15 . '</td>
                <td>' . $fulltime_t2 . '</td>
                <td>' . $fulltime_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>' . $fulltime_early . '</td>';
            }
            $html = $html.'<td>' . $fulltime_afternoon . '</td>
                <td>' . $fulltime_night . '</td>
                <td>' . $fulltime_saturday . '</td>
                <td>' . $fulltime_sunday . '</td></tr>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Casual Loading</td>
                <td>' . $casual_t1 . '</td>
                <td>' . $casual_t15 . '</td>
                <td>' . $casual_t2 . '</td>
                <td>' . $casual_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>' . $casual_early . '</td>';
            }
            $html = $html.'<td>' . $casual_afternoon . '</td>
                <td>' . $casual_night . '</td>
                <td>' . $casual_saturday . '</td>
                <td>' . $casual_sunday . '</td></tr>';

            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Pay Rate</td>
                <td>' . $pay_rate_t1 . '</td>
                <td>' . $pay_rate_t15 . '</td>
                <td>' . $pay_rate_t2 . '</td>
                <td>' . $pay_rate_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>' . $pay_rate_early . '</td>';
            }
            $html =$html.'<td>' . $pay_rate_afternoon . '</td>
                <td>' . $pay_rate_night . '</td>
                <td>' . $pay_rate_saturday . '</td>
                <td>' . $pay_rate_sunday . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Superannuation</td>
                <td>' . $super_t1 . '</td>
                <td>' . $super_t15 . '</td>
                <td>' . $super_t2 . '</td>
                <td>' . $super_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>' . $super_early . '</td>';
            }
            $html = $html.'<td>' . $super_afternoon . '</td>
                <td>' . $super_night . '</td>
                <td>' . $super_saturday . '</td>
                <td>' . $super_sunday . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Payroll Tax</td>
                <td>' . $payrollTax_t1 . '</td>
                <td>' . $payrollTax_t15 . '</td>
                <td>' . $payrollTax_t2 . '</td>
                <td>' . $payrollTax_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>' . $payrollTax_early . '</td>';
            }
            $html = $html.'<td>' . $payrollTax_afternoon . '</td>
                <td>' . $payrollTax_night . '</td>
                <td>' . $payrollTax_saturday . '</td>
                <td>' . $payrollTax_sunday . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">MHWS</td>
                <td>' . $mhws_t1 . '</td>
                <td>' . $mhws_t15 . '</td>
                <td>' . $mhws_t2 . '</td>
                <td>' . $mhws_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>' . $mhws_early . '</td>';
            }
            $html = $html.'<td>' . $mhws_afternoon . '</td>
                <td>' . $mhws_night . '</td>
                <td>' . $mhws_saturday . '</td>
                <td>' . $mhws_sunday . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">WorkCover</td>
                <td>' . $workcover_t1 . '</td>
                <td>' . $workcover_t15 . '</td>
                <td>' . $workcover_t2 . '</td>
                <td>' . $workcover_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$workcover_early.'</td>';
            }
            $html = $html.'<td>' . $workcover_afternoon . '</td>
                <td>' . $workcover_night . '</td>
                <td>' . $workcover_saturday . '</td>
                <td>' . $workcover_sunday . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Loaded Cost</td>
                <td>' . $loadedcost_t1 . '</td>
                <td>' . $loadedcost_t15 . '</td>
                <td>' . $loadedcost_t2 . '</td>
                <td>' . $loadedcost_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$loadedcost_early.'</td>';
            }
            $html = $html.'<td>' . $loadedcost_afternoon . '</td>
                <td>' . $loadedcost_night . '</td>
                <td>' . $loadedcost_saturday . '</td>
                <td>' . $loadedcost_sunday . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Placement Fee</td>
                <td>' . $placement_fee . '</td>
                <td>' . $placement_fee . '</td>
                <td>' . $placement_fee . '</td>
                <td>' . $placement_fee . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$placement_fee.'</td>';
            }
            $html = $html.'<td>' . $placement_fee . '</td>
                <td>' . $placement_fee . '</td>
                <td>' . $placement_fee . '</td>
                <td>' . $placement_fee . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Charge Rate</td>
                <td>' . $chargeRate_t1 . '</td>
                <td>' . $chargeRate_t15 . '</td>
                <td>' . $chargeRate_t2 . '</td>
                <td>' . $chargeRate_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$chargeRate_early.'</td>';
            }
            $html = $html.'<td>' . $chargeRate_afternoon . '</td>
                <td>' . $chargeRate_night . '</td>
                <td>' . $chargeRate_saturday . '</td>
                <td>' . $chargeRate_sunday . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br>';

            $html = $html.'</div>';
        }

        $html = $html.'<br><div style="font-size: 14pt; font-weight: bold; font-style: italic"><b>Classification: '.$this->position2.'</b></div>';
        $html = $html.'<br>';
        /*  --------------------------   hire rate 2 html   ----------------------- */
        if($this->breakdown == 'CHARGE RATE'){
            $html = $html . '<div align="center"><table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <thead>
              <tr>
                <th style="width: 17%">Description</th>
                <th>T1.0 Ordinary</th>
                <th>T1.5 Overtime</th>
                <th>T2.0 Overtime</th>
                <th>Public Holiday</th>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<th>Early Morning</th>';
            }
            $html = $html.'<th>Afternoon</th>
                <th>Night</th>
                <th>Saturday</th>
                <th>Sunday</th>
              </tr>
            </thead>
            <tbody>';
            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Charge Rate</td>
                <td>' . $chg_gst_t1_2 . '</td>
                <td>' . $chg_gst_t15_2 . '</td>
                <td>' . $chg_gst_t2_2 . '</td>
                <td>' . $chg_gst_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$chg_gst_early_2.'</td>';
            }
            $html = $html.'<td>' . $chg_gst_afternoon_2 . '</td>
                <td>' . $chg_gst_night_2 . '</td>
                <td>' . $chg_gst_saturday_2 . '</td>
                <td>' . $chg_gst_sunday_2 . '</td></tr>';
            $html = $html . '</tbody>
          </table></div><br>';
        }else {
            $html = $html . '<div align="center"><table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <thead>
              <tr>
                <th style="width: 17%">Description</th>
                <th>T1.0 Ordinary</th>
                <th>T1.5 Overtime</th>
                <th>T2.0 Overtime</th>
                <th>Public Holiday</th>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<th>Early Morning</th>';
            }
            $html = $html.'<th>Afternoon</th>
                <th>Night</th>
                <th>Saturday</th>
                <th>Sunday</th>
              </tr>
            </thead>
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Fulltime Rate</td>
                <td>' . $hourly_rate2 . '</td>
                <td>' . $fulltime_t15_2 . '</td>
                <td>' . $fulltime_t2_2 . '</td>
                <td>' . $fulltime_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$fulltime_early_2.'</td>';
            }
            $html = $html.'<td>' . $fulltime_afternoon_2 . '</td>
                <td>' . $fulltime_night_2 . '</td>
                <td>' . $fulltime_saturday_2 . '</td>
                <td>' . $fulltime_sunday_2 . '</td></tr>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Casual Loading</td>
                <td>' . $casual_t1_2 . '</td>
                <td>' . $casual_t15_2 . '</td>
                <td>' . $casual_t2_2 . '</td>
                <td>' . $casual_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$casual_early_2.'</td>';
            }
            $html = $html.'<td>' . $casual_afternoon_2 . '</td>
                <td>' . $casual_night_2 . '</td>
                <td>' . $casual_saturday_2 . '</td>
                <td>' . $casual_sunday_2 . '</td></tr>';

            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Pay Rate</td>
                <td>' . $pay_rate_t1_2 . '</td>
                <td>' . $pay_rate_t15_2 . '</td>
                <td>' . $pay_rate_t2_2 . '</td>
                <td>' . $pay_rate_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$pay_rate_early_2.'</td>';
            }
            $html = $html.'<td>' . $pay_rate_afternoon_2 . '</td>
                <td>' . $pay_rate_night_2 . '</td>
                <td>' . $pay_rate_saturday_2 . '</td>
                <td>' . $pay_rate_sunday_2 . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Superannuation</td>
                <td>' . $super_t1_2 . '</td>
                <td>' . $super_t15_2 . '</td>
                <td>' . $super_t2_2 . '</td>
                <td>' . $super_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$super_early_2.'</td>';
            }
            $html = $html.'<td>' . $super_afternoon_2 . '</td>
                <td>' . $super_night_2 . '</td>
                <td>' . $super_saturday_2 . '</td>
                <td>' . $super_sunday_2 . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Payroll Tax</td>
                <td>' . $payrollTax_t1_2 . '</td>
                <td>' . $payrollTax_t15_2 . '</td>
                <td>' . $payrollTax_t2_2 . '</td>
                <td>' . $payrollTax_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$payrollTax_early_2.'</td>';
            }
            $html = $html.'<td>' . $payrollTax_afternoon_2 . '</td>
                <td>' . $payrollTax_night_2 . '</td>
                <td>' . $payrollTax_saturday_2 . '</td>
                <td>' . $payrollTax_sunday_2 . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">MHWS</td>
                <td>' . $mhws_t1_2 . '</td>
                <td>' . $mhws_t15_2 . '</td>
                <td>' . $mhws_t2_2 . '</td>
                <td>' . $mhws_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$mhws_early_2.'</td>';
            }
            $html = $html.'<td>' . $mhws_afternoon_2 . '</td>
                <td>' . $mhws_night_2 . '</td>
                <td>' . $mhws_saturday_2 . '</td>
                <td>' . $mhws_sunday_2 . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">WorkCover</td>
                <td>' . $workcover_t1_2 . '</td>
                <td>' . $workcover_t15_2 . '</td>
                <td>' . $workcover_t2_2 . '</td>
                <td>' . $workcover_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$workcover_early_2.'</td>';
            }
            $html = $html.'<td>' . $workcover_afternoon_2 . '</td>
                <td>' . $workcover_night_2 . '</td>
                <td>' . $workcover_saturday_2 . '</td>
                <td>' . $workcover_sunday_2 . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Loaded Cost</td>
                <td>' . $loadedcost_t1_2 . '</td>
                <td>' . $loadedcost_t15_2 . '</td>
                <td>' . $loadedcost_t2_2 . '</td>
                <td>' . $loadedcost_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$loadedcost_early_2.'</td>';
            }
            $html = $html.'<td>' . $loadedcost_afternoon_2 . '</td>
                <td>' . $loadedcost_night_2 . '</td>
                <td>' . $loadedcost_saturday_2 . '</td>
                <td>' . $loadedcost_sunday_2 . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Placement Fee</td>
                <td>' . $placement_fee_2 . '</td>
                <td>' . $placement_fee_2 . '</td>
                <td>' . $placement_fee_2 . '</td>
                <td>' . $placement_fee_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$placement_fee_2.'</td>';
            }
            $html = $html.'<td>' . $placement_fee_2 . '</td>
                <td>' . $placement_fee_2 . '</td>
                <td>' . $placement_fee_2 . '</td>
                <td>' . $placement_fee_2 . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Charge Rate</td>
                <td>' . $chargeRate_t1_2 . '</td>
                <td>' . $chargeRate_t15_2 . '</td>
                <td>' . $chargeRate_t2_2 . '</td>
                <td>' . $chargeRate_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$chargeRate_early_2.'</td>';
            }
            $html = $html.'<td>' . $chargeRate_afternoon_2 . '</td>
                <td>' . $chargeRate_night_2 . '</td>
                <td>' . $chargeRate_saturday_2 . '</td>
                <td>' . $chargeRate_sunday_2 . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br>';
            $html = $html .'</div>';
        }
        $html = $html.'<br pagebreak="true"><div style="font-size: 14pt; font-weight: bold; font-style: italic"><b>Classification: '.$this->position3.'</b></div>';
        $html = $html.'<br>';
        /*  --------------------------   hire rate 3 html   ----------------------- */
        if($this->breakdown == 'CHARGE RATE'){
            $html = $html . '<div align="center"><table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <thead>
              <tr>
                <th style="width: 17%">Description</th>
                <th>T1.0 Ordinary</th>
                <th>T1.5 Overtime</th>
                <th>T2.0 Overtime</th>
                <th>Public Holiday</th>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<th>Early Morning</th>';
            }
            $html = $html.'<th>Afternoon</th>
                <th>Night</th>
                <th>Saturday</th>
                <th>Sunday</th>
              </tr>
            </thead>
            <tbody>';
            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Charge Rate</td>
                <td>' . $chg_gst_t1_3 . '</td>
                <td>' . $chg_gst_t15_3 . '</td>
                <td>' . $chg_gst_t2_3 . '</td>
                <td>' . $chg_gst_holiday_3 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$chg_gst_early_3.'</td>';
            }
            $html = $html.'<td>' . $chg_gst_afternoon_3 . '</td>
                <td>' . $chg_gst_night_3 . '</td>
                <td>' . $chg_gst_saturday_3 . '</td>
                <td>' . $chg_gst_sunday_3 . '</td></tr>';
            $html = $html . '</tbody>
          </table></div><br>';
        }else {
            $html = $html . '<div align="center"><table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <thead>
              <tr>
                <th style="width: 17%">Description</th>
                <th>T1.0 Ordinary</th>
                <th>T1.5 Overtime</th>
                <th>T2.0 Overtime</th>
                <th>Public Holiday</th>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<th>Early Morning</th>';
            }
            $html = $html.'<th>Afternoon</th>
                <th>Night</th>
                <th>Saturday</th>
                <th>Sunday</th>
              </tr>
            </thead>
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Fulltime Rate</td>
                <td>' . $hourly_rate3 . '</td>
                <td>' . $fulltime_t15_3 . '</td>
                <td>' . $fulltime_t2_3 . '</td>
                <td>' . $fulltime_holiday_3 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$fulltime_early_3.'</td>';
            }
            $html = $html.'<td>' . $fulltime_afternoon_3 . '</td>
                <td>' . $fulltime_night_3 . '</td>
                <td>' . $fulltime_saturday_3 . '</td>
                <td>' . $fulltime_sunday_3 . '</td></tr>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Casual Loading</td>
                <td>' . $casual_t1_3 . '</td>
                <td>' . $casual_t15_3 . '</td>
                <td>' . $casual_t2_3 . '</td>
                <td>' . $casual_holiday_3 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$casual_early_3.'</td>';
            }
            $html = $html.'<td>' . $casual_afternoon_3 . '</td>
                <td>' . $casual_night_3 . '</td>
                <td>' . $casual_saturday_3 . '</td>
                <td>' . $casual_sunday_3 . '</td></tr>';

            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Pay Rate</td>
                <td>' . $pay_rate_t1_3 . '</td>
                <td>' . $pay_rate_t15_3 . '</td>
                <td>' . $pay_rate_t2_3 . '</td>
                <td>' . $pay_rate_holiday_3 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$pay_rate_early_3.'</td>';
            }
            $html = $html.'<td>' . $pay_rate_afternoon_3 . '</td>
                <td>' . $pay_rate_night_3 . '</td>
                <td>' . $pay_rate_saturday_3 . '</td>
                <td>' . $pay_rate_sunday_3 . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Superannuation</td>
                <td>' . $super_t1_3 . '</td>
                <td>' . $super_t15_3 . '</td>
                <td>' . $super_t2_3 . '</td>
                <td>' . $super_holiday_3 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$super_early_3.'</td>';
            }
            $html = $html.'<td>' . $super_afternoon_3 . '</td>
                <td>' . $super_night_3 . '</td>
                <td>' . $super_saturday_3 . '</td>
                <td>' . $super_sunday_3 . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Payroll Tax</td>
                <td>' . $payrollTax_t1_3 . '</td>
                <td>' . $payrollTax_t15_3 . '</td>
                <td>' . $payrollTax_t2_3 . '</td>
                <td>' . $payrollTax_holiday_3 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$payrollTax_early_3.'</td>';
            }
            $html = $html.'<td>' . $payrollTax_afternoon_3 . '</td>
                <td>' . $payrollTax_night_3 . '</td>
                <td>' . $payrollTax_saturday_3 . '</td>
                <td>' . $payrollTax_sunday_3 . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">MHWS</td>
                <td>' . $mhws_t1_3 . '</td>
                <td>' . $mhws_t15_3 . '</td>
                <td>' . $mhws_t2_3 . '</td>
                <td>' . $mhws_holiday_3 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$mhws_early_3.'</td>';
            }
            $html = $html.'<td>' . $mhws_afternoon_3 . '</td>
                <td>' . $mhws_night_3 . '</td>
                <td>' . $mhws_saturday_3 . '</td>
                <td>' . $mhws_sunday_3 . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">WorkCover</td>
                <td>' . $workcover_t1_3 . '</td>
                <td>' . $workcover_t15_3 . '</td>
                <td>' . $workcover_t2_3 . '</td>
                <td>' . $workcover_holiday_3 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$workcover_early_3.'</td>';
            }
            $html = $html.'<td>' . $workcover_afternoon_3 . '</td>
                <td>' . $workcover_night_3 . '</td>
                <td>' . $workcover_saturday_3 . '</td>
                <td>' . $workcover_sunday_3 . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Loaded Cost</td>
                <td>' . $loadedcost_t1_3 . '</td>
                <td>' . $loadedcost_t15_3 . '</td>
                <td>' . $loadedcost_t2_3 . '</td>
                <td>' . $loadedcost_holiday_3 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$loadedcost_early_3.'</td>';
            }
            $html = $html.'<td>' . $loadedcost_afternoon_3 . '</td>
                <td>' . $loadedcost_night_3 . '</td>
                <td>' . $loadedcost_saturday_3 . '</td>
                <td>' . $loadedcost_sunday_3 . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Placement Fee</td>
                <td>' . $placement_fee_3 . '</td>
                <td>' . $placement_fee_3 . '</td>
                <td>' . $placement_fee_3 . '</td>
                <td>' . $placement_fee_3 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$placement_fee_3.'</td>';
            }
            $html = $html.'<td>' . $placement_fee_3 . '</td>
                <td>' . $placement_fee_3 . '</td>
                <td>' . $placement_fee_3 . '</td>
                <td>' . $placement_fee_3 . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Charge Rate</td>
                <td>' . $chargeRate_t1_3 . '</td>
                <td>' . $chargeRate_t15_3 . '</td>
                <td>' . $chargeRate_t2_3 . '</td>
                <td>' . $chargeRate_holiday_3 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$chargeRate_early_3.'</td>';
            }
            $html = $html.'<td>' . $chargeRate_afternoon_3 . '</td>
                <td>' . $chargeRate_night_3 . '</td>
                <td>' . $chargeRate_saturday_3 . '</td>
                <td>' . $chargeRate_sunday_3 . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br>';
          $html = $html.'</div>';
        }


        $html = $html.'<div style="text-align: right">Please note charge rates expressed are exclusive of GST.<br>Payment terms - '.$this->payment_terms.' Days from Invoice date</div><br>';
        $html = $html.'I agree to the rates and terms proposed by Chandler Services and understand the rules and regulations according to the '.$this->award;
        $html = $html.'<br><br>';
        /*$html = $html.'<table style="background-color: #FFFFFF; border: none; border-color: #FFFFFF">
                        <thead style="border: none; background-color: #FFFFFF; background: #FFFFFF">
                             <tr style="border: none; background-color: #FFFFFF; background: #FFFFFF">
                                <th style="border: none; background-color: #FFFFFF; background: #FFFFFF"></th>
                                <th style="border: none; background-color: #FFFFFF; background: #FFFFFF"></th>
                                <th style="border: none; background-color: #FFFFFF; background: #FFFFFF"></th>
                                <th style="border: none; background-color: #FFFFFF; background: #FFFFFF"></th>
                             </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td style="width: 45%">Name: ..............................................................................</td>
                            <td style="width: 5%"></td>
                            <td style="width: 20%">Received T&C s</td>
                            <td style="width: 20%"><input type="checkbox" name="box1" value="1" checked="" style="border: 1px solid black"/>Yes<input type="checkbox" name="box2" value="1" checked="" style="border: 1px solid black"/> No</td>
                          </tr>
                          <tr><td colspan="4"></td></tr>
                          <tr>
                            <td style="width: 45%">Title: ................................................................................</td>
                            <td style="width: 5%"></td>
                            <td style="width: 20%"></td>
                            <td style="width: 20%"></td>
                          </tr>
                          <tr><td colspan="4"></td></tr>
                           <tr>
                            <td style="width: 45%">Company: ..........................................................................</td>
                            <td style="width: 5%"></td>
                            <td style="width: 20%"></td>
                            <td style="width: 20%"></td>
                          </tr>
                          <tr><td colspan="4"></td></tr>
                            <tr>
                            <td style="width: 45%">Signature: ..........................................................................</td>
                            <td style="width: 5%"></td>
                            <td style="width: 20%">Date:</td>
                            <td style="width: 20%"></td>
                          </tr>
                        </tbody>
                      </table>';*/

        $fileName = 'hireRate_'.time().'-'.mt_rand();
        $filePathPDF = './rates/'.$fileName.'.pdf';
        $pdf->writeHTML($html, true, false, false, false, '');

        $pdf->lastPage();
        $pdf->Output(__DIR__.'/rates/'.$fileName.'.pdf', 'F');
        echo $filePathPDF;
    }
    public function calculateHireRateForFourPositions(){

        $pdf = new HIRERATEPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setHeaderTemplateAutoreset(true);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('');
        $pdf->SetTitle('');
        $pdf->SetSubject('');
        $pdf->SetKeywords('');
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 8));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        define('PDF_CUSTOM_HEADER_STRING',' ');
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_CUSTOM_HEADER_STRING);
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        $pdf->SetFont('calibri', '', 9);
        /*$fontname = TCPDF_FONTS::addTTFfont("includes/TCPDF-master/fonts/Roboto-Regular.ttf",'TrueTypeUnicode','',32);
        $pdf->SetFont($fontname, '', 10);*/
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        $pdf->AddPage();
        $html = '';
        $html = $html.'<style>
tr{ 
text-transform: uppercase;
}

table {
    border-color:gray;
}
th{
    text-align: center;
    background-color: #5b9ad5;
    color: #FFFFFF;
}
.zebra0{
    background-color: #cbd2d5;
}
.zebra1{
    background-color: white;
}
</style>';

        $hourly_rate = $this->hourly_rate;
        $fulltime_t15 = number_format(($hourly_rate * self::T15_OVERTIME),2);
        $fulltime_t2 = number_format(($hourly_rate * self::T2_OVERTIME),2);
        $fulltime_holiday = number_format(($hourly_rate * self::T25_OVERTIME),2);
        $fulltime_early = number_format(($hourly_rate * (1 + self::EARLY_MORNING_LOADING)),2);
        $fulltime_afternoon = number_format(($hourly_rate * (1 + self::AFTERNOON_LOADING)),2);
        $fulltime_night = number_format(($hourly_rate * (1 + self::NIGHT_LOADING)),2);
        $fulltime_saturday = number_format(($hourly_rate * (self::T15_OVERTIME)),2);
        $fulltime_sunday = number_format(($hourly_rate * (self::T2_OVERTIME)),2);

        if($this->award == self::STORAGE_AWD){
            $casual_t1 = number_format(($hourly_rate * self::CASUAL_LOADING), 2);
            $casual_t15 = $casual_t1;
            $casual_t2 = $casual_t1;
            $casual_holiday = $casual_t1;
            $casual_early = $casual_t1;
            $casual_afternoon = $casual_t1;
            $casual_night = $casual_t1;
            $casual_saturday = $casual_t1;
            $casual_sunday = $casual_t1;

            $pay_rate_t1 = number_format(($hourly_rate + $casual_t1), 2);
            $pay_rate_t15 = number_format(($fulltime_t15 + $casual_t15), 2);
            $pay_rate_t2 = number_format(($fulltime_t2 + $casual_t2), 2);
            $pay_rate_holiday = number_format(($fulltime_holiday + $casual_holiday), 2);
            $pay_rate_early = number_format(($fulltime_early + $casual_early), 2);
            $pay_rate_afternoon = number_format(($fulltime_afternoon + $casual_afternoon), 2);
            $pay_rate_night = number_format(($fulltime_night + $casual_night), 2);
            $pay_rate_saturday = number_format(($fulltime_saturday + $casual_saturday), 2);
            $pay_rate_sunday = number_format(($fulltime_sunday + $casual_sunday), 2);

        }elseif($this->award == self::FOOD_AWD){
            $casual_t1 = number_format(($hourly_rate * self::CASUAL_LOADING), 2);
            $casual_t15 = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_t2 = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);
            $casual_holiday = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T25_OVERTIME), 2);
            $casual_early = number_format(($casual_t1 * (1 + self::EARLY_MORNING_LOADING) + 0.01),2);
            $casual_afternoon = number_format((($hourly_rate * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1)), 2);
            $casual_night = number_format((($hourly_rate * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1)), 2);
            $casual_saturday = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_sunday = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);

            $pay_rate_t1 = number_format(($hourly_rate + $casual_t1), 2);
            $pay_rate_t15 = number_format(($fulltime_t15 + $casual_t15), 2);
            $pay_rate_t2 = number_format(($fulltime_t2 + $casual_t2), 2);
            $pay_rate_holiday = number_format(($fulltime_holiday + $casual_holiday), 2);
            $pay_rate_early = number_format(($fulltime_early + $casual_early), 2);
            $pay_rate_afternoon = number_format(($fulltime_afternoon + $casual_afternoon), 2);
            $pay_rate_night = number_format(($fulltime_night + $casual_night), 2);
            $pay_rate_saturday = number_format(($fulltime_saturday + $casual_saturday), 2);
            $pay_rate_sunday = number_format(($fulltime_sunday + $casual_sunday), 2);
        }elseif($this->award == self::RETAIL_AWD){
            $fulltime_t2 = number_format(($hourly_rate * self::RTT2_OVERTIME),2);
            $fulltime_holiday = number_format(($hourly_rate * self::RTT25_OVERTIME),2);
            $fulltime_afternoon = number_format(($hourly_rate * (1 + self::RT_AFTERNOON_LOADING)),2);
            $fulltime_sunday = number_format(($hourly_rate * (self::RTT2_OVERTIME)),2);

            $casual_t1 = number_format(($hourly_rate * self::CASUAL_LOADING), 2);
            $casual_t15 = $casual_t1;
            $casual_t2 = $casual_t1;
            $casual_holiday = $casual_t1;
            $casual_early = $casual_t1;
            $casual_afternoon = $casual_t1;
            $casual_night = $casual_t1;
            $casual_saturday = $casual_t1;
            $casual_sunday = $casual_t1;

            $pay_rate_t1 = number_format(($hourly_rate + $casual_t1), 2);
            $pay_rate_t15 = number_format(($fulltime_t15 + $casual_t15), 2);
            $pay_rate_t2 = number_format(($fulltime_t2 + $casual_t2), 2);
            $pay_rate_holiday = number_format(($fulltime_holiday + $casual_holiday), 2);
            $pay_rate_early = number_format(($fulltime_early + $casual_early), 2);
            $pay_rate_afternoon = number_format(($fulltime_afternoon + $casual_afternoon), 2);
            $pay_rate_night = number_format(($fulltime_night + $casual_night), 2);
            $pay_rate_saturday = number_format(($fulltime_saturday + $casual_saturday), 2);
            $pay_rate_sunday = number_format(($fulltime_sunday + $casual_sunday), 2);
        }else {
            $casual_t1 = number_format(($hourly_rate * self::CASUAL_LOADING), 2);
            $casual_t15 = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_t2 = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);
            $casual_holiday = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T25_OVERTIME), 2);
            $casual_afternoon = number_format((($hourly_rate * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1)), 2);
            $casual_night = number_format((($hourly_rate * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1)), 2);
            $casual_saturday = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_sunday = number_format((($hourly_rate * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);

            $pay_rate_t1 = number_format(($hourly_rate + ($hourly_rate * self::CASUAL_LOADING)), 2);
            $pay_rate_t15 = number_format((($hourly_rate * self::T15_OVERTIME) + (($hourly_rate * self::CASUAL_LOADING) * self::T15_OVERTIME)), 2);
            $pay_rate_t2 = number_format((($hourly_rate * self::T2_OVERTIME) + (($hourly_rate * self::CASUAL_LOADING) * self::T2_OVERTIME)), 2);
            $pay_rate_holiday = number_format((($hourly_rate * self::T25_OVERTIME) + (($hourly_rate * self::CASUAL_LOADING) * self::T25_OVERTIME)), 2);
            $pay_rate_afternoon = number_format((($hourly_rate * (1 + self::AFTERNOON_LOADING)) + (($hourly_rate * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1))), 2);
            $pay_rate_night = number_format((($hourly_rate * (1 + self::NIGHT_LOADING)) + (($hourly_rate * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1))), 2);
            $pay_rate_saturday = number_format((($hourly_rate * (self::T15_OVERTIME)) + (($hourly_rate * self::CASUAL_LOADING) * self::T15_OVERTIME)), 2);
            $pay_rate_sunday = number_format((($hourly_rate * (self::T2_OVERTIME)) + (($hourly_rate * self::CASUAL_LOADING) * self::T2_OVERTIME)), 2);
        }
        $super_t1 = number_format((($pay_rate_t1) * $this->superannuation),2);
        $super_t15 = number_format((0),2);
        $super_t2 = number_format((0),2);
        $super_holiday = number_format((($pay_rate_holiday) * $this->superannuation),2);
        $super_early = number_format((($pay_rate_early) * $this->superannuation),2);
        $super_afternoon = number_format((($pay_rate_afternoon) * $this->superannuation),2);
        $super_night = number_format((($pay_rate_night) * $this->superannuation),2);
        $super_saturday = number_format((($pay_rate_saturday) * $this->superannuation),2);
        $super_sunday = number_format((($pay_rate_sunday) * $this->superannuation),2);

        $payrollTax_t1 = number_format((($pay_rate_t1 + $super_t1) * $this->payroll_tax),2);
        $payrollTax_t15 = number_format((($pay_rate_t15 + $super_t15) * $this->payroll_tax),2);
        $payrollTax_t2 = number_format((($pay_rate_t2 + $super_t2) * $this->payroll_tax),2);
        $payrollTax_holiday = number_format((($pay_rate_holiday + $super_holiday) * $this->payroll_tax),2);
        $payrollTax_early = number_format((($pay_rate_early + $super_early) * $this->payroll_tax),2);
        $payrollTax_afternoon = number_format((($pay_rate_afternoon + $super_afternoon) * $this->payroll_tax),2);
        $payrollTax_night = number_format((($pay_rate_night + $super_night) * $this->payroll_tax),2);
        $payrollTax_saturday = number_format((($pay_rate_saturday + $super_saturday) * $this->payroll_tax),2);
        $payrollTax_sunday = number_format((($pay_rate_sunday + $super_sunday) * $this->payroll_tax),2);

        $mhws_t1 = number_format((($pay_rate_t1 + $super_t1) * $this->mhws),2);
        $mhws_t15 = number_format((($pay_rate_t15 + $super_t15) * $this->mhws),2);
        $mhws_t2 = number_format((($pay_rate_t2 + $super_t2) * $this->mhws),2);
        $mhws_holiday = number_format((($pay_rate_holiday + $super_holiday) * $this->mhws),2);
        $mhws_early = number_format((($pay_rate_early + $super_early) * $this->mhws),2);
        $mhws_afternoon = number_format((($pay_rate_afternoon + $super_afternoon) * $this->mhws),2);
        $mhws_night = number_format((($pay_rate_night + $super_night) * $this->mhws),2);
        $mhws_saturday = number_format((($pay_rate_saturday + $super_saturday) * $this->mhws),2);
        $mhws_sunday = number_format((($pay_rate_sunday + $super_sunday) * $this->mhws),2);

        $workcover_t1 = number_format((($pay_rate_t1 + $super_t1) * $this->workcover),2);
        $workcover_t15 = number_format((($pay_rate_t15 + $super_t15) * $this->workcover),2);
        $workcover_t2 = number_format((($pay_rate_t2 + $super_t2) * $this->workcover),2);
        $workcover_holiday = number_format((($pay_rate_holiday + $super_holiday) * $this->workcover),2);
        $workcover_early = number_format((($pay_rate_early + $super_early) * $this->workcover),2);
        $workcover_afternoon = number_format((($pay_rate_afternoon + $super_afternoon) * $this->workcover),2);
        $workcover_night = number_format((($pay_rate_night + $super_night) * $this->workcover),2);
        $workcover_saturday = number_format((($pay_rate_saturday + $super_saturday) * $this->workcover),2);
        $workcover_sunday = number_format((($pay_rate_sunday + $super_sunday) * $this->workcover),2);

        $loadedcost_t1 = number_format(($pay_rate_t1 + $super_t1 + $payrollTax_t1 + $mhws_t1 + $workcover_t1),2);
        $loadedcost_t15 = number_format(($pay_rate_t15 + $super_t15 + $payrollTax_t15 + $mhws_t15 + $workcover_t15),2);
        $loadedcost_t2 = number_format(($pay_rate_t2 + $super_t2 + $payrollTax_t2 + $mhws_t2 + $workcover_t2),2);
        $loadedcost_holiday = number_format(($pay_rate_holiday + $super_holiday + $payrollTax_holiday + $mhws_holiday + $workcover_holiday),2);
        $loadedcost_early = number_format(($pay_rate_early + $super_early + $payrollTax_early + $mhws_early + $workcover_early),2);
        $loadedcost_afternoon = number_format(($pay_rate_afternoon + $super_afternoon + $payrollTax_afternoon + $mhws_afternoon + $workcover_afternoon),2);
        $loadedcost_night = number_format(($pay_rate_night + $super_night + $payrollTax_night + $mhws_night + $workcover_night),2);
        $loadedcost_saturday = number_format(($pay_rate_saturday + $super_saturday + $payrollTax_saturday + $mhws_saturday + $workcover_saturday),2);
        $loadedcost_sunday = number_format(($pay_rate_sunday + $super_sunday + $payrollTax_sunday + $mhws_sunday + $workcover_sunday),2);

        $placement_fee = number_format($this->margin,2);

        $chargeRate_t1 = number_format(($placement_fee + $loadedcost_t1),2);
        $chargeRate_t15 = number_format(($placement_fee + $loadedcost_t15),2);
        $chargeRate_t2 = number_format(($placement_fee + $loadedcost_t2),2);
        $chargeRate_holiday = number_format(($placement_fee + $loadedcost_holiday),2);
        $chargeRate_early = number_format(($placement_fee + $loadedcost_early),2);
        $chargeRate_afternoon = number_format(($placement_fee + $loadedcost_afternoon),2);
        $chargeRate_night = number_format(($placement_fee + $loadedcost_night),2);
        $chargeRate_saturday = number_format(($placement_fee + $loadedcost_saturday),2);
        $chargeRate_sunday = number_format(($placement_fee + $loadedcost_sunday),2);

        $gst_t1 = number_format(($chargeRate_t1 * 0.1),2);
        $gst_t15 = number_format(($chargeRate_t15 * 0.1),2);
        $gst_t2 = number_format(($chargeRate_t2 * 0.1),2);
        $gst_holiday = number_format(($chargeRate_holiday * 0.1),2);
        $gst_early = number_format(($chargeRate_early * 0.1),2);
        $gst_afternoon = number_format(($chargeRate_afternoon * 0.1),2);
        $gst_night = number_format(($chargeRate_night * 0.1),2);
        $gst_saturday = number_format(($chargeRate_saturday * 0.1),2);
        $gst_sunday = number_format(($chargeRate_sunday * 0.1),2);

        $chg_gst_t1 = number_format(($chargeRate_t1),2);
        $chg_gst_t15 = number_format(($chargeRate_t15),2);
        $chg_gst_t2 = number_format(($chargeRate_t2),2);
        $chg_gst_holiday = number_format(($chargeRate_holiday),2);
        $chg_gst_early = number_format(($chargeRate_early),2);
        $chg_gst_afternoon = number_format(($chargeRate_afternoon),2);
        $chg_gst_night = number_format(($chargeRate_night),2);
        $chg_gst_saturday = number_format(($chargeRate_saturday),2);
        $chg_gst_sunday = number_format(($chargeRate_sunday),2);

        /* --------------------------    hire rate 2    ---------------------------- */

        $hourly_rate2 = $this->hourly_rate2;
        $fulltime_t15_2 = number_format(($hourly_rate2 * self::T15_OVERTIME),2);
        $fulltime_t2_2 = number_format(($hourly_rate2 * self::T2_OVERTIME),2);
        $fulltime_holiday_2 = number_format(($hourly_rate2 * self::T25_OVERTIME),2);
        $fulltime_early_2 =  number_format(($hourly_rate2 * (1 + self::EARLY_MORNING_LOADING)),2);
        $fulltime_afternoon_2 = number_format(($hourly_rate2 * (1 + self::AFTERNOON_LOADING)),2);
        $fulltime_night_2 = number_format(($hourly_rate2 * (1 + self::NIGHT_LOADING)),2);
        $fulltime_saturday_2 = number_format(($hourly_rate2 * (self::T15_OVERTIME)),2);
        $fulltime_sunday_2 = number_format(($hourly_rate2 * (self::T2_OVERTIME)),2);

        if($this->award == self::STORAGE_AWD){
            $casual_t1_2 = number_format(($hourly_rate2 * self::CASUAL_LOADING), 2);
            $casual_t15_2 = $casual_t1_2;
            $casual_t2_2 = $casual_t1_2;
            $casual_holiday_2 = $casual_t1_2;
            $casual_early_2 = $casual_t1_2;
            $casual_afternoon_2 = $casual_t1_2;
            $casual_night_2 = $casual_t1_2;
            $casual_saturday_2 = $casual_t1_2;
            $casual_sunday_2 = $casual_t1_2;

            $pay_rate_t1_2 = number_format(($hourly_rate2 + $casual_t1_2), 2);
            $pay_rate_t15_2 = number_format(($fulltime_t15_2 + $casual_t15_2), 2);
            $pay_rate_t2_2 = number_format(($fulltime_t2_2 + $casual_t2_2), 2);
            $pay_rate_holiday_2 = number_format(($fulltime_holiday_2 + $casual_holiday_2), 2);
            $pay_rate_early_2 = number_format(($fulltime_early_2 + $casual_early_2), 2);
            $pay_rate_afternoon_2 = number_format(($fulltime_afternoon_2 + $casual_afternoon_2), 2);
            $pay_rate_night_2 = number_format(($fulltime_night_2 + $casual_night_2), 2);
            $pay_rate_saturday_2 = number_format(($fulltime_saturday_2 + $casual_saturday_2), 2);
            $pay_rate_sunday_2 = number_format(($fulltime_sunday_2 + $casual_sunday_2), 2);
        }elseif($this->award == self::FOOD_AWD){
            $casual_t1_2 = number_format(($hourly_rate2 * self::CASUAL_LOADING), 2);
            $casual_t15_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_t2_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);
            $casual_holiday_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T25_OVERTIME), 2);
            $casual_early_2 = number_format(($casual_t1_2 * (1 + self::EARLY_MORNING_LOADING) + 0.01),2);
            $casual_afternoon_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1)), 2);
            $casual_night_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1)), 2);
            $casual_saturday_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_sunday_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);

            $pay_rate_t1_2 = number_format(($hourly_rate2 + $casual_t1_2), 2);
            $pay_rate_t15_2 = number_format(($fulltime_t15_2 + $casual_t15_2), 2);
            $pay_rate_t2_2 = number_format(($fulltime_t2_2 + $casual_t2_2), 2);
            $pay_rate_holiday_2 = number_format(($fulltime_holiday_2 + $casual_holiday_2), 2);
            $pay_rate_early_2 = number_format(($fulltime_early_2 + $casual_early_2), 2);
            $pay_rate_afternoon_2 = number_format(($fulltime_afternoon_2 + $casual_afternoon_2), 2);
            $pay_rate_night_2 = number_format(($fulltime_night_2 + $casual_night_2), 2);
            $pay_rate_saturday_2 = number_format(($fulltime_saturday_2 + $casual_saturday_2), 2);
            $pay_rate_sunday_2 = number_format(($fulltime_sunday_2 + $casual_sunday_2), 2);
        }elseif($this->award == self::RETAIL_AWD){
            $fulltime_t2_2 = number_format(($hourly_rate2 * self::RTT2_OVERTIME),2);
            $fulltime_holiday_2 = number_format(($hourly_rate2 * self::RTT25_OVERTIME),2);
            $fulltime_afternoon_2 = number_format(($hourly_rate2 * (1 + self::RT_AFTERNOON_LOADING)),2);
            $fulltime_sunday_2 = number_format(($hourly_rate2 * (self::RTT2_OVERTIME)),2);

            $casual_t1_2 = number_format(($hourly_rate2 * self::CASUAL_LOADING), 2);
            $casual_t15_2 = $casual_t1_2;
            $casual_t2_2 = $casual_t1_2;
            $casual_holiday_2 = $casual_t1_2;
            $casual_early_2 = $casual_t1_2;
            $casual_afternoon_2 = $casual_t1_2;
            $casual_night_2 = $casual_t1_2;
            $casual_saturday_2 = $casual_t1_2;
            $casual_sunday_2 = $casual_t1_2;

            $pay_rate_t1_2 = number_format(($hourly_rate2 + $casual_t1_2), 2);
            $pay_rate_t15_2 = number_format(($fulltime_t15_2 + $casual_t15_2), 2);
            $pay_rate_t2_2 = number_format(($fulltime_t2_2 + $casual_t2_2), 2);
            $pay_rate_holiday_2 = number_format(($fulltime_holiday_2 + $casual_holiday_2), 2);
            $pay_rate_early_2 = number_format(($fulltime_early_2 + $casual_early_2), 2);
            $pay_rate_afternoon_2 = number_format(($fulltime_afternoon_2 + $casual_afternoon_2), 2);
            $pay_rate_night_2 = number_format(($fulltime_night_2 + $casual_night_2), 2);
            $pay_rate_saturday_2 = number_format(($fulltime_saturday_2 + $casual_saturday_2), 2);
            $pay_rate_sunday_2 = number_format(($fulltime_sunday_2 + $casual_sunday_2), 2);
        }else {
            $casual_t1_2 = number_format(($hourly_rate2 * self::CASUAL_LOADING), 2);
            $casual_t15_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_t2_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);
            $casual_holiday_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T25_OVERTIME), 2);
            $casual_afternoon_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1)), 2);
            $casual_night_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1)), 2);
            $casual_saturday_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_sunday_2 = number_format((($hourly_rate2 * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);

            $pay_rate_t1_2 = number_format(($hourly_rate2 + ($hourly_rate2 * self::CASUAL_LOADING)), 2);
            $pay_rate_t15_2 = number_format((($hourly_rate2 * self::T15_OVERTIME) + (($hourly_rate2 * self::CASUAL_LOADING) * self::T15_OVERTIME)), 2);
            $pay_rate_t2_2 = number_format((($hourly_rate2 * self::T2_OVERTIME) + (($hourly_rate2 * self::CASUAL_LOADING) * self::T2_OVERTIME)), 2);
            $pay_rate_holiday_2 = number_format((($hourly_rate2 * self::T25_OVERTIME) + (($hourly_rate2 * self::CASUAL_LOADING) * self::T25_OVERTIME)), 2);
            $pay_rate_afternoon_2 = number_format((($hourly_rate2 * (1 + self::AFTERNOON_LOADING)) + (($hourly_rate2 * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1))), 2);
            $pay_rate_night_2 = number_format((($hourly_rate2 * (1 + self::NIGHT_LOADING)) + (($hourly_rate2 * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1))), 2);
            $pay_rate_saturday_2 = number_format((($hourly_rate2 * (self::T15_OVERTIME)) + (($hourly_rate2 * self::CASUAL_LOADING) * self::T15_OVERTIME)), 2);
            $pay_rate_sunday_2 = number_format((($hourly_rate2 * (self::T2_OVERTIME)) + (($hourly_rate2 * self::CASUAL_LOADING) * self::T2_OVERTIME)), 2);
        }
        $super_t1_2 = number_format((($pay_rate_t1_2) * $this->superannuation2),2);
        $super_t15_2 = number_format((0),2);
        $super_t2_2 = number_format((0),2);
        $super_holiday_2 = number_format((($pay_rate_holiday_2) * $this->superannuation2),2);
        $super_early_2 = number_format((($pay_rate_early_2) * $this->superannuation2),2);
        $super_afternoon_2 = number_format((($pay_rate_afternoon_2) * $this->superannuation2),2);
        $super_night_2 = number_format((($pay_rate_night_2) * $this->superannuation2),2);
        $super_saturday_2 = number_format((($pay_rate_saturday_2) * $this->superannuation2),2);
        $super_sunday_2 = number_format((($pay_rate_sunday_2) * $this->superannuation2),2);

        $payrollTax_t1_2 = number_format((($pay_rate_t1_2 + $super_t1_2) * $this->payroll_tax2),2);
        $payrollTax_t15_2 = number_format((($pay_rate_t15_2 + $super_t15_2) * $this->payroll_tax2),2);
        $payrollTax_t2_2 = number_format((($pay_rate_t2_2 + $super_t2_2) * $this->payroll_tax2),2);
        $payrollTax_holiday_2 = number_format((($pay_rate_holiday_2 + $super_holiday_2) * $this->payroll_tax2),2);
        $payrollTax_early_2 = number_format((($pay_rate_early_2 + $super_early_2) * $this->payroll_tax2),2);
        $payrollTax_afternoon_2 = number_format((($pay_rate_afternoon_2 + $super_afternoon_2) * $this->payroll_tax2),2);
        $payrollTax_night_2 = number_format((($pay_rate_night_2 + $super_night_2) * $this->payroll_tax2),2);
        $payrollTax_saturday_2 = number_format((($pay_rate_saturday_2 + $super_saturday_2) * $this->payroll_tax2),2);
        $payrollTax_sunday_2 = number_format((($pay_rate_sunday_2 + $super_sunday_2) * $this->payroll_tax2),2);

        $mhws_t1_2 = number_format((($pay_rate_t1_2 + $super_t1_2) * $this->mhws2),2);
        $mhws_t15_2 = number_format((($pay_rate_t15_2 + $super_t15_2) * $this->mhws2),2);
        $mhws_t2_2 = number_format((($pay_rate_t2_2 + $super_t2_2) * $this->mhws2),2);
        $mhws_holiday_2 = number_format((($pay_rate_holiday_2 + $super_holiday_2) * $this->mhws2),2);
        $mhws_early_2 = number_format((($pay_rate_early_2 + $super_early_2) * $this->mhws2),2);
        $mhws_afternoon_2 = number_format((($pay_rate_afternoon_2 + $super_afternoon_2) * $this->mhws2),2);
        $mhws_night_2 = number_format((($pay_rate_night_2 + $super_night_2) * $this->mhws2),2);
        $mhws_saturday_2 = number_format((($pay_rate_saturday_2 + $super_saturday_2) * $this->mhws2),2);
        $mhws_sunday_2 = number_format((($pay_rate_sunday_2 + $super_sunday_2) * $this->mhws2),2);

        $workcover_t1_2 = number_format((($pay_rate_t1_2 + $super_t1_2) * $this->workcover2),2);
        $workcover_t15_2 = number_format((($pay_rate_t15_2 + $super_t15_2) * $this->workcover2),2);
        $workcover_t2_2 = number_format((($pay_rate_t2_2 + $super_t2_2) * $this->workcover2),2);
        $workcover_holiday_2 = number_format((($pay_rate_holiday_2 + $super_holiday_2) * $this->workcover2),2);
        $workcover_early_2 = number_format((($pay_rate_early_2 + $super_early_2) * $this->workcover2),2);
        $workcover_afternoon_2 = number_format((($pay_rate_afternoon_2 + $super_afternoon_2) * $this->workcover2),2);
        $workcover_night_2 = number_format((($pay_rate_night_2 + $super_night_2) * $this->workcover2),2);
        $workcover_saturday_2 = number_format((($pay_rate_saturday_2 + $super_saturday_2) * $this->workcover2),2);
        $workcover_sunday_2 = number_format((($pay_rate_sunday_2 + $super_sunday_2) * $this->workcover2),2);

        $loadedcost_t1_2 = number_format(($pay_rate_t1_2 + $super_t1_2 + $payrollTax_t1_2 + $mhws_t1_2 + $workcover_t1_2),2);
        $loadedcost_t15_2 = number_format(($pay_rate_t15_2 + $super_t15_2 + $payrollTax_t15_2 + $mhws_t15_2 + $workcover_t15_2),2);
        $loadedcost_t2_2 = number_format(($pay_rate_t2_2 + $super_t2_2 + $payrollTax_t2_2 + $mhws_t2_2 + $workcover_t2_2),2);
        $loadedcost_holiday_2 = number_format(($pay_rate_holiday_2 + $super_holiday_2 + $payrollTax_holiday_2 + $mhws_holiday_2 + $workcover_holiday_2),2);
        $loadedcost_early_2 = number_format(($pay_rate_early_2 + $super_early_2 + $payrollTax_early_2 + $mhws_early_2 + $workcover_early_2),2);
        $loadedcost_afternoon_2 = number_format(($pay_rate_afternoon_2 + $super_afternoon_2 + $payrollTax_afternoon_2 + $mhws_afternoon_2 + $workcover_afternoon_2),2);
        $loadedcost_night_2 = number_format(($pay_rate_night_2 + $super_night_2 + $payrollTax_night_2 + $mhws_night_2 + $workcover_night_2),2);
        $loadedcost_saturday_2 = number_format(($pay_rate_saturday_2 + $super_saturday_2 + $payrollTax_saturday_2 + $mhws_saturday_2 + $workcover_saturday_2),2);
        $loadedcost_sunday_2 = number_format(($pay_rate_sunday_2 + $super_sunday_2 + $payrollTax_sunday_2 + $mhws_sunday_2 + $workcover_sunday_2),2);

        $placement_fee_2 = number_format($this->margin2,2);

        $chargeRate_t1_2 = number_format(($placement_fee_2 + $loadedcost_t1_2),2);
        $chargeRate_t15_2 = number_format(($placement_fee_2 + $loadedcost_t15_2),2);
        $chargeRate_t2_2 = number_format(($placement_fee_2 + $loadedcost_t2_2),2);
        $chargeRate_holiday_2 = number_format(($placement_fee_2 + $loadedcost_holiday_2),2);
        $chargeRate_early_2 = number_format(($placement_fee_2 + $loadedcost_early_2),2);
        $chargeRate_afternoon_2 = number_format(($placement_fee_2 + $loadedcost_afternoon_2),2);
        $chargeRate_night_2 = number_format(($placement_fee_2 + $loadedcost_night_2),2);
        $chargeRate_saturday_2 = number_format(($placement_fee_2 + $loadedcost_saturday_2),2);
        $chargeRate_sunday_2 = number_format(($placement_fee_2 + $loadedcost_sunday_2),2);

        $gst_t1_2 = number_format(($chargeRate_t1_2 * 0.1),2);
        $gst_t15_2 = number_format(($chargeRate_t15_2 * 0.1),2);
        $gst_t2_2 = number_format(($chargeRate_t2_2 * 0.1),2);
        $gst_holiday_2 = number_format(($chargeRate_holiday_2 * 0.1),2);
        $gst_early_2 = number_format(($chargeRate_early_2 * 0.1),2);
        $gst_afternoon_2 = number_format(($chargeRate_afternoon_2 * 0.1),2);
        $gst_night_2 = number_format(($chargeRate_night_2 * 0.1),2);
        $gst_saturday_2 = number_format(($chargeRate_saturday_2 * 0.1),2);
        $gst_sunday_2 = number_format(($chargeRate_sunday_2 * 0.1),2);

        $chg_gst_t1_2 = number_format(($chargeRate_t1_2),2);
        $chg_gst_t15_2 = number_format(($chargeRate_t15_2),2);
        $chg_gst_t2_2 = number_format(($chargeRate_t2_2),2);
        $chg_gst_holiday_2 = number_format(($chargeRate_holiday_2),2);
        $chg_gst_early_2 = number_format(($chargeRate_early_2),2);
        $chg_gst_afternoon_2 = number_format(($chargeRate_afternoon_2),2);
        $chg_gst_night_2 = number_format(($chargeRate_night_2),2);
        $chg_gst_saturday_2 = number_format(($chargeRate_saturday_2),2);
        $chg_gst_sunday_2 = number_format(($chargeRate_sunday_2),2);

        /* --------------------------    hire rate 3    ---------------------------- */

        $hourly_rate3 = $this->hourly_rate3;
        $fulltime_t15_3 = number_format(($hourly_rate3 * self::T15_OVERTIME),2);
        $fulltime_t2_3 = number_format(($hourly_rate3 * self::T2_OVERTIME),2);
        $fulltime_holiday_3 = number_format(($hourly_rate3 * self::T25_OVERTIME),2);
        $fulltime_early_3 = number_format(($hourly_rate3 * (1 + self::EARLY_MORNING_LOADING)),2);
        $fulltime_afternoon_3 = number_format(($hourly_rate3 * (1 + self::AFTERNOON_LOADING)),2);
        $fulltime_night_3 = number_format(($hourly_rate3 * (1 + self::NIGHT_LOADING)),2);
        $fulltime_saturday_3 = number_format(($hourly_rate3 * (self::T15_OVERTIME)),2);
        $fulltime_sunday_3 = number_format(($hourly_rate3 * (self::T2_OVERTIME)),2);
        if($this->award == self::STORAGE_AWD){
            $casual_t1_3 = number_format(($hourly_rate3 * self::CASUAL_LOADING), 2);
            $casual_t15_3 = $casual_t1_3;
            $casual_t2_3 = $casual_t1_3;
            $casual_holiday_3 = $casual_t1_3;
            $casual_early_3 = $casual_t1_3;
            $casual_afternoon_3 = $casual_t1_3;
            $casual_night_3 = $casual_t1_3;
            $casual_saturday_3 = $casual_t1_3;
            $casual_sunday_3 = $casual_t1_3;

            $pay_rate_t1_3 = number_format(($hourly_rate3 + $casual_t1_3), 2);
            $pay_rate_t15_3 = number_format(($fulltime_t15_3 + $casual_t15_3), 2);
            $pay_rate_t2_3 = number_format(($fulltime_t2_3 + $casual_t2_3), 2);
            $pay_rate_holiday_3 = number_format(($fulltime_holiday_3 + $casual_holiday_3), 2);
            $pay_rate_early_3 = number_format(($fulltime_early_3 + $casual_early_3), 2);
            $pay_rate_afternoon_3 = number_format(($fulltime_afternoon_3 + $casual_afternoon_3), 2);
            $pay_rate_night_3 = number_format(($fulltime_night_3 + $casual_night_3), 2);
            $pay_rate_saturday_3 = number_format(($fulltime_saturday_3 + $casual_saturday_3), 2);
            $pay_rate_sunday_3 = number_format(($fulltime_sunday_3 + $casual_sunday_3), 2);
        }elseif($this->award == self::FOOD_AWD){
            $casual_t1_3 = number_format(($hourly_rate3 * self::CASUAL_LOADING), 2);
            $casual_t15_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_t2_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);
            $casual_holiday_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * self::T25_OVERTIME), 2);
            $casual_early_3 = number_format(($casual_t1_3 * (1 + self::EARLY_MORNING_LOADING) + 0.01),2);
            $casual_afternoon_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1)), 2);
            $casual_night_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1)), 2);
            $casual_saturday_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_sunday_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);

            $pay_rate_t1_3 = number_format(($hourly_rate3 + $casual_t1_3), 2);
            $pay_rate_t15_3 = number_format(($fulltime_t15_3 + $casual_t15_3), 2);
            $pay_rate_t2_3 = number_format(($fulltime_t2_3 + $casual_t2_3), 2);
            $pay_rate_holiday_3 = number_format(($fulltime_holiday_3 + $casual_holiday_3), 2);
            $pay_rate_early_3 = number_format(($fulltime_early_3 + $casual_early_3), 2);
            $pay_rate_afternoon_3 = number_format(($fulltime_afternoon_3 + $casual_afternoon_3), 2);
            $pay_rate_night_3 = number_format(($fulltime_night_3 + $casual_night_3), 2);
            $pay_rate_saturday_3 = number_format(($fulltime_saturday_3 + $casual_saturday_3), 2);
            $pay_rate_sunday_3 = number_format(($fulltime_sunday_3 + $casual_sunday_3), 2);
        }elseif($this->award == self::RETAIL_AWD){
            $fulltime_t2_3 = number_format(($hourly_rate3 * self::RTT2_OVERTIME),2);
            $fulltime_holiday_3 = number_format(($hourly_rate3 * self::RTT25_OVERTIME),2);
            $fulltime_afternoon_3 = number_format(($hourly_rate3 * (1 + self::RT_AFTERNOON_LOADING)),2);
            $fulltime_sunday_3 = number_format(($hourly_rate3 * (self::RTT2_OVERTIME)),2);

            $casual_t1_3 = number_format(($hourly_rate3 * self::CASUAL_LOADING), 2);
            $casual_t15_3 = $casual_t1_3;
            $casual_t2_3 = $casual_t1_3;
            $casual_holiday_3 = $casual_t1_3;
            $casual_early_3 = $casual_t1_3;
            $casual_afternoon_3 = $casual_t1_3;
            $casual_night_3 = $casual_t1_3;
            $casual_saturday_3 = $casual_t1_3;
            $casual_sunday_3 = $casual_t1_3;

            $pay_rate_t1_3 = number_format(($hourly_rate3 + $casual_t1_3), 2);
            $pay_rate_t15_3 = number_format(($fulltime_t15_3 + $casual_t15_3), 2);
            $pay_rate_t2_3 = number_format(($fulltime_t2_3 + $casual_t2_3), 2);
            $pay_rate_holiday_3 = number_format(($fulltime_holiday_3 + $casual_holiday_3), 2);
            $pay_rate_early_3 = number_format(($fulltime_early_3 + $casual_early_3), 2);
            $pay_rate_afternoon_3 = number_format(($fulltime_afternoon_3 + $casual_afternoon_3), 2);
            $pay_rate_night_3 = number_format(($fulltime_night_3 + $casual_night_3), 2);
            $pay_rate_saturday_3 = number_format(($fulltime_saturday_3 + $casual_saturday_3), 2);
            $pay_rate_sunday_3 = number_format(($fulltime_sunday_3 + $casual_sunday_3), 2);
        }else {
            $casual_t1_3 = number_format(($hourly_rate3 * self::CASUAL_LOADING), 2);
            $casual_t15_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_t2_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);
            $casual_holiday_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * self::T25_OVERTIME), 2);
            $casual_afternoon_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1)), 2);
            $casual_night_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1)), 2);
            $casual_saturday_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_sunday_3 = number_format((($hourly_rate3 * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);

            $pay_rate_t1_3 = number_format(($hourly_rate3 + ($hourly_rate3 * self::CASUAL_LOADING)), 2);
            $pay_rate_t15_3 = number_format((($hourly_rate3 * self::T15_OVERTIME) + (($hourly_rate3 * self::CASUAL_LOADING) * self::T15_OVERTIME)), 2);
            $pay_rate_t2_3 = number_format((($hourly_rate3 * self::T2_OVERTIME) + (($hourly_rate3 * self::CASUAL_LOADING) * self::T2_OVERTIME)), 2);
            $pay_rate_holiday_3 = number_format((($hourly_rate3 * self::T25_OVERTIME) + (($hourly_rate3 * self::CASUAL_LOADING) * self::T25_OVERTIME)), 2);
            $pay_rate_afternoon_3 = number_format((($hourly_rate3 * (1 + self::AFTERNOON_LOADING)) + (($hourly_rate3 * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1))), 2);
            $pay_rate_night_3 = number_format((($hourly_rate3 * (1 + self::NIGHT_LOADING)) + (($hourly_rate3 * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1))), 2);
            $pay_rate_saturday_3 = number_format((($hourly_rate3 * (self::T15_OVERTIME)) + (($hourly_rate3 * self::CASUAL_LOADING) * self::T15_OVERTIME)), 2);
            $pay_rate_sunday_3 = number_format((($hourly_rate3 * (self::T2_OVERTIME)) + (($hourly_rate3 * self::CASUAL_LOADING) * self::T2_OVERTIME)), 2);
        }
        $super_t1_3 = number_format((($pay_rate_t1_3) * $this->superannuation3),2);
        $super_t15_3 = number_format(0,2);
        $super_t2_3 = number_format(0,2);
        $super_holiday_3 = number_format((($pay_rate_holiday_3) * $this->superannuation3),2);
        $super_early_3 = number_format((($pay_rate_early_3) * $this->superannuation3),2);
        $super_afternoon_3 = number_format((($pay_rate_afternoon_3) * $this->superannuation3),2);
        $super_night_3 = number_format((($pay_rate_night_3) * $this->superannuation3),2);
        $super_saturday_3 = number_format((($pay_rate_saturday_3) * $this->superannuation3),2);
        $super_sunday_3 = number_format((($pay_rate_sunday_3) * $this->superannuation3),2);

        $payrollTax_t1_3 = number_format((($pay_rate_t1_3 + $super_t1_3) * $this->payroll_tax3),2);
        $payrollTax_t15_3 = number_format((($pay_rate_t15_3 + $super_t15_3) * $this->payroll_tax3),2);
        $payrollTax_t2_3 = number_format((($pay_rate_t2_3 + $super_t2_3) * $this->payroll_tax3),2);
        $payrollTax_holiday_3 = number_format((($pay_rate_holiday_3 + $super_holiday_3) * $this->payroll_tax3),2);
        $payrollTax_early_3 = number_format((($pay_rate_early_3 + $super_early_3) * $this->payroll_tax3),2);
        $payrollTax_afternoon_3 = number_format((($pay_rate_afternoon_3 + $super_afternoon_3) * $this->payroll_tax3),2);
        $payrollTax_night_3 = number_format((($pay_rate_night_3 + $super_night_3) * $this->payroll_tax3),2);
        $payrollTax_saturday_3 = number_format((($pay_rate_saturday_3 + $super_saturday_3) * $this->payroll_tax3),2);
        $payrollTax_sunday_3 = number_format((($pay_rate_sunday_3 + $super_sunday_3) * $this->payroll_tax3),2);

        $mhws_t1_3 = number_format((($pay_rate_t1_3 + $super_t1_3) * $this->mhws3),2);
        $mhws_t15_3 = number_format((($pay_rate_t15_3 + $super_t15_3) * $this->mhws3),2);
        $mhws_t2_3 = number_format((($pay_rate_t2_3 + $super_t2_3) * $this->mhws3),2);
        $mhws_holiday_3 = number_format((($pay_rate_holiday_3 + $super_holiday_3) * $this->mhws3),2);
        $mhws_early_3 = number_format((($pay_rate_early_3 + $super_early_3) * $this->mhws3),2);
        $mhws_afternoon_3 = number_format((($pay_rate_afternoon_3 + $super_afternoon_3) * $this->mhws3),2);
        $mhws_night_3 = number_format((($pay_rate_night_3 + $super_night_3) * $this->mhws3),2);
        $mhws_saturday_3 = number_format((($pay_rate_saturday_3 + $super_saturday_3) * $this->mhws3),2);
        $mhws_sunday_3 = number_format((($pay_rate_sunday_3 + $super_sunday_3) * $this->mhws3),2);

        $workcover_t1_3 = number_format((($pay_rate_t1_3 + $super_t1_3) * $this->workcover3),2);
        $workcover_t15_3 = number_format((($pay_rate_t15_3 + $super_t15_3) * $this->workcover3),2);
        $workcover_t2_3 = number_format((($pay_rate_t2_3 + $super_t2_3) * $this->workcover3),2);
        $workcover_holiday_3 = number_format((($pay_rate_holiday_3 + $super_holiday_3) * $this->workcover3),2);
        $workcover_early_3 = number_format((($pay_rate_early_3 + $super_early_3) * $this->workcover3),2);
        $workcover_afternoon_3 = number_format((($pay_rate_afternoon_3 + $super_afternoon_3) * $this->workcover3),2);
        $workcover_night_3 = number_format((($pay_rate_night_3 + $super_night_3) * $this->workcover3),2);
        $workcover_saturday_3 = number_format((($pay_rate_saturday_3 + $super_saturday_3) * $this->workcover3),2);
        $workcover_sunday_3 = number_format((($pay_rate_sunday_3 + $super_sunday_3) * $this->workcover3),2);

        $loadedcost_t1_3 = number_format(($pay_rate_t1_3 + $super_t1_3 + $payrollTax_t1_3 + $mhws_t1_3 + $workcover_t1_3),2);
        $loadedcost_t15_3 = number_format(($pay_rate_t15_3 + $super_t15_3 + $payrollTax_t15_3 + $mhws_t15_3 + $workcover_t15_3),2);
        $loadedcost_t2_3 = number_format(($pay_rate_t2_3 + $super_t2_3 + $payrollTax_t2_3 + $mhws_t2_3 + $workcover_t2_3),2);
        $loadedcost_holiday_3 = number_format(($pay_rate_holiday_3 + $super_holiday_3 + $payrollTax_holiday_3 + $mhws_holiday_3 + $workcover_holiday_3),2);
        $loadedcost_early_3 = number_format(($pay_rate_early_3 + $super_early_3 + $payrollTax_early_3 + $mhws_early_3 + $workcover_early_3),2);
        $loadedcost_afternoon_3 = number_format(($pay_rate_afternoon_3 + $super_afternoon_3 + $payrollTax_afternoon_3 + $mhws_afternoon_3 + $workcover_afternoon_3),2);
        $loadedcost_night_3 = number_format(($pay_rate_night_3 + $super_night_3 + $payrollTax_night_3 + $mhws_night_3 + $workcover_night_3),2);
        $loadedcost_saturday_3 = number_format(($pay_rate_saturday_3 + $super_saturday_3 + $payrollTax_saturday_3 + $mhws_saturday_3 + $workcover_saturday_3),2);
        $loadedcost_sunday_3 = number_format(($pay_rate_sunday_3 + $super_sunday_3 + $payrollTax_sunday_3 + $mhws_sunday_3 + $workcover_sunday_3),2);

        $placement_fee_3 = number_format($this->margin3,2);

        $chargeRate_t1_3 = number_format(($placement_fee_3 + $loadedcost_t1_3),2);
        $chargeRate_t15_3 = number_format(($placement_fee_3 + $loadedcost_t15_3),2);
        $chargeRate_t2_3 = number_format(($placement_fee_3 + $loadedcost_t2_3),2);
        $chargeRate_holiday_3 = number_format(($placement_fee_3 + $loadedcost_holiday_3),2);
        $chargeRate_early_3 = number_format(($placement_fee_3 + $loadedcost_early_3),2);
        $chargeRate_afternoon_3 = number_format(($placement_fee_3 + $loadedcost_afternoon_3),2);
        $chargeRate_night_3 = number_format(($placement_fee_3 + $loadedcost_night_3),2);
        $chargeRate_saturday_3 = number_format(($placement_fee_3 + $loadedcost_saturday_3),2);
        $chargeRate_sunday_3 = number_format(($placement_fee_3 + $loadedcost_sunday_3),2);

        $gst_t1_3 = number_format(($chargeRate_t1_3 * 0.1),2);
        $gst_t15_3 = number_format(($chargeRate_t15_3 * 0.1),2);
        $gst_t2_3 = number_format(($chargeRate_t2_3 * 0.1),2);
        $gst_holiday_3 = number_format(($chargeRate_holiday_3 * 0.1),2);
        $gst_early_3 = number_format(($chargeRate_early_3 * 0.1),2);
        $gst_afternoon_3 = number_format(($chargeRate_afternoon_3 * 0.1),2);
        $gst_night_3 = number_format(($chargeRate_night_3 * 0.1),2);
        $gst_saturday_3 = number_format(($chargeRate_saturday_3 * 0.1),2);
        $gst_sunday_3 = number_format(($chargeRate_sunday_3 * 0.1),2);

        $chg_gst_t1_3 = number_format(($chargeRate_t1_3),2);
        $chg_gst_t15_3 = number_format(($chargeRate_t15_3),2);
        $chg_gst_t2_3 = number_format(($chargeRate_t2_3),2);
        $chg_gst_holiday_3 = number_format(($chargeRate_holiday_3),2);
        $chg_gst_early_3 = number_format(($chargeRate_early_3),2);
        $chg_gst_afternoon_3 = number_format(($chargeRate_afternoon_3),2);
        $chg_gst_night_3 = number_format(($chargeRate_night_3),2);
        $chg_gst_saturday_3 = number_format(($chargeRate_saturday_3),2);
        $chg_gst_sunday_3 = number_format(($chargeRate_sunday_3),2);


        /* --------------------------    hire rate 4    ---------------------------- */

        $hourly_rate4 = $this->hourly_rate4;
        $fulltime_t15_4 = number_format(($hourly_rate4 * self::T15_OVERTIME),2);
        $fulltime_t2_4 = number_format(($hourly_rate4 * self::T2_OVERTIME),2);
        $fulltime_holiday_4 = number_format(($hourly_rate4 * self::T25_OVERTIME),2);
        $fulltime_early_4 = number_format(($hourly_rate4 * (1 + self::EARLY_MORNING_LOADING)),2);
        $fulltime_afternoon_4 = number_format(($hourly_rate4 * (1 + self::AFTERNOON_LOADING)),2);
        $fulltime_night_4 = number_format(($hourly_rate4 * (1 + self::NIGHT_LOADING)),2);
        $fulltime_saturday_4 = number_format(($hourly_rate4 * (self::T15_OVERTIME)),2);
        $fulltime_sunday_4 = number_format(($hourly_rate4 * (self::T2_OVERTIME)),2);
        if($this->award == self::STORAGE_AWD){
            $casual_t1_4 = number_format(($hourly_rate4 * self::CASUAL_LOADING), 2);
            $casual_t15_4 = $casual_t1_4;
            $casual_t2_4 = $casual_t1_4;
            $casual_holiday_4 = $casual_t1_4;
            $casual_early_4 = $casual_t1_4;
            $casual_afternoon_4 = $casual_t1_4;
            $casual_night_4 = $casual_t1_4;
            $casual_saturday_4 = $casual_t1_4;
            $casual_sunday_4 = $casual_t1_4;

            $pay_rate_t1_4 = number_format(($hourly_rate4 + $casual_t1_4), 2);
            $pay_rate_t15_4 = number_format(($fulltime_t15_4 + $casual_t15_4), 2);
            $pay_rate_t2_4 = number_format(($fulltime_t2_4 + $casual_t2_4), 2);
            $pay_rate_holiday_4 = number_format(($fulltime_holiday_4 + $casual_holiday_4), 2);
            $pay_rate_early_4 = number_format(($fulltime_early_4 + $casual_early_4), 2);
            $pay_rate_afternoon_4 = number_format(($fulltime_afternoon_4 + $casual_afternoon_4), 2);
            $pay_rate_night_4 = number_format(($fulltime_night_4 + $casual_night_4), 2);
            $pay_rate_saturday_4 = number_format(($fulltime_saturday_4 + $casual_saturday_4), 2);
            $pay_rate_sunday_4 = number_format(($fulltime_sunday_4 + $casual_sunday_4), 2);
        }elseif($this->award == self::FOOD_AWD){
            $casual_t1_4 = number_format(($hourly_rate4 * self::CASUAL_LOADING), 2);
            $casual_t15_4 = number_format((($hourly_rate4 * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_t2_4 = number_format((($hourly_rate4 * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);
            $casual_holiday_4 = number_format((($hourly_rate4 * self::CASUAL_LOADING) * self::T25_OVERTIME), 2);
            $casual_early_4 = number_format(($casual_t1_4 * (1 + self::EARLY_MORNING_LOADING) + 0.01),2);
            $casual_afternoon_4 = number_format((($hourly_rate4 * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1)), 2);
            $casual_night_4 = number_format((($hourly_rate4 * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1)), 2);
            $casual_saturday_4 = number_format((($hourly_rate4 * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_sunday_4 = number_format((($hourly_rate4 * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);

            $pay_rate_t1_4 = number_format(($hourly_rate4 + $casual_t1_4), 2);
            $pay_rate_t15_4 = number_format(($fulltime_t15_4 + $casual_t15_4), 2);
            $pay_rate_t2_4 = number_format(($fulltime_t2_4 + $casual_t2_4), 2);
            $pay_rate_holiday_4 = number_format(($fulltime_holiday_4 + $casual_holiday_4), 2);
            $pay_rate_early_4 = number_format(($fulltime_early_4 + $casual_early_4), 2);
            $pay_rate_afternoon_4 = number_format(($fulltime_afternoon_4 + $casual_afternoon_4), 2);
            $pay_rate_night_4 = number_format(($fulltime_night_4 + $casual_night_4), 2);
            $pay_rate_saturday_4 = number_format(($fulltime_saturday_4 + $casual_saturday_4), 2);
            $pay_rate_sunday_4 = number_format(($fulltime_sunday_4 + $casual_sunday_4), 2);
        }elseif($this->award == self::RETAIL_AWD){
            $fulltime_t2_4 = number_format(($hourly_rate4 * self::RTT2_OVERTIME),2);
            $fulltime_holiday_4 = number_format(($hourly_rate4 * self::RTT25_OVERTIME),2);
            $fulltime_afternoon_4 = number_format(($hourly_rate4 * (1 + self::RT_AFTERNOON_LOADING)),2);
            $fulltime_sunday_4 = number_format(($hourly_rate4 * (self::RTT2_OVERTIME)),2);

            $casual_t1_4 = number_format(($hourly_rate4 * self::CASUAL_LOADING), 2);
            $casual_t15_4 = $casual_t1_4;
            $casual_t2_4 = $casual_t1_4;
            $casual_holiday_4 = $casual_t1_4;
            $casual_early_4 = $casual_t1_4;
            $casual_afternoon_4 = $casual_t1_4;
            $casual_night_4 = $casual_t1_4;
            $casual_saturday_4 = $casual_t1_4;
            $casual_sunday_4 = $casual_t1_4;

            $pay_rate_t1_4 = number_format(($hourly_rate4 + $casual_t1_4), 2);
            $pay_rate_t15_4 = number_format(($fulltime_t15_4 + $casual_t15_4), 2);
            $pay_rate_t2_4 = number_format(($fulltime_t2_4 + $casual_t2_4), 2);
            $pay_rate_holiday_4 = number_format(($fulltime_holiday_4 + $casual_holiday_4), 2);
            $pay_rate_early_4 = number_format(($fulltime_early_4 + $casual_early_4), 2);
            $pay_rate_afternoon_4 = number_format(($fulltime_afternoon_4 + $casual_afternoon_4), 2);
            $pay_rate_night_4 = number_format(($fulltime_night_4 + $casual_night_4), 2);
            $pay_rate_saturday_4 = number_format(($fulltime_saturday_4 + $casual_saturday_4), 2);
            $pay_rate_sunday_4 = number_format(($fulltime_sunday_4 + $casual_sunday_4), 2);
        }else {
            $casual_t1_4 = number_format(($hourly_rate4 * self::CASUAL_LOADING), 2);
            $casual_t15_4 = number_format((($hourly_rate4 * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_t2_4 = number_format((($hourly_rate4 * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);
            $casual_holiday_4 = number_format((($hourly_rate4 * self::CASUAL_LOADING) * self::T25_OVERTIME), 2);
            $casual_afternoon_4 = number_format((($hourly_rate4 * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1)), 2);
            $casual_night_4 = number_format((($hourly_rate4 * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1)), 2);
            $casual_saturday_4 = number_format((($hourly_rate4 * self::CASUAL_LOADING) * self::T15_OVERTIME), 2);
            $casual_sunday_4 = number_format((($hourly_rate4 * self::CASUAL_LOADING) * self::T2_OVERTIME), 2);

            $pay_rate_t1_4 = number_format(($hourly_rate4 + ($hourly_rate4 * self::CASUAL_LOADING)), 2);
            $pay_rate_t15_4 = number_format((($hourly_rate4 * self::T15_OVERTIME) + (($hourly_rate4 * self::CASUAL_LOADING) * self::T15_OVERTIME)), 2);
            $pay_rate_t2_4 = number_format((($hourly_rate4 * self::T2_OVERTIME) + (($hourly_rate4 * self::CASUAL_LOADING) * self::T2_OVERTIME)), 2);
            $pay_rate_holiday_4 = number_format((($hourly_rate4 * self::T25_OVERTIME) + (($hourly_rate4 * self::CASUAL_LOADING) * self::T25_OVERTIME)), 2);
            $pay_rate_afternoon_4 = number_format((($hourly_rate4 * (1 + self::AFTERNOON_LOADING)) + (($hourly_rate4 * self::CASUAL_LOADING) * (self::AFTERNOON_LOADING + 1))), 2);
            $pay_rate_night_4 = number_format((($hourly_rate4 * (1 + self::NIGHT_LOADING)) + (($hourly_rate4 * self::CASUAL_LOADING) * (self::NIGHT_LOADING + 1))), 2);
            $pay_rate_saturday_4 = number_format((($hourly_rate4 * (self::T15_OVERTIME)) + (($hourly_rate4 * self::CASUAL_LOADING) * self::T15_OVERTIME)), 2);
            $pay_rate_sunday_4 = number_format((($hourly_rate4 * (self::T2_OVERTIME)) + (($hourly_rate4 * self::CASUAL_LOADING) * self::T2_OVERTIME)), 2);
        }
        $super_t1_4 = number_format((($pay_rate_t1_4) * $this->superannuation4),2);
        $super_t15_4 = number_format(0,2);
        $super_t2_4 = number_format(0,2);
        $super_holiday_4 = number_format((($pay_rate_holiday_4) * $this->superannuation4),2);
        $super_early_4 = number_format((($pay_rate_early_4) * $this->superannuation4),2);
        $super_afternoon_4 = number_format((($pay_rate_afternoon_4) * $this->superannuation4),2);
        $super_night_4 = number_format((($pay_rate_night_4) * $this->superannuation4),2);
        $super_saturday_4 = number_format((($pay_rate_saturday_4) * $this->superannuation4),2);
        $super_sunday_4 = number_format((($pay_rate_sunday_4) * $this->superannuation4),2);

        $payrollTax_t1_4 = number_format((($pay_rate_t1_4 + $super_t1_4) * $this->payroll_tax4),2);
        $payrollTax_t15_4 = number_format((($pay_rate_t15_4 + $super_t15_4) * $this->payroll_tax4),2);
        $payrollTax_t2_4 = number_format((($pay_rate_t2_4 + $super_t2_4) * $this->payroll_tax4),2);
        $payrollTax_holiday_4 = number_format((($pay_rate_holiday_4 + $super_holiday_4) * $this->payroll_tax4),2);
        $payrollTax_early_4 = number_format((($pay_rate_early_4 + $super_early_4) * $this->payroll_tax4),2);
        $payrollTax_afternoon_4 = number_format((($pay_rate_afternoon_4 + $super_afternoon_4) * $this->payroll_tax4),2);
        $payrollTax_night_4 = number_format((($pay_rate_night_4 + $super_night_4) * $this->payroll_tax4),2);
        $payrollTax_saturday_4 = number_format((($pay_rate_saturday_4 + $super_saturday_4) * $this->payroll_tax4),2);
        $payrollTax_sunday_4 = number_format((($pay_rate_sunday_4 + $super_sunday_4) * $this->payroll_tax4),2);

        $mhws_t1_4 = number_format((($pay_rate_t1_4 + $super_t1_4) * $this->mhws4),2);
        $mhws_t15_4 = number_format((($pay_rate_t15_4 + $super_t15_4) * $this->mhws4),2);
        $mhws_t2_4 = number_format((($pay_rate_t2_4 + $super_t2_4) * $this->mhws4),2);
        $mhws_holiday_4 = number_format((($pay_rate_holiday_4 + $super_holiday_4) * $this->mhws4),2);
        $mhws_early_4 = number_format((($pay_rate_early_4 + $super_early_4) * $this->mhws4),2);
        $mhws_afternoon_4 = number_format((($pay_rate_afternoon_4 + $super_afternoon_4) * $this->mhws4),2);
        $mhws_night_4 = number_format((($pay_rate_night_4 + $super_night_4) * $this->mhws4),2);
        $mhws_saturday_4 = number_format((($pay_rate_saturday_4 + $super_saturday_4) * $this->mhws4),2);
        $mhws_sunday_4 = number_format((($pay_rate_sunday_4 + $super_sunday_4) * $this->mhws4),2);

        $workcover_t1_4 = number_format((($pay_rate_t1_4 + $super_t1_4) * $this->workcover4),2);
        $workcover_t15_4 = number_format((($pay_rate_t15_4 + $super_t15_4) * $this->workcover4),2);
        $workcover_t2_4 = number_format((($pay_rate_t2_4 + $super_t2_4) * $this->workcover4),2);
        $workcover_holiday_4 = number_format((($pay_rate_holiday_4 + $super_holiday_4) * $this->workcover4),2);
        $workcover_early_4 = number_format((($pay_rate_early_4 + $super_early_4) * $this->workcover4),2);
        $workcover_afternoon_4 = number_format((($pay_rate_afternoon_4 + $super_afternoon_4) * $this->workcover4),2);
        $workcover_night_4 = number_format((($pay_rate_night_4 + $super_night_4) * $this->workcover4),2);
        $workcover_saturday_4 = number_format((($pay_rate_saturday_4 + $super_saturday_4) * $this->workcover4),2);
        $workcover_sunday_4 = number_format((($pay_rate_sunday_4 + $super_sunday_4) * $this->workcover4),2);

        $loadedcost_t1_4 = number_format(($pay_rate_t1_4 + $super_t1_4 + $payrollTax_t1_4 + $mhws_t1_4 + $workcover_t1_4),2);
        $loadedcost_t15_4 = number_format(($pay_rate_t15_4 + $super_t15_4 + $payrollTax_t15_4 + $mhws_t15_4 + $workcover_t15_4),2);
        $loadedcost_t2_4 = number_format(($pay_rate_t2_4 + $super_t2_4 + $payrollTax_t2_4 + $mhws_t2_4 + $workcover_t2_4),2);
        $loadedcost_holiday_4 = number_format(($pay_rate_holiday_4 + $super_holiday_4 + $payrollTax_holiday_4 + $mhws_holiday_4 + $workcover_holiday_4),2);
        $loadedcost_early_4 = number_format(($pay_rate_early_4 + $super_early_4 + $payrollTax_early_4 + $mhws_early_4 + $workcover_early_4),2);
        $loadedcost_afternoon_4 = number_format(($pay_rate_afternoon_4 + $super_afternoon_4 + $payrollTax_afternoon_4 + $mhws_afternoon_4 + $workcover_afternoon_4),2);
        $loadedcost_night_4 = number_format(($pay_rate_night_4 + $super_night_4 + $payrollTax_night_4 + $mhws_night_4 + $workcover_night_4),2);
        $loadedcost_saturday_4 = number_format(($pay_rate_saturday_4 + $super_saturday_4 + $payrollTax_saturday_4 + $mhws_saturday_4 + $workcover_saturday_4),2);
        $loadedcost_sunday_4 = number_format(($pay_rate_sunday_4 + $super_sunday_4 + $payrollTax_sunday_4 + $mhws_sunday_4 + $workcover_sunday_4),2);

        $placement_fee_4 = number_format($this->margin4,2);

        $chargeRate_t1_4 = number_format(($placement_fee_4 + $loadedcost_t1_4),2);
        $chargeRate_t15_4 = number_format(($placement_fee_4 + $loadedcost_t15_4),2);
        $chargeRate_t2_4 = number_format(($placement_fee_4 + $loadedcost_t2_4),2);
        $chargeRate_holiday_4 = number_format(($placement_fee_4 + $loadedcost_holiday_4),2);
        $chargeRate_early_4 = number_format(($placement_fee_4 + $loadedcost_early_4),2);
        $chargeRate_afternoon_4 = number_format(($placement_fee_4 + $loadedcost_afternoon_4),2);
        $chargeRate_night_4 = number_format(($placement_fee_4 + $loadedcost_night_4),2);
        $chargeRate_saturday_4 = number_format(($placement_fee_4 + $loadedcost_saturday_4),2);
        $chargeRate_sunday_4 = number_format(($placement_fee_4 + $loadedcost_sunday_4),2);

        $gst_t1_4 = number_format(($chargeRate_t1_4 * 0.1),2);
        $gst_t15_4 = number_format(($chargeRate_t15_4 * 0.1),2);
        $gst_t2_4 = number_format(($chargeRate_t2_4 * 0.1),2);
        $gst_holiday_4 = number_format(($chargeRate_holiday_4 * 0.1),2);
        $gst_early_4 = number_format(($chargeRate_early_4 * 0.1),2);
        $gst_afternoon_4 = number_format(($chargeRate_afternoon_4 * 0.1),2);
        $gst_night_4 = number_format(($chargeRate_night_4 * 0.1),2);
        $gst_saturday_4 = number_format(($chargeRate_saturday_4 * 0.1),2);
        $gst_sunday_4 = number_format(($chargeRate_sunday_4 * 0.1),2);

        $chg_gst_t1_4 = number_format(($chargeRate_t1_4),2);
        $chg_gst_t15_4 = number_format(($chargeRate_t15_4),2);
        $chg_gst_t2_4 = number_format(($chargeRate_t2_4),2);
        $chg_gst_holiday_4 = number_format(($chargeRate_holiday_4),2);
        $chg_gst_early_4 = number_format(($chargeRate_early_4),2);
        $chg_gst_afternoon_4 = number_format(($chargeRate_afternoon_4),2);
        $chg_gst_night_4 = number_format(($chargeRate_night_4),2);
        $chg_gst_saturday_4 = number_format(($chargeRate_saturday_4),2);
        $chg_gst_sunday_4 = number_format(($chargeRate_sunday_4),2);


        $html = $html.'<table cellspacing="0" style="background-color: #FFFFFF; border: none; border-color: #FFFFFF">
            <thead style="border: none; background-color: #FFFFFF; background: #FFFFFF">
              <tr style="border: none; background-color: #FFFFFF; background: #FFFFFF">
                <th style="border: none; background-color: #FFFFFF; background: #FFFFFF"></th>
                <th style="border: none; background-color: #FFFFFF; background: #FFFFFF"></th>
                <th style="border: none; background-color: #FFFFFF; background: #FFFFFF"></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="text-align: right; width: 35%"><span style="font-size: 12pt; color: #0d0f12; font-weight: bold; border: 1px solid red">Rates presented to:</span>
                    <br>
                        <span style="font-size: 10pt; color: #0AA699; font-weight: bold;">'.$this->client.'</span>
                    <br>
                    <br>
                        <span style="font-size: 12pt; color: #0d0f12; font-weight: bold">Award/EBA:</span>
                        <br>
                        <span style="font-size: 10pt; color: #0AA699; font-weight: bold">'.$this->award.'</span></td>
                <td></td>
                <td><img src="img/logo.png" width="220" height="50" border="0" alt=""/>
                    <br>
                    <img src="'.$this->client_logo.'"  alt="">
                </td>
              </tr>
            </tbody>
          </table>';
        $html = $html.'<br>';
        $html = $html.'';
        $html = $html.'<br></div>';

        $html = $html.'<br>';
        $html = $html.'<div style="font-size: 22pt; color: red; font-weight: bold">Labour <br>';
        $html = $html.'Hire Rates</div>';
        $html = $html.'<br>';
        $html = $html.'<div style="font-size: 14pt; font-weight: bold; font-style: italic"><b>Classification: '.$this->position.'</b></div>';
        $html = $html.'<br>';

        if($this->breakdown == 'CHARGE RATE'){
            $html = $html . '<div align="center"><table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <thead>
              <tr>
                <th style="width: 17%">Description</th>
                <th>T1.0 Ordinary</th>
                <th>T1.5 Overtime</th>
                <th>T2.0 Overtime</th>
                <th>Public Holiday</th>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<th>Early Morning</th>';
            }
            $html = $html.'<th>Afternoon</th>
                <th>Night</th>
                <th>Saturday</th>
                <th>Sunday</th>
              </tr>
            </thead>
            <tbody>';
            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Charge Rate</td>
                <td>' . $chg_gst_t1 . '</td>
                <td>' . $chg_gst_t15 . '</td>
                <td>' . $chg_gst_t2 . '</td>
                <td>' . $chg_gst_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>' . $chg_gst_early . '</td>';
            }
            $html = $html.'<td>' . $chg_gst_afternoon . '</td>
                <td>' . $chg_gst_night . '</td>
                <td>' . $chg_gst_saturday . '</td>
                <td>' . $chg_gst_sunday . '</td></tr>';
            $html = $html . '</tbody>
          </table></div><br>';
        }else {
            $html = $html . '<div align="center"><table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <thead>
              <tr>
                <th style="width: 17%">Description</th>
                <th>T1.0 Ordinary</th>
                <th>T1.5 Overtime</th>
                <th>T2.0 Overtime</th>
                <th>Public Holiday</th>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<th>Early Morning</th>';
            }
            $html = $html.'<th>Afternoon</th>
                <th>Night</th>
                <th>Saturday</th>
                <th>Sunday</th>
              </tr>
            </thead>
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Fulltime Rate</td>
                <td>' . $hourly_rate . '</td>
                <td>' . $fulltime_t15 . '</td>
                <td>' . $fulltime_t2 . '</td>
                <td>' . $fulltime_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>' . $fulltime_early . '</td>';
            }
            $html = $html.'<td>' . $fulltime_afternoon . '</td>
                <td>' . $fulltime_night . '</td>
                <td>' . $fulltime_saturday . '</td>
                <td>' . $fulltime_sunday . '</td></tr>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Casual Loading</td>
                <td>' . $casual_t1 . '</td>
                <td>' . $casual_t15 . '</td>
                <td>' . $casual_t2 . '</td>
                <td>' . $casual_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>' . $casual_early . '</td>';
            }
            $html = $html.'<td>' . $casual_afternoon . '</td>
                <td>' . $casual_night . '</td>
                <td>' . $casual_saturday . '</td>
                <td>' . $casual_sunday . '</td></tr>';

            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Pay Rate</td>
                <td>' . $pay_rate_t1 . '</td>
                <td>' . $pay_rate_t15 . '</td>
                <td>' . $pay_rate_t2 . '</td>
                <td>' . $pay_rate_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>' . $pay_rate_early . '</td>';
            }
            $html =$html.'<td>' . $pay_rate_afternoon . '</td>
                <td>' . $pay_rate_night . '</td>
                <td>' . $pay_rate_saturday . '</td>
                <td>' . $pay_rate_sunday . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Superannuation</td>
                <td>' . $super_t1 . '</td>
                <td>' . $super_t15 . '</td>
                <td>' . $super_t2 . '</td>
                <td>' . $super_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>' . $super_early . '</td>';
            }
            $html = $html.'<td>' . $super_afternoon . '</td>
                <td>' . $super_night . '</td>
                <td>' . $super_saturday . '</td>
                <td>' . $super_sunday . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Payroll Tax</td>
                <td>' . $payrollTax_t1 . '</td>
                <td>' . $payrollTax_t15 . '</td>
                <td>' . $payrollTax_t2 . '</td>
                <td>' . $payrollTax_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>' . $payrollTax_early . '</td>';
            }
            $html = $html.'<td>' . $payrollTax_afternoon . '</td>
                <td>' . $payrollTax_night . '</td>
                <td>' . $payrollTax_saturday . '</td>
                <td>' . $payrollTax_sunday . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">MHWS</td>
                <td>' . $mhws_t1 . '</td>
                <td>' . $mhws_t15 . '</td>
                <td>' . $mhws_t2 . '</td>
                <td>' . $mhws_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>' . $mhws_early . '</td>';
            }
            $html = $html.'<td>' . $mhws_afternoon . '</td>
                <td>' . $mhws_night . '</td>
                <td>' . $mhws_saturday . '</td>
                <td>' . $mhws_sunday . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">WorkCover</td>
                <td>' . $workcover_t1 . '</td>
                <td>' . $workcover_t15 . '</td>
                <td>' . $workcover_t2 . '</td>
                <td>' . $workcover_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$workcover_early.'</td>';
            }
            $html = $html.'<td>' . $workcover_afternoon . '</td>
                <td>' . $workcover_night . '</td>
                <td>' . $workcover_saturday . '</td>
                <td>' . $workcover_sunday . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Loaded Cost</td>
                <td>' . $loadedcost_t1 . '</td>
                <td>' . $loadedcost_t15 . '</td>
                <td>' . $loadedcost_t2 . '</td>
                <td>' . $loadedcost_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$loadedcost_early.'</td>';
            }
            $html = $html.'<td>' . $loadedcost_afternoon . '</td>
                <td>' . $loadedcost_night . '</td>
                <td>' . $loadedcost_saturday . '</td>
                <td>' . $loadedcost_sunday . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Placement Fee</td>
                <td>' . $placement_fee . '</td>
                <td>' . $placement_fee . '</td>
                <td>' . $placement_fee . '</td>
                <td>' . $placement_fee . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$placement_fee.'</td>';
            }
            $html = $html.'<td>' . $placement_fee . '</td>
                <td>' . $placement_fee . '</td>
                <td>' . $placement_fee . '</td>
                <td>' . $placement_fee . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Charge Rate</td>
                <td>' . $chargeRate_t1 . '</td>
                <td>' . $chargeRate_t15 . '</td>
                <td>' . $chargeRate_t2 . '</td>
                <td>' . $chargeRate_holiday . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$chargeRate_early.'</td>';
            }
            $html = $html.'<td>' . $chargeRate_afternoon . '</td>
                <td>' . $chargeRate_night . '</td>
                <td>' . $chargeRate_saturday . '</td>
                <td>' . $chargeRate_sunday . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br>';

            $html = $html.'</div>';
        }

        $html = $html.'<br><div style="font-size: 14pt; font-weight: bold; font-style: italic"><b>Classification: '.$this->position2.'</b></div>';
        $html = $html.'<br>';
        /*  --------------------------   hire rate 2 html   ----------------------- */
        if($this->breakdown == 'CHARGE RATE'){
            $html = $html . '<div align="center"><table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <thead>
              <tr>
                <th style="width: 17%">Description</th>
                <th>T1.0 Ordinary</th>
                <th>T1.5 Overtime</th>
                <th>T2.0 Overtime</th>
                <th>Public Holiday</th>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<th>Early Morning</th>';
            }
            $html = $html.'<th>Afternoon</th>
                <th>Night</th>
                <th>Saturday</th>
                <th>Sunday</th>
              </tr>
            </thead>
            <tbody>';
            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Charge Rate</td>
                <td>' . $chg_gst_t1_2 . '</td>
                <td>' . $chg_gst_t15_2 . '</td>
                <td>' . $chg_gst_t2_2 . '</td>
                <td>' . $chg_gst_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$chg_gst_early_2.'</td>';
            }
            $html = $html.'<td>' . $chg_gst_afternoon_2 . '</td>
                <td>' . $chg_gst_night_2 . '</td>
                <td>' . $chg_gst_saturday_2 . '</td>
                <td>' . $chg_gst_sunday_2 . '</td></tr>';
            $html = $html . '</tbody>
          </table></div><br>';
        }else {
            $html = $html . '<div align="center"><table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <thead>
              <tr>
                <th style="width: 17%">Description</th>
                <th>T1.0 Ordinary</th>
                <th>T1.5 Overtime</th>
                <th>T2.0 Overtime</th>
                <th>Public Holiday</th>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<th>Early Morning</th>';
            }
            $html = $html.'<th>Afternoon</th>
                <th>Night</th>
                <th>Saturday</th>
                <th>Sunday</th>
              </tr>
            </thead>
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Fulltime Rate</td>
                <td>' . $hourly_rate2 . '</td>
                <td>' . $fulltime_t15_2 . '</td>
                <td>' . $fulltime_t2_2 . '</td>
                <td>' . $fulltime_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$fulltime_early_2.'</td>';
            }
            $html = $html.'<td>' . $fulltime_afternoon_2 . '</td>
                <td>' . $fulltime_night_2 . '</td>
                <td>' . $fulltime_saturday_2 . '</td>
                <td>' . $fulltime_sunday_2 . '</td></tr>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Casual Loading</td>
                <td>' . $casual_t1_2 . '</td>
                <td>' . $casual_t15_2 . '</td>
                <td>' . $casual_t2_2 . '</td>
                <td>' . $casual_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$casual_early_2.'</td>';
            }
            $html = $html.'<td>' . $casual_afternoon_2 . '</td>
                <td>' . $casual_night_2 . '</td>
                <td>' . $casual_saturday_2 . '</td>
                <td>' . $casual_sunday_2 . '</td></tr>';

            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Pay Rate</td>
                <td>' . $pay_rate_t1_2 . '</td>
                <td>' . $pay_rate_t15_2 . '</td>
                <td>' . $pay_rate_t2_2 . '</td>
                <td>' . $pay_rate_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$pay_rate_early_2.'</td>';
            }
            $html = $html.'<td>' . $pay_rate_afternoon_2 . '</td>
                <td>' . $pay_rate_night_2 . '</td>
                <td>' . $pay_rate_saturday_2 . '</td>
                <td>' . $pay_rate_sunday_2 . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Superannuation</td>
                <td>' . $super_t1_2 . '</td>
                <td>' . $super_t15_2 . '</td>
                <td>' . $super_t2_2 . '</td>
                <td>' . $super_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$super_early_2.'</td>';
            }
            $html = $html.'<td>' . $super_afternoon_2 . '</td>
                <td>' . $super_night_2 . '</td>
                <td>' . $super_saturday_2 . '</td>
                <td>' . $super_sunday_2 . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Payroll Tax</td>
                <td>' . $payrollTax_t1_2 . '</td>
                <td>' . $payrollTax_t15_2 . '</td>
                <td>' . $payrollTax_t2_2 . '</td>
                <td>' . $payrollTax_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$payrollTax_early_2.'</td>';
            }
            $html = $html.'<td>' . $payrollTax_afternoon_2 . '</td>
                <td>' . $payrollTax_night_2 . '</td>
                <td>' . $payrollTax_saturday_2 . '</td>
                <td>' . $payrollTax_sunday_2 . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">MHWS</td>
                <td>' . $mhws_t1_2 . '</td>
                <td>' . $mhws_t15_2 . '</td>
                <td>' . $mhws_t2_2 . '</td>
                <td>' . $mhws_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$mhws_early_2.'</td>';
            }
            $html = $html.'<td>' . $mhws_afternoon_2 . '</td>
                <td>' . $mhws_night_2 . '</td>
                <td>' . $mhws_saturday_2 . '</td>
                <td>' . $mhws_sunday_2 . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">WorkCover</td>
                <td>' . $workcover_t1_2 . '</td>
                <td>' . $workcover_t15_2 . '</td>
                <td>' . $workcover_t2_2 . '</td>
                <td>' . $workcover_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$workcover_early_2.'</td>';
            }
            $html = $html.'<td>' . $workcover_afternoon_2 . '</td>
                <td>' . $workcover_night_2 . '</td>
                <td>' . $workcover_saturday_2 . '</td>
                <td>' . $workcover_sunday_2 . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Loaded Cost</td>
                <td>' . $loadedcost_t1_2 . '</td>
                <td>' . $loadedcost_t15_2 . '</td>
                <td>' . $loadedcost_t2_2 . '</td>
                <td>' . $loadedcost_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$loadedcost_early_2.'</td>';
            }
            $html = $html.'<td>' . $loadedcost_afternoon_2 . '</td>
                <td>' . $loadedcost_night_2 . '</td>
                <td>' . $loadedcost_saturday_2 . '</td>
                <td>' . $loadedcost_sunday_2 . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Placement Fee</td>
                <td>' . $placement_fee_2 . '</td>
                <td>' . $placement_fee_2 . '</td>
                <td>' . $placement_fee_2 . '</td>
                <td>' . $placement_fee_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$placement_fee_2.'</td>';
            }
            $html = $html.'<td>' . $placement_fee_2 . '</td>
                <td>' . $placement_fee_2 . '</td>
                <td>' . $placement_fee_2 . '</td>
                <td>' . $placement_fee_2 . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Charge Rate</td>
                <td>' . $chargeRate_t1_2 . '</td>
                <td>' . $chargeRate_t15_2 . '</td>
                <td>' . $chargeRate_t2_2 . '</td>
                <td>' . $chargeRate_holiday_2 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$chargeRate_early_2.'</td>';
            }
            $html = $html.'<td>' . $chargeRate_afternoon_2 . '</td>
                <td>' . $chargeRate_night_2 . '</td>
                <td>' . $chargeRate_saturday_2 . '</td>
                <td>' . $chargeRate_sunday_2 . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br>';
            $html = $html .'</div>';
        }
        $html = $html.'<br pagebreak="true"><div style="font-size: 14pt; font-weight: bold; font-style: italic"><b>Classification: '.$this->position3.'</b></div>';
        $html = $html.'<br>';
        /*  --------------------------   hire rate 3 html   ----------------------- */
        if($this->breakdown == 'CHARGE RATE'){
            $html = $html . '<div align="center"><table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <thead>
              <tr>
                <th style="width: 17%">Description</th>
                <th>T1.0 Ordinary</th>
                <th>T1.5 Overtime</th>
                <th>T2.0 Overtime</th>
                <th>Public Holiday</th>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<th>Early Morning</th>';
            }
            $html = $html.'<th>Afternoon</th>
                <th>Night</th>
                <th>Saturday</th>
                <th>Sunday</th>
              </tr>
            </thead>
            <tbody>';
            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Charge Rate</td>
                <td>' . $chg_gst_t1_3 . '</td>
                <td>' . $chg_gst_t15_3 . '</td>
                <td>' . $chg_gst_t2_3 . '</td>
                <td>' . $chg_gst_holiday_3 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$chg_gst_early_3.'</td>';
            }
            $html = $html.'<td>' . $chg_gst_afternoon_3 . '</td>
                <td>' . $chg_gst_night_3 . '</td>
                <td>' . $chg_gst_saturday_3 . '</td>
                <td>' . $chg_gst_sunday_3 . '</td></tr>';
            $html = $html . '</tbody>
          </table></div><br>';
        }else {
            $html = $html . '<div align="center"><table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <thead>
              <tr>
                <th style="width: 17%">Description</th>
                <th>T1.0 Ordinary</th>
                <th>T1.5 Overtime</th>
                <th>T2.0 Overtime</th>
                <th>Public Holiday</th>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<th>Early Morning</th>';
            }
            $html = $html.'<th>Afternoon</th>
                <th>Night</th>
                <th>Saturday</th>
                <th>Sunday</th>
              </tr>
            </thead>
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Fulltime Rate</td>
                <td>' . $hourly_rate3 . '</td>
                <td>' . $fulltime_t15_3 . '</td>
                <td>' . $fulltime_t2_3 . '</td>
                <td>' . $fulltime_holiday_3 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$fulltime_early_3.'</td>';
            }
            $html = $html.'<td>' . $fulltime_afternoon_3 . '</td>
                <td>' . $fulltime_night_3 . '</td>
                <td>' . $fulltime_saturday_3 . '</td>
                <td>' . $fulltime_sunday_3 . '</td></tr>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Casual Loading</td>
                <td>' . $casual_t1_3 . '</td>
                <td>' . $casual_t15_3 . '</td>
                <td>' . $casual_t2_3 . '</td>
                <td>' . $casual_holiday_3 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$casual_early_3.'</td>';
            }
            $html = $html.'<td>' . $casual_afternoon_3 . '</td>
                <td>' . $casual_night_3 . '</td>
                <td>' . $casual_saturday_3 . '</td>
                <td>' . $casual_sunday_3 . '</td></tr>';

            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Pay Rate</td>
                <td>' . $pay_rate_t1_3 . '</td>
                <td>' . $pay_rate_t15_3 . '</td>
                <td>' . $pay_rate_t2_3 . '</td>
                <td>' . $pay_rate_holiday_3 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$pay_rate_early_3.'</td>';
            }
            $html = $html.'<td>' . $pay_rate_afternoon_3 . '</td>
                <td>' . $pay_rate_night_3 . '</td>
                <td>' . $pay_rate_saturday_3 . '</td>
                <td>' . $pay_rate_sunday_3 . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Superannuation</td>
                <td>' . $super_t1_3 . '</td>
                <td>' . $super_t15_3 . '</td>
                <td>' . $super_t2_3 . '</td>
                <td>' . $super_holiday_3 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$super_early_3.'</td>';
            }
            $html = $html.'<td>' . $super_afternoon_3 . '</td>
                <td>' . $super_night_3 . '</td>
                <td>' . $super_saturday_3 . '</td>
                <td>' . $super_sunday_3 . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Payroll Tax</td>
                <td>' . $payrollTax_t1_3 . '</td>
                <td>' . $payrollTax_t15_3 . '</td>
                <td>' . $payrollTax_t2_3 . '</td>
                <td>' . $payrollTax_holiday_3 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$payrollTax_early_3.'</td>';
            }
            $html = $html.'<td>' . $payrollTax_afternoon_3 . '</td>
                <td>' . $payrollTax_night_3 . '</td>
                <td>' . $payrollTax_saturday_3 . '</td>
                <td>' . $payrollTax_sunday_3 . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">MHWS</td>
                <td>' . $mhws_t1_3 . '</td>
                <td>' . $mhws_t15_3 . '</td>
                <td>' . $mhws_t2_3 . '</td>
                <td>' . $mhws_holiday_3 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$mhws_early_3.'</td>';
            }
            $html = $html.'<td>' . $mhws_afternoon_3 . '</td>
                <td>' . $mhws_night_3 . '</td>
                <td>' . $mhws_saturday_3 . '</td>
                <td>' . $mhws_sunday_3 . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">WorkCover</td>
                <td>' . $workcover_t1_3 . '</td>
                <td>' . $workcover_t15_3 . '</td>
                <td>' . $workcover_t2_3 . '</td>
                <td>' . $workcover_holiday_3 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$workcover_early_3.'</td>';
            }
            $html = $html.'<td>' . $workcover_afternoon_3 . '</td>
                <td>' . $workcover_night_3 . '</td>
                <td>' . $workcover_saturday_3 . '</td>
                <td>' . $workcover_sunday_3 . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Loaded Cost</td>
                <td>' . $loadedcost_t1_3 . '</td>
                <td>' . $loadedcost_t15_3 . '</td>
                <td>' . $loadedcost_t2_3 . '</td>
                <td>' . $loadedcost_holiday_3 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$loadedcost_early_3.'</td>';
            }
            $html = $html.'<td>' . $loadedcost_afternoon_3 . '</td>
                <td>' . $loadedcost_night_3 . '</td>
                <td>' . $loadedcost_saturday_3 . '</td>
                <td>' . $loadedcost_sunday_3 . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Placement Fee</td>
                <td>' . $placement_fee_3 . '</td>
                <td>' . $placement_fee_3 . '</td>
                <td>' . $placement_fee_3 . '</td>
                <td>' . $placement_fee_3 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$placement_fee_3.'</td>';
            }
            $html = $html.'<td>' . $placement_fee_3 . '</td>
                <td>' . $placement_fee_3 . '</td>
                <td>' . $placement_fee_3 . '</td>
                <td>' . $placement_fee_3 . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Charge Rate</td>
                <td>' . $chargeRate_t1_3 . '</td>
                <td>' . $chargeRate_t15_3 . '</td>
                <td>' . $chargeRate_t2_3 . '</td>
                <td>' . $chargeRate_holiday_3 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$chargeRate_early_3.'</td>';
            }
            $html = $html.'<td>' . $chargeRate_afternoon_3 . '</td>
                <td>' . $chargeRate_night_3 . '</td>
                <td>' . $chargeRate_saturday_3 . '</td>
                <td>' . $chargeRate_sunday_3 . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br>';
            $html = $html.'</div>';
        }
        $html = $html.'<br><div style="font-size: 14pt; font-weight: bold; font-style: italic"><b>Classification: '.$this->position4.'</b></div>';
        $html = $html.'<br>';
        /*  --------------------------   hire rate 4 html   ----------------------- */
        if($this->breakdown == 'CHARGE RATE'){
            $html = $html . '<div align="center"><table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <thead>
              <tr>
                <th style="width: 17%">Description</th>
                <th>T1.0 Ordinary</th>
                <th>T1.5 Overtime</th>
                <th>T2.0 Overtime</th>
                <th>Public Holiday</th>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<th>Early Morning</th>';
            }
            $html = $html.'<th>Afternoon</th>
                <th>Night</th>
                <th>Saturday</th>
                <th>Sunday</th>
              </tr>
            </thead>
            <tbody>';
            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Charge Rate</td>
                <td>' . $chg_gst_t1_4 . '</td>
                <td>' . $chg_gst_t15_4 . '</td>
                <td>' . $chg_gst_t2_4 . '</td>
                <td>' . $chg_gst_holiday_4 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$chg_gst_early_4.'</td>';
            }
            $html = $html.'<td>' . $chg_gst_afternoon_4 . '</td>
                <td>' . $chg_gst_night_4 . '</td>
                <td>' . $chg_gst_saturday_4 . '</td>
                <td>' . $chg_gst_sunday_4 . '</td></tr>';
            $html = $html . '</tbody>
          </table></div><br>';
        }else {
            $html = $html . '<div align="center"><table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <thead>
              <tr>
                <th style="width: 17%">Description</th>
                <th>T1.0 Ordinary</th>
                <th>T1.5 Overtime</th>
                <th>T2.0 Overtime</th>
                <th>Public Holiday</th>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<th>Early Morning</th>';
            }
            $html = $html.'<th>Afternoon</th>
                <th>Night</th>
                <th>Saturday</th>
                <th>Sunday</th>
              </tr>
            </thead>
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Fulltime Rate</td>
                <td>' . $hourly_rate4 . '</td>
                <td>' . $fulltime_t15_4 . '</td>
                <td>' . $fulltime_t2_4 . '</td>
                <td>' . $fulltime_holiday_4 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$fulltime_early_4.'</td>';
            }
            $html = $html.'<td>' . $fulltime_afternoon_4 . '</td>
                <td>' . $fulltime_night_4 . '</td>
                <td>' . $fulltime_saturday_4 . '</td>
                <td>' . $fulltime_sunday_4 . '</td></tr>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Casual Loading</td>
                <td>' . $casual_t1_4 . '</td>
                <td>' . $casual_t15_4 . '</td>
                <td>' . $casual_t2_4 . '</td>
                <td>' . $casual_holiday_4 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$casual_early_4.'</td>';
            }
            $html = $html.'<td>' . $casual_afternoon_4 . '</td>
                <td>' . $casual_night_4 . '</td>
                <td>' . $casual_saturday_4 . '</td>
                <td>' . $casual_sunday_4 . '</td></tr>';

            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Pay Rate</td>
                <td>' . $pay_rate_t1_4 . '</td>
                <td>' . $pay_rate_t15_4 . '</td>
                <td>' . $pay_rate_t2_4 . '</td>
                <td>' . $pay_rate_holiday_4 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$pay_rate_early_4.'</td>';
            }
            $html = $html.'<td>' . $pay_rate_afternoon_4 . '</td>
                <td>' . $pay_rate_night_4 . '</td>
                <td>' . $pay_rate_saturday_4 . '</td>
                <td>' . $pay_rate_sunday_4 . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Superannuation</td>
                <td>' . $super_t1_4 . '</td>
                <td>' . $super_t15_4 . '</td>
                <td>' . $super_t2_4 . '</td>
                <td>' . $super_holiday_4 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$super_early_4.'</td>';
            }
            $html = $html.'<td>' . $super_afternoon_4 . '</td>
                <td>' . $super_night_4 . '</td>
                <td>' . $super_saturday_4 . '</td>
                <td>' . $super_sunday_4 . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Payroll Tax</td>
                <td>' . $payrollTax_t1_4 . '</td>
                <td>' . $payrollTax_t15_4 . '</td>
                <td>' . $payrollTax_t2_4 . '</td>
                <td>' . $payrollTax_holiday_4 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$payrollTax_early_4.'</td>';
            }
            $html = $html.'<td>' . $payrollTax_afternoon_4 . '</td>
                <td>' . $payrollTax_night_4 . '</td>
                <td>' . $payrollTax_saturday_4 . '</td>
                <td>' . $payrollTax_sunday_4 . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">MHWS</td>
                <td>' . $mhws_t1_4 . '</td>
                <td>' . $mhws_t15_4 . '</td>
                <td>' . $mhws_t2_4 . '</td>
                <td>' . $mhws_holiday_4 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$mhws_early_4.'</td>';
            }
            $html = $html.'<td>' . $mhws_afternoon_4 . '</td>
                <td>' . $mhws_night_4 . '</td>
                <td>' . $mhws_saturday_4 . '</td>
                <td>' . $mhws_sunday_4 . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">WorkCover</td>
                <td>' . $workcover_t1_4 . '</td>
                <td>' . $workcover_t15_4 . '</td>
                <td>' . $workcover_t2_4 . '</td>
                <td>' . $workcover_holiday_4 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$workcover_early_4.'</td>';
            }
            $html = $html.'<td>' . $workcover_afternoon_4 . '</td>
                <td>' . $workcover_night_4 . '</td>
                <td>' . $workcover_saturday_4 . '</td>
                <td>' . $workcover_sunday_4 . '</td></tr>';

            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Loaded Cost</td>
                <td>' . $loadedcost_t1_4 . '</td>
                <td>' . $loadedcost_t15_4 . '</td>
                <td>' . $loadedcost_t2_4 . '</td>
                <td>' . $loadedcost_holiday_4 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$loadedcost_early_4.'</td>';
            }
            $html = $html.'<td>' . $loadedcost_afternoon_4 . '</td>
                <td>' . $loadedcost_night_4 . '</td>
                <td>' . $loadedcost_saturday_4 . '</td>
                <td>' . $loadedcost_sunday_4 . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr>
                <td style="width: 17%; text-align: left">Placement Fee</td>
                <td>' . $placement_fee_4 . '</td>
                <td>' . $placement_fee_4 . '</td>
                <td>' . $placement_fee_4 . '</td>
                <td>' . $placement_fee_4 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$placement_fee_4.'</td>';
            }
            $html = $html.'<td>' . $placement_fee_4 . '</td>
                <td>' . $placement_fee_4 . '</td>
                <td>' . $placement_fee_4 . '</td>
                <td>' . $placement_fee_4 . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br><br>';
            $html = $html . '<table cellspacing="0" cellpadding="1" border="1" style="width: 95%;">
            <tbody>';
            $html = $html . '<tr style="background-color: #fce4d6">
                <td style="width: 17%; text-align: left">Charge Rate</td>
                <td>' . $chargeRate_t1_4 . '</td>
                <td>' . $chargeRate_t15_4 . '</td>
                <td>' . $chargeRate_t2_4 . '</td>
                <td>' . $chargeRate_holiday_4 . '</td>';
            if(($this->award == self::FOOD_AWD) || ($this->award == self::STORAGE_AWD) || ($this->award == self::RETAIL_AWD)){
                $html = $html.'<td>'.$chargeRate_early_4.'</td>';
            }
            $html = $html.'<td>' . $chargeRate_afternoon_4 . '</td>
                <td>' . $chargeRate_night_4 . '</td>
                <td>' . $chargeRate_saturday_4 . '</td>
                <td>' . $chargeRate_sunday_4 . '</td></tr>';
            $html = $html . '</tbody>
          </table>';
            $html = $html . '<br>';

            $html = $html.'</div>';
        }


        $html = $html.'<div style="text-align: right">Please note charge rates expressed are exclusive of GST.<br>Payment terms - '.$this->payment_terms.' Days from Invoice date</div><br>';
        $html = $html.'I agree to the rates and terms proposed by Chandler Services and understand the rules and regulations according to the '.$this->award;
        $html = $html.'<br><br>';


        $fileName = 'hireRate_'.time().'-'.mt_rand();
        $filePathPDF = './rates/'.$fileName.'.pdf';
        $pdf->writeHTML($html, true, false, false, false, '');

        $pdf->lastPage();
        $pdf->Output(__DIR__.'/rates/'.$fileName.'.pdf', 'F');
        echo $filePathPDF;
    }
}

if(!empty($hourly_rate) && empty($hourly_rate2) && empty($hourly_rate3) && empty($hourly_rate4)) {
    $calculateHireRate = new HireRate($client, $position,$position2,$position3,$position4, $award, $breakdown, $hourly_rate, $superannuation, $payroll_tax, $mhws, $workcover, $margin,$hourly_rate2,$superannuation2,$payroll_tax2,$mhws2,$workcover2,$margin2,$hourly_rate3,$superannuation3,$payroll_tax3,$mhws3,$workcover3,$margin3,$hourly_rate4,$superannuation4,$payroll_tax4,$mhws4,$workcover4,$margin4,$payment_terms, $client_logo);
    $calculateHireRate->calculateHireRate();
}else if(!empty($hourly_rate) && !empty($hourly_rate2) && empty($hourly_rate3) && empty($hourly_rate4)){
    $calculateHireRate = new HireRate($client, $position,$position2,$position3,$position4, $award, $breakdown, $hourly_rate, $superannuation, $payroll_tax, $mhws, $workcover, $margin,$hourly_rate2,$superannuation2,$payroll_tax2,$mhws2,$workcover2,$margin2,$hourly_rate3,$superannuation3,$payroll_tax3,$mhws3,$workcover3,$margin3,$hourly_rate4,$superannuation4,$payroll_tax4,$mhws4,$workcover4,$margin4,$payment_terms, $client_logo);
    $calculateHireRate->calculateHireRateForTwoPositions();
}else if(!empty($hourly_rate) && !empty($hourly_rate2) && !empty($hourly_rate3) && empty($hourly_rate4)) {
    $calculateHireRate = new HireRate($client, $position,$position2,$position3,$position4, $award, $breakdown, $hourly_rate, $superannuation, $payroll_tax, $mhws, $workcover, $margin,$hourly_rate2,$superannuation2,$payroll_tax2,$mhws2,$workcover2,$margin2,$hourly_rate3,$superannuation3,$payroll_tax3,$mhws3,$workcover3,$margin3,$hourly_rate4,$superannuation4,$payroll_tax4,$mhws4,$workcover4,$margin4,$payment_terms, $client_logo);
    $calculateHireRate->calculateHireRateForThreePositions();
}else if(!empty($hourly_rate) && !empty($hourly_rate2) && !empty($hourly_rate3) && !empty($hourly_rate4)) {
    $calculateHireRate = new HireRate($client, $position,$position2,$position3,$position4, $award, $breakdown, $hourly_rate, $superannuation, $payroll_tax, $mhws, $workcover, $margin,$hourly_rate2,$superannuation2,$payroll_tax2,$mhws2,$workcover2,$margin2,$hourly_rate3,$superannuation3,$payroll_tax3,$mhws3,$workcover3,$margin3,$hourly_rate4,$superannuation4,$payroll_tax4,$mhws4,$workcover4,$margin4,$payment_terms, $client_logo);
    $calculateHireRate->calculateHireRateForFourPositions();
}