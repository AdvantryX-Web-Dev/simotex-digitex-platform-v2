document.addEventListener("DOMContentLoaded", function () {
    setTimeout(function () {
        var statusMessages = document.querySelectorAll('.statusM');
        console.log(statusMessages); // Vérifie si les éléments sont trouvés
        if (statusMessages.length > 0) {
            statusMessages.forEach(function (statusMessage) {
                statusMessage.style.display = 'none';
            });
            // Modifier l'URL sans recharger la page
            history.replaceState({}, document.title, window.location.pathname);
        }
    }, 1000);
});



function formToggle(ID) {
    var element = document.getElementById(ID);
    if (element.style.display === "none") {
        element.style.display = "block";
    } else {
        element.style.display = "none";
    }
}