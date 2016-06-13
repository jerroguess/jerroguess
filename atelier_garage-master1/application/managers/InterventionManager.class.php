<?php
class InterventionManager
{
	private $_db;

	const ACTION_REUSSIE = 1;
	const ACTION_ECHOUEE = 0;

	public function __construct($db){$this->setDb($db);}
	
	public function setDb($db){$this->_db = $db;}

	public function add(Intervention $intervention)
	{
		$q = $this->_db->prepare('INSERT INTO intervention SET nom = :nom, prix= :prix');

		$q->bindValue(':nom',$intervention->nom(),PDO::PARAM_STR);
		$q->bindValue(':prix',$intervention->prix(),PDO::PARAM_INT);
		
		$q->execute();
		
		$intervention->hydrate([
			'id'=>$this->_db->lastInsertId(), 
			'nom'=>$intervention->nom(), 
			'prix'=>$intervention->prix()]);
		
		return self::ACTION_REUSSIE;
	}

	public function count()
	{
		return $this->_db->query('SELECT COUNT(*) FROM intervention')->fetchColumn();
	}

	public function delete(Intervention $intervention)
	{
		$q = $this->_db->prepare('DELETE FROM intervention WHERE id = :id');
		$q->bindValue(':id',$intervention->id(),PDO::PARAM_INT);
		
		$q->execute();
		
		return self::ACTION_REUSSIE;
	}

	public function exists(Intervention $intervention)
	{    
		$q = $this->_db->prepare('SELECT COUNT(*) FROM intervention WHERE id = :id');
		$q->bindValue(':id',$intervention->id(),PDO::PARAM_INT);

		$q->execute();
    
		return (bool) $q->fetchColumn();
	}
	
	public function get($id)
	{
		$q = $this->_db->prepare('SELECT id, nom, prix FROM intervention WHERE id = :id');	
		$q->execute([':id' => $id]);

		$intervention = $q->fetch(PDO::FETCH_ASSOC);
		
		return empty($intervention) ? null : new Intervention($intervention);
	}
	
  
	public function getList($id, $nom, $prix)
	{
		$interventions = [];
		
		$q = $this->_db->prepare('
						SELECT * 
						FROM intervention 
						WHERE id LIKE :id 
						AND nom LIKE :nom 
						AND prix LIKE :prix
						ORDER BY prix
		');

    	$q->bindParam(':id', $id, PDO::PARAM_INT);
    	$q->bindParam(':nom', $nom, PDO::PARAM_STR);
		$q->bindParam(':prix', $prix, PDO::PARAM_INT);
		$q->execute();
	    
		while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
		{
			$interventions[] = new Intervention($donnees); 
		}
		return $interventions;
	}

  
	public function update(Intervention $intervention)
	{
		if($this->exists($intervention))
		{
			$q = $this->_db->prepare('UPDATE intervention SET prix= :prix, nom = :nom WHERE id = :id');
		    
			$q->bindValue(':id',$intervention->id(),PDO::PARAM_INT);
			$q->bindValue(':nom',$intervention->nom(),PDO::PARAM_STR);
			$q->bindValue(':prix',$intervention->prix(),PDO::PARAM_INT);
		    
			$q->execute();
			return self::ACTION_REUSSIE;
		}
		else
		{
			return self::ACTION_ECHOUEE;
		}
	}
}