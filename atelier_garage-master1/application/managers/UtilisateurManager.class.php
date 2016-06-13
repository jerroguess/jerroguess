<?php
class UtilisateurManager
{
	private $_db;

	const ACTION_REUSSIE = 1;
	const ACTION_ECHOUEE = 0;

	public function __construct($db){$this->setDb($db);}
	
	public function setDb($db){$this->_db = $db;}

	# prend un utilisateur en argument, retourne 1
	public function add(Utilisateur $utilisateur)
	{
		$q = $this->_db->prepare('INSERT INTO utilisateurs SET pseudo = :pseudo, pass = :pass, privileges= :privileges');

		$q->bindValue(':pseudo',$utilisateur->pseudo(),PDO::PARAM_STR);
		$q->bindValue(':pass',$utilisateur->pass(),PDO::PARAM_STR);
		$q->bindValue(':privileges',$utilisateur->privileges(),PDO::PARAM_STR);

		$q->execute();
		
		$utilisateur->hydrate([
			'id'=>$this->_db->lastInsertId(), 
			'pseudo'=>$utilisateur->pseudo(), 
			'pass'=>$utilisateur->pass(), 
			'privileges'=>$utilisateur->privileges()]);

		return self::ACTION_REUSSIE;
	}
	
	# retourne le nombre de utlisateurs en bdd (int)
	public function count()
	{
		return $this->_db->query('SELECT COUNT(*) FROM utlisateurs')->fetchColumn();
	}
	
	# prend un utilisateur en argument, retourne 1 si l'action est réussie
	public function delete(Utilisateur $utilisateur)
	{
		$q = $this->_db->prepare('DELETE FROM utilisateurs WHERE id = :id');
		$q->execute([':id' => $utilisateur->id()]);
		return self::ACTION_REUSSIE;
	}
	
	# prend un utilisateur en argument, retourne un booléen
	public function exists(Utilisateur $utilisateur)
	{    
		$q = $this->_db->prepare('SELECT COUNT(*) FROM utilisateurs WHERE id = :id');
		$q->execute([':id' => $utilisateur->id()]);
    
		return (bool) $q->fetchColumn();
	}

  	# prend un pseudo et pass en argument, retourne un utilisateur si il existe
	public function get($id)
	{		
		$q = $this->_db->prepare('SELECT * FROM utilisateurs WHERE Id = :id');	
    	$q->bindParam(':id', $id, PDO::PARAM_INT);
		$q->execute();

		$utilisateur = $q->fetch(PDO::FETCH_ASSOC);
		
		return empty($utilisateur) ? null : new Utilisateur($utilisateur);
	}
  
	# retourne un tableau de clients
	public function getList($id, $pseudo, $pass, $privileges)
	{
		$utilisateurs = [];

		$q = $this->_db->prepare('
			SELECT *
			FROM utilisateurs
			WHERE id LIKE :id
			AND pseudo LIKE :pseudo
			AND pass LIKE :pass
			AND privileges LIKE :privileges'
		);

    	$q->bindParam(':id', $id, PDO::PARAM_INT);
    	$q->bindParam(':pseudo', $pseudo, PDO::PARAM_INT);
    	$q->bindParam(':pass', $pass, PDO::PARAM_INT);
    	$q->bindParam(':privileges', $privileges, PDO::PARAM_INT);

		$q->execute();
	    
		while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
		{
			$utilisateurs[] = new Utilisateur($donnees); 
		}
		return $utilisateurs;
	}
	
  	# prend un utilisateur en argument, retourne 1 si l'action est réussie, 0 sinon
	public function update(Utilisateur $utilisateur)
	{
		if($this->exists($utilisateur))
		{
			$q = $this->_db->prepare('UPDATE utilisateurs SET pseudo = :pseudo, pass = :pass, privileges= :privileges WHERE id = :id');
		    
		$q->bindValue(':id',$utilisateur->id(),PDO::PARAM_STR);
		$q->bindValue(':pseudo',$utilisateur->pseudo(),PDO::PARAM_STR);
		$q->bindValue(':pass',$utilisateur->pass(),PDO::PARAM_STR);
		$q->bindValue(':privileges',$utilisateur->privileges(),PDO::PARAM_STR);
		    	
			$q->execute();

			return self::ACTION_REUSSIE;
		}
		else
		{
			return self::ACTION_ECHOUEE;
		}
	}
}
