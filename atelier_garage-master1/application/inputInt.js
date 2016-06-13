function surligne(champ, erreur)
{
	if(erreur)
		champ.style.backgroundColor = "#fba";
	else
		champ.style.backgroundColor = "";
}

function isInt(champ)
{
	if(!Number.isInteger(champ))
	{
		surligne(champ, true);
		return false;
	}
	else
	{
		surligne(champ, false);
		return true;
	}
}

function verifForm(f)
{
	var intOk = isInt(f.numero);
   
	if(intOk)
   		return true;
   	else
   	{
    	alert("Vous devez saisir un entier");
    	return false;
    }
}