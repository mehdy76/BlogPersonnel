//Confirmation pour delete article

function confirmationdeleteaction(objet, id) {
	if (confirm("Voulez vous vraiment supprimer l'objet ?" )) {
		window.location.href = "editmod.php?"+objet+"="+id+"&delete"
    }
}