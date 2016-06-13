function surligne(champ, erreur)
{
	if(erreur)
		champ.style.backgroundColor = "#fba";
	else
		champ.style.backgroundColor = "";
}

function isInt(champ)
{
	var regex = /^\+?(0|[1-9]\d*)$/;

	if((!regex.test(champ.value)))
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

function verifFormClient(f)
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

function verifFormFacture(f)
{
	var intOk = isInt(f.idFacture);

	if(intOk)
			return true;
	else
	{
		alert("Vous devez saisir un entier");
		return false;
	}
}
