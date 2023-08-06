const codeemail = document.querySelector("#codemail");
codeemail.addEventListener("keydown", function (event) {
    // crée un évènement sur le input quand il y a un clic sur le /!\clavier/!\
  
    if (/[^0-9]/.test(event.key)&& event.key !== 'Backspace' && event.key !== 'Delete') {
      // le / c'est une délimitation et le ^ c'est une négation
  
      event.preventDefault(); // ça annule l'évènement d'avant si la condition n'est pas respectééééééé
    }
  });