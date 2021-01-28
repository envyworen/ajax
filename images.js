// Gestionnaire de la requête AJAX
let xhr = new XMLHttpRequest();
xhr.onreadystatechange = function () {
	if (this.readyState === 4 && this.status === 200) {
		let data = JSON.parse(this.responseText);
		controller(data);
	} else {
		showMessages(["Erreur " + this.status]);
	}
};

// Click sur le bouton readAll
document.getElementById("readAll").addEventListener("click", function () {
	xhr.open("GET", "images_control.php", true);
	xhr.send();
});

// Affiche un tableau de message dans la div "message"
function showMessages(messages) {
	let div = document.getElementById("message");
	div.innerHTML = "";
	messages.forEach(function(message) {
		let p = document.createElement("p");
		p.innerText = message;
		div.appendChild(p);
	});
}

// Affiche une liste d'images dans la div "content"
function listImages(images) {
	let content = document.getElementById("content");
	content.innerHTML = "";
	images.forEach(function (image) {
		let p = document.createElement("p");
		p.innerText = image.titre;
		content.appendChild(p);
	});
}

// Controller JS => récupère les données JSON reçues par la requête AJAX
function controller(data) {
	console.log(data);
	if (data.action === 'read') {
		if (data.images) {
			showMessages(data.messages);
			listImages(data.images);
		}
	}
}
