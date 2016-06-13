<?php
class FactureManager
{
	private $_db;

	const ACTION_REUSSIE = 1;
	const ACTION_ECHOUEE = 0;

	public function __construct($db){$this->setDb($db);}
	
	public function setDb($db){$this->_db = $db;}

	# prend une facture en argument, l'ajoute en base de données, hydrate la facture (pour compléter idFacture), retourne 1
	public function add(Facture $facture)
	{
		$q = $this->_db->prepare('INSERT INTO facture SET prixTotal = :prixTotal');

		$q->bindValue(':prixTotal',$facture->prixTotal(),PDO::PARAM_INT);	
		
		$q->execute();
		
		# on hydrate la facture passée en argument avec l'id insérée en base de données
		$facture->hydrate([
			'idFacture'=>$this->_db->lastInsertId(), 
			'prixTotal'=>$facture->prixTotal()
			]);
		
		return self::ACTION_REUSSIE;
	}

	# compte le nombre de factures en base de donnée, retourne un int
	public function count()
	{
		return $this->_db->query('SELECT COUNT(*) FROM facture')->fetchColumn();
	}

	# supprime un commentaire en base de donnée, retourne 1
	public function delete(Facture $facture)
	{
		$q = $this->_db->prepare('DELETE FROM facture WHERE idFacture = :idFacture');
		
		$q->bindValue(':idFacture',$facture->idFacture(),PDO::PARAM_INT);
		
		$q->execute();
		
		return self::ACTION_REUSSIE;
	}

	# teste si un commentaire existe, renvoie un booléen
	public function exists(Facture $facture)
	{    
		$q = $this->_db->prepare('SELECT COUNT(*) FROM facture WHERE idFacture = :idFacture');
		
		$q->bindValue(':idFacture',$facture->idFacture(),PDO::PARAM_INT);

		$q->execute();
    
		return (bool) $q->fetchColumn();
	}
	
	# prend en argument l'id d'une facture, retourne la facture concernée si elle existe
	public function get($idFacture)
	{
		$q = $this->_db->prepare('SELECT idFacture, prixTotal FROM facture WHERE idFacture = :idFacture');	
		$q->execute([':idFacture' => $idFacture]);

		$facture = $q->fetch(PDO::FETCH_ASSOC);
		
		return empty($facture) ? null : new Facture($facture);
	}
	
  	# prend en agument un idFacture ou un prix total et retourne un array de factures
	public function getList($idFacture, $prixTotal)
	{
		$factures = [];
		
		$q = $this->_db->prepare('
			SELECT * 
			FROM facture
			WHERE idFacture LIKE :idFacture 
			AND prixTotal LIKE :prixTotal
			ORDER BY idFacture');

    		$q->bindParam(':idFacture', $idFacture, PDO::PARAM_INT);
    		$q->bindParam(':prixTotal', $prixTotal, PDO::PARAM_INT);
		
			$q->execute();
	    
		while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
		{
			$factures[] = new Facture($donnees); 
		}
		return $factures;
	}

  	# prend une facture en argument, la modifie en base de données, retourne 1 si réussi, 0 sinon
	public function update(Facture $facture)
	{
		if($this->exists($facture))
		{
			$q = $this->_db->prepare('UPDATE facture SET prixTotal = :prixTotal WHERE idFacture = :idFacture');
		    
			$q->bindValue(':idFacture',$facture->idFacture(),PDO::PARAM_INT);
			$q->bindValue(':prixTotal',$facture->prixTotal(),PDO::PARAM_INT);
			
		    
			$q->execute();
			return self::ACTION_REUSSIE;
		}
		else
		{
			return self::ACTION_ECHOUEE;
		}
	}
}
