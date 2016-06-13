<?php
class RepareManager
{
	private $_db;

	const ACTION_REUSSIE = 1;
	const ACTION_ECHOUEE = 0;

	public function __construct($db){$this->setDb($db);}
	
	public function setDb($db){$this->_db = $db;}

	# prend un repare en argument, retourne 1
	public function add(Repare $repare)
	{
		$q = $this->_db->prepare('
			INSERT INTO repare 
			SET technicien = :technicien, voiture = :voiture, idFacture = :idFacture, dateDebut= :dateDebut, dateFin = :dateFin
		');

		$q->bindValue(':technicien',$repare->technicien(),PDO::PARAM_INT);
		$q->bindValue(':voiture',$repare->voiture(),PDO::PARAM_STR);
		$q->bindValue(':idFacture',$repare->idFacture(),PDO::PARAM_INT);
		$q->bindValue(':dateDebut',$repare->dateDebut(),PDO::PARAM_STR);
		$q->bindValue(':dateFin',$repare->dateFin(),PDO::PARAM_STR);
		
		$q->execute();
		
		return self::ACTION_REUSSIE;
	}
	
	# retourne le nombre de repares en bdd (int)
	public function count()
	{
		return $this->_db->query('SELECT COUNT(*) FROM repare')->fetchColumn();
	}
	
	# prend un repare en argument, le supprime en base de données, retourne 1 si l'action est réussie
	public function delete(Repare $repare)
	{
		$q = $this->_db->prepare('
			DELETE FROM repare 
			WHERE (technicien = :technicien AND voiture = :voiture AND dateDebut = :dateDebut)
		');
		
		$q->bindValue(':technicien',$repare->technicien(),PDO::PARAM_INT);
		$q->bindValue(':voiture',$repare->voiture(),PDO::PARAM_STR);
		$q->bindValue(':dateDebut',$repare->dateDebut(),PDO::PARAM_STR);
		
		$q->execute();
		
		return self::ACTION_REUSSIE;
	}

	# prend un repare en argument, retourne un booléen
	public function exists(Repare $repare)
	{    
		$q = $this->_db->prepare('
			SELECT COUNT(*)
			FROM repare 
			WHERE (technicien = :technicien AND voiture = :voiture AND dateDebut = :dateDebut)
		');
		
		$q->bindValue(':technicien',$repare->technicien(),PDO::PARAM_INT);
		$q->bindValue(':voiture',$repare->voiture(),PDO::PARAM_STR);
		$q->bindValue(':dateDebut',$repare->dateDebut(),PDO::PARAM_STR);
		
		$q->execute();
    
		return (bool) $q->fetchColumn();
	}

  	# prend un technicien, une voiture et une facture en argument, retourne un repare si il existe
	public function get($technicien, $voiture, $dateDebut)
	{
		$q = $this->_db->prepare('
			SELECT * 
			FROM repare 
			WHERE (technicien = :technicien AND voiture = :voiture AND dateDebut = :dateDebut)
		');
			
		$q->bindValue(':technicien',$technicien,PDO::PARAM_INT);
		$q->bindValue(':voiture',$voiture,PDO::PARAM_STR);
		$q->bindValue(':dateDebut',$dateDebut,PDO::PARAM_STR);
		
		$q->execute();

		$repare = $q->fetch(PDO::FETCH_ASSOC);
		
		return empty($repare) ? null : new Repare($repare);
	}
  
	# retourne un array de repares
	public function getList($technicien, $voiture, $idFacture, $dateDebut, $dateFin)
	{
		$repares = [];
		
		$bonus = empty($dateFin) ? '' : 'AND dateFin LIKE :dateFin';

		$q = $this->_db->prepare('
			SELECT technicien, voiture, idFacture, dateDebut, dateFin
			FROM repare
			WHERE technicien LIKE :technicien
			AND voiture LIKE :voiture
			AND idFacture LIKE :idFacture 
			AND dateDebut LIKE :dateDebut 
			' .$bonus. '
			ORDER BY dateDebut
		');


    	$q->bindParam(':technicien', $technicien, PDO::PARAM_INT);
    	$q->bindParam(':voiture', $voiture, PDO::PARAM_STR);
		$q->bindParam(':idFacture', $idFacture, PDO::PARAM_INT);
		$q->bindParam(':dateDebut', $dateDebut, PDO::PARAM_STR);
		if (!empty($dateFin)) {$q->bindParam(':dateFin', $dateFin, PDO::PARAM_STR); }
		
		$q->execute();
	    
		while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
		{
			$repares[] = new Repare($donnees); 
		}
		return $repares;
	}
	
  	# prend un repare en argument, retourne 1 si l'action est réussie, 0 sinon
	public function update(Repare $repare)
	{
		if($this->exists($repare))
		{
			$q = $this->_db->prepare('
				UPDATE repare 
				SET dateFin = :dateFin, idFacture = :idFacture 
				WHERE (technicien = :technicien 
				AND voiture = :voiture
				AND dateDebut = :dateDebut)
			');
		
			$q->bindValue(':dateDebut',$repare->dateDebut(),PDO::PARAM_STR);
			$q->bindValue(':dateFin',$repare->dateFin(),PDO::PARAM_STR);
			$q->bindValue(':voiture',$repare->voiture(),PDO::PARAM_STR);
			$q->bindValue(':technicien',$repare->technicien(),PDO::PARAM_INT);
			$q->bindValue(':idFacture',$repare->idFacture(),PDO::PARAM_INT);
			
			$q->execute();
			
			return self::ACTION_REUSSIE;
		}
		else
		{
			return self::ACTION_ECHOUEE;
		}
	}
}
