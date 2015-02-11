<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

include_once  'Nominations_Class.php';

switch($_SERVER['REQUEST_METHOD'])
{
	case "GET":
		if ((isset($_REQUEST['StartDate'])) && (!empty($_REQUEST['StartDate'])) && (isset($_REQUEST['EndDate'])) && (!empty($_REQUEST['EndDate'])))
		{
			$StartDate = $_REQUEST['StartDate'];
			$EndDate = $_REQUEST['EndDate'];
			header('Content-type: application/json');
			$Nominations = new Nominations();
			$NomCol = $Nominations->GetDateRange($StartDate,$EndDate);
			
			echo json_encode($NomCol);
		}
		else
		{
			header('Content-type: application/json');
			$Nominations = new Nominations();
			echo json_encode($Nominations->GetAll());
		}			
		
		break;
	case "POST":
		$Nominations = new Nominations();
		
		if (isset($_REQUEST["title"]) && (!empty($_REQUEST["title"])))
		{
		
		$Nominations->Title = $_REQUEST["title"];
		$Nominations->Author = $_REQUEST["author"];
		$Nominations->Description =$_REQUEST["description"];
		$Nominations->NominatedBy =$_REQUEST["nominatedby"];
		$Nominations->NominationDate =  json_decode($_REQUEST["nominatedDate"]);
		echo "_REQUEST";
		echo var_dump($_REQUEST);
		}
		else
		{
			$params = json_decode(file_get_contents('php://input'));
			echo "JSON";
			echo var_dump($params);
			$Nominations->Title = $params["title"];
			$Nominations->Author = $params["author"];
			$Nominations->Description =$params["description"];
			$Nominations->NominatedBy =$params["nominatedby"];
			$Nominations->NominationDate =json_decode($params["nominatedDate"]);			
		}
		try {
			$Nominations->SaveToDB();
			send_response(201,"Created",$Nominations);
		}
		catch(MongoException $m)
		{
			send_response(400 ,"BAD REQUEST",$m->toString());
		}
		catch (Exception $e)
		{
			send_response(500 ,"Internal Server Error",$e->toString());			
		}
		
}

function send_response($status,$status_message,$data)
{
	header("HTTP/1.1 $status $status_message");
	$response['status']=$status;
	$response['status_message']=$status_message;
	$response['data']=$data;
	$json_response= json_encode($response);
	echo $json_response;
	
}

?>