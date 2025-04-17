<?php
require_once("includes/axiom_conn.php");

$attr = array('Forklift Driver - Advanced',
'Administration / Clerical',
'FARM WORK',
'Airtool Operator',
'Ag Qualification',
'Assembler',
'Bakery Experience',
'Bookkeeper',
'Press Operator',
'Brisbane',
'Caulker',
'CNC',
'Cold Storage',
'Container Unloader',
'Delivery Driver (courier)',
'Customer Service',
'Data Entry',
'Despatch',
'Die Setter',
'Easturn Subursb NSW',
'CA (Chartered Accountant)',
'Responsible Serving of Alcohol Cert',
'Extrusion Operator',
'Farm Hand',
'Female',
'General Labourer',
'Maintenance Fitter',
'Fork - General',
'Forklift - Reach',
'Freight Forwarder',
'CAR',
'NO CAR',
'DRIVER\'S LICENSE',
'Grinder',
'Accounting',
'Administration',
'Engineering',
'Manufctring/ processing/industrial',
'Trades & Sevices',
'Transport & Logistics',
'Inducted Willow',
'Injection Moulding',
'Carpentry',
'Concreting',
'Cleaning',
'Cook',
'Elevated work platforms',
'Exporting logistics',
'Fabricating',
'Food & Beverage service',
'Food prep/process/handling exp',
'Freight handling',
'Food Handling Certificate',
'HACCP (food hygiene)',
'Heavy plant handling/driving',
'Inventory control',
'Labouring - Construction',
'Labouring - builders labourer',
'Labouring - maintenance',
'Caulker',
'Logistics',
'Machine maintenance',
'Manufacturing',
'Meat packing',
'Metal fabrication',
'Packaging',
'Plastering',
'Plumbing',
'Power Tools',
'Quality controller',
'Receptionist skills',
'Rigger',
'Scaffolding',
'Scheduling',
'Qualified Shopfitter',
'Shopfitting Experience',
'Steel fixing',
'Stores',
'Transport logistics',
'Upholstering',
'Waitering',
'Welder',
'General Labourer',
'Pallet repairer',
'Warehouse All-rounder',
'REMOVALIST DRIVER',
'Truck Jockey',
'Tractor Driver',
'Chemical User Permit',
'Confined Space',
'Crane Driver',
'Dogging',
'Elevated Platform',
'Excavator',
'First Aid Qualified',
'Forklift',
'Front End Loader',
'Heavy Combination Licence',
'Heavy Rigid Licence',
'Heavy Vehicle',
'Light Rigid',
'Rigging',
'Medium Rigid license',
'Electrical Technician',
'Order picker/ Stock picker (LO)',
'Driver\'s Licence- Full',
'WOY',
'Male',
'Machine Operator - Food',
'Machine Operator - General',
'Machine Operator - Metal',
'Blow Moulding',
'Machine Operator - Wood',
'Northern Suburbs NSW',
'Noy',
'Administrator',
'Baker',
'Bobcat Operator',
'Boiler maker',
'Cabinet Maker',
'Electrician',
'Factory Worker',
'Joiner',
'Painter',
'Process Operator/ Worker',
'Stonemason',
'Supervisor',
'Tradesperson/ Assistant',
'Warehouse supervisor',
'Pick/Packers',
'Pick/Packers-Advanced',
'Fruit Picker',
'Machine Operator Plastic',
'Powder Coating',
'Print Offsider/Printer',
'White Card',
'RF Scanning',
'South West Suburbs NSW',
'Soy',
'Truck Driver-Specialised',
'Store person',
'Suitable for Artwrap',
'Suitable for Minifab',
'Supervisor',
'Suitable for Schiavello',
'Suitable for Willow',
'Sydney',
'Voice Picking',
'Western Suburbs NSW',
'Willow',
'Welder - Non Trade',
'Quality Assurance',
'Profile/Plasma cutter operator');

$sql = $axmysqli->prepare("SELECT ATTRIBUTECODE, DESCRIPTION FROM tattribute_new") or die($axmysqli->error);
$sql->execute();
$sql->store_result();
$sql->bind_result($ATTRIBUTECODE,$DESCRIPTION) or die($axmysqli->error);
$insert = $axmysqli->prepare("INSERT INTO otherlicence(code,otherLicenceType) VALUES(?,?)") or die($axmysqli->error);
$chk = $axmysqli->prepare("SELECT otherLicenceType FROM otherlicence WHERE otherLicenceType = ?")or die ($axmysqli->error);
while($sql->fetch()){
	//echo $ATTRIBUTECODE.$DESCRIPTION;
	foreach ($attr as $des) {
		if($DESCRIPTION === $des){
			  $chk->bind_param("s",$DESCRIPTION) or die($axmysqli->error);
			  $chk->execute();
			  $chk->store_result();
			  $num_of_rows = $chk->num_rows;
			  if($num_of_rows>0){
			  
			  }else{
				  $chk->free_result();
				  
				  $insert->bind_param("ss",$ATTRIBUTECODE,$DESCRIPTION) or die($axmysqli->error);
				  $insert->execute()or die($axmysqli->error);
				  $insert->fetch();
				  $nrows = $insert->affected_rows;
				  /**/
			 	  echo $DESCRIPTION.'<br>';
			  }
		}
	}
}
$insert->close();
$sql->close();
?>