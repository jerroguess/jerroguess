# atelier_garage
modélisation de l'atelier d'un garage avec PHP et mysql

Un garage peut comporter plusieurs ateliers. On ne s’occupe que de l’atelier
de réparation dont on souhaite faire la gestion.

Une voiture à réparer est identifiée par son numéro d’immatriculation et
caractérisée par sa marque, son type, son année, son kilométrage et sa date
d’arrivée au garage. 

Chaque client est identifié par un numéro et caractérisé par
son nom, son prénom, son adresse (commune) et par le nom de la personne qui
prend en charge le suivi administratif de la commande.

Chaque commune est identifiée par son nom et caractérisée par le nombre
de clients qui ont réparé leurs voitures dans le garage.

Le garage offre aux clients deux types de réparations :
Soit forfaitaire, incluant les pièces détachées (vidange moteur, changement de
filtre à huile, remplacement des plaquettes de frein, changement d’amortisseurs
ou pneus, etc.), soit non forfaitaire dépendant de la main d’oeuvre et des pièces
détachées.

Pendant l’intervention, le technicien peut noter ses remarques. Le technicien
est alors identifié par son numéro et caractérisé par son nom et prénom, ainsi
que le nombre de voitures qu’il a réparées.
