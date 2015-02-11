<?php
class Nominations
{	
	public $Title;
	public $Author;
	public $Description;
	public $NominatedBy;
	public $NominationDate;
	public $Id;
	
	public function SaveToDB()
	{
		$m = new MongoClient();
		$db = $m->BookClub;
		$Nominations = $db->Nominations;	
		return $Nominations->save(array("Title" => $this->Title, "Author" => $this->Author, "Description" => $this->Description,
			"NominatedBy" => $this->NominatedBy, "NominationDate" => new MongoDate(strtotime($this->NominationDate))));
	}
		
	public function GetFromDB($i)
	{		
		$m = new MongoClient();
		$db = $m->BookClub;
		$Nominations = $db->Nominations;
		$a = $Nominations->findOne(array("_id" => $i));	
		$this->Title = $a->Title;
		$this->Author = $a->Author;
		$this->Description = $a->Description;
		$this->NominatedBy = $a->NominatedBy;
		$this->NominationDate = date('Y-M-d h:i:s', $a->NominationDate->sec);
	}
	
	public function GetDateRange($Startdate, $EndDate)
	{		
		$MStart = new MongoDate(strtotime($Startdate));
		$MEnd = new MongoDate(strtotime($EndDate));
		$m = new MongoClient();
		$db = $m->BookClub;
		$Nominations = $db->Nominations;
		$NominationCol = $Nominations->find(array("NominationDate" => array('$gt' => $MStart, '$lte' => $MEnd)));
		$NomArray = array();
		foreach($NominationCol as $Nom)
		{
			$Nomination = new Nominations();
			$Nomination->Id = $Nom["_id"];
			$Nomination->Title = $Nom["Title"];
			$Nomination->Author = $Nom["Author"];
			$Nomination->Description = $Nom["Description"];
			$Nomination->NominatedBy = $Nom["NominatedBy"];
			$Nomination->NominationDate = date('Y-M-d h:i:s', $Nom["NominationDate"]->sec); 
			array_push($NomArray, $Nomination);
		}
		return $NomArray;
	
	}
	
	public function GetAll()
	{		
		$m = new MongoClient();
		$db = $m->BookClub;
		$Nominations = $db->Nominations;
		$NominationCol = $Nominations->find();
		$NomArray = array();
		foreach($NominationCol as $Nom)
		{
			$Nomination = new Nominations();
			$Nomination->Id = $Nom["_id"];
			$Nomination->Title = $Nom["Title"];
			$Nomination->Author = $Nom["Author"];
			$Nomination->Description = $Nom["Description"];
			$Nomination->NominatedBy = $Nom["NominatedBy"];
			$Nomination->NominationDate = date('Y-M-d h:i:s', $Nom["NominationDate"]->sec); 
			array_push($NomArray, $Nomination);
		}
		return $NomArray;
	}
}
?>