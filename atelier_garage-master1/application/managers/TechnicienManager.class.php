<?php
class TechnicienManager
{
	private $_db;

	const ACTION_REUSSIE = 1;
	const ACTION_ECHOUEE = 0;

	public function __construct($db){$this->setDb($db);}
	
	public function setDb($db){$this->_db = $db;}

	public function add(Technicien $technicien)
	{
		$q = $this->_db->prepare('INSERT INTO technicien SET numero = :numero, nom = :nom, prenom = :prenom');

		$q->bindValue(':numero',$technicien->numero(),PDO::PARAM_INT);
		$q->bindValue(':nom',$technicien->nom(),PDO::PARAM_STR);
		$q->bindValue(':prenom',$technicien->prenom(),PDO::PARAM_STR);
		
		$q->execute();
		
		$technicien->hydrate([
			'numero'=>$technicien->numero(), 
			'nom'=>$technicien->nom(), 
			'prenom'=>$technicien->prenom(),
			'nombre'=>0
			]);
		
		return self::ACTION_REUSSIE;
	}

	public function count()
	{
		return $this->_db->query('SELECT COUNT(*) FROM technicien')->fetchColumn();
	}

	public function delete(Technicien $technicien)
	{
		$q = $this->_db->prepare('DELETE FROM technicien WHERE numero = :numero');
		$q->execute([':numero' => $technicien->numero()]);
		return self::ACTION_REUSSIE;
	}

	public function exists(Technicien $technicien)
	{    
		$q = $this->_db->prepare('SELECT COUNT(*) FROM technicien WHERE numero = :numero');
		$q->execute([':numero' => $technicien->numero()]);
    
		return (bool) $q->fetchColumn();
	}

  
	public function get($numero)
	{
		$q = $this->_db->prepare('
			SELECT numero, nom, prenom, IFNULL(cnt,0) as nombre
			FROM (
				SELECT technicien, COUNT(dateFin) as cnt
				FROM repare
				GROUP BY technicien
              	) T
			RIGHT JOIN technicien
			ON T.technicien = technicien.numero
			WHERE technicien.numero = :numero
		');
			
		$q->execute([':numero' => $numero]);

		$technicien = $q->fetch(PDO::FETCH_ASSOC);
		
		return empty($technicien) ? NULL : new Technicien($technicien);
	}
	
	public function getAll()
	{
		$techniciens = [];
    
		$q = $this->_db->prepare('
			SELECT numero, nom, prenom, IFNULL(cnt,0) as nombre
			FROM (
				SELECT technicien, COUNT(dateFin) as cnt
				FROM repare
				GROUP BY technicien
                	) T

			RIGHT JOIN technicien
			ON T.technicien = technicien.numero
		');
		
		$q->execute();
		    
		while ($technicien = $q->fetch(PDO::FETCH_ASSOC))
		{
			$techniciens[] = new Technicien($technicien);
				
		}
		    
		return $techniciens;
	}
	
	public function getList($numero, $nom, $prenom)
	{
		$techniciens = [];

		$q = $this->_db->prepare('
			SELECT numero, nom, prenom, IFNULL(cnt,0) as nombre
			FROM (
				SELECT technicien, COUNT(dateFin) as cnt
				FROM repare
				GROUP BY technicien
                	) T
			RIGHT JOIN technicien
			ON T.technicien = technicien.numero
			WHERE numero LIKE :numero
			AND nom LIKE :nom
			AND prenom LIKE :prenom
			ORDER BY numero
		');


    		$q->bindParam(':numero', $numero, PDO::PARAM_INT);
    		$q->bindParam(':nom', $nom, PDO::PARAM_STR);
		$q->bindParam(':prenom', $prenom, PDO::PARAM_STR);
	 
		$q->execute();
	    
		while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
		{
			$techniciens[] = new Technicien($donnees); 
		}
		return $techniciens;
	}
	
  
	public function update(Technicien $technicien)
	{
		if($this->exists($technicien))
		{
			$q = $this->_db->prepare('UPDATE technicien SET nom = :nom, prenom = :prenom WHERE numero = :numero');
		    
			$q->bindValue(':nom',$technicien->nom(),PDO::PARAM_STR);
			$q->bindValue(':prenom',$technicien->prenom(),PDO::PARAM_STR);
			$q->bindValue(':numero',$technicien->numero(),PDO::PARAM_INT);
		    
			$q->execute();
			return self::ACTION_REUSSIE;
		}
		else
		{
			return self::ACTION_ECHOUEE;
		}
	}
}
