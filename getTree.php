<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

/*$refString = '%ref:%';
$sql = $mysqli->prepare("SELECT autoid,subject,mailfrom,mailto FROM resume WHERE subject LIKE ? ORDER BY date DESC LIMIT 0,10")or die($mysqli->error);
$sql->bind_param("s",$refString)or die($mysqli->error);*/
//$sql = $mysqli->prepare("SELECT id,name,text,link,parent_id FROM mail_view_items")or die($mysqli->error);
$sql = $mysqli->prepare("SELECT id,mailfrom,mailto,subject,reference,parent_id FROM resume WHERE reference IS NOT NULL ORDER BY reference")or die($mysqli->error);
$sql->execute();
$sql->store_result();
$sql->bind_result($id,$mailfrom,$mailto,$subject,$reference,$parent_id)or die($mysqli->error);
//$sql->bind_result($id,$name,$text,$link,$parent_id)or die($mysqli->error);
//$sql->bind_result($autoid,$subject,$mailfrom,$mailto)or die($mysqli->error);
while($sql->fetch()){
    /*$str = explode('ref:',$subject);
    $subStr = $str[1];
    $sub_data["id"] = $autoid;
    $sub_data["name"] = $subject;
    $sub_data["text"] = $subStr;*/

    /*if(empty($parent)){
        $parent = $subStr;
        $sub_data["parent_id"] = $parent;
        $data[] = $sub_data;
    }
    if($parent == $subStr) {
        $parent = $subStr;
        $sub_data["parent_id"] = $parent;
        $data[] = $sub_data;
    }
    if ($parent != $subStr){
        $parent = $subStr;
        $sub_data["parent_id"] = $parent;
        $data[] = $sub_data;
    }*/
    $row = array('id'=>$id,'text'=>$reference,'parent_id'=>$parent_id);
    $data[] = $row;
    /*$sub_data['id'] = $id;
    $sub_data['name'] = $mailfrom;
    $sub_data['text'] = $reference;
    $sub_data['parent_id'] = $parent_id;
    $data[] = $sub_data;*/
}
foreach($data as $key => &$value)
{
     $output[$value['id']] = &$value;
}
foreach($data as $key => &$value)
{
    if($value['parent_id'] && isset($output[$value['parent_id']]))
    {
        $output[$value['parent_id']]['nodes'][] = &$value;
    }
}
foreach($data as $key => &$value)
{
    if($value['parent_id'] && isset($output[$value['parent_id']]))
    {
        unset($data[$key]);
    }
}
/*$itemsByReference = array();
// Build array of item references:
foreach($data as $key => &$item) {
    $itemsByReference[$item['id']] = &$item;
}
// Set items as children of the relevant parent item.
foreach($data as $key => &$item)  {
    if($item['parent_id'] && isset($itemsByReference[$item['parent_id']])) {
        $itemsByReference [$item['parent_id']]['nodes'][] = &$item;
    }
}
// Remove items that were added to parents elsewhere:
foreach($data as $key => &$item) {
    if($item['parent_id'] && isset($itemsByReference[$item['parent_id']]))
        unset($data[$key]);
}*/
echo json_encode($data);