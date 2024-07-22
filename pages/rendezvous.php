<meta name="page" content='Rdv-rEu'>
<div id="calendar"></div>
<div id="eventModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Détails</h2>
        <div class="eventDetails">
            <label>Type d'évènement</label>
            <input type="text" disabled id="eventType" placeholder="">
            <label>Nom de l'évenement</label>
            <input type="text" disabled id="eventDetails" placeholder="">
            <label>From</label>
            <input type="text" disabled id="eventDetails" placeholder="">
            <label>To</label>
            <input type="text" disabled id="eventDetails" placeholder="">
            <label>Qui</label>
            <input type="text" disabled id="eventDetails" placeholder="">
        </div>
    </div>
</div>
<div id="newEvent-page">
    <button id='eventButton-page'>Ajouter un évenement</button>
    <div id="new-page">
        <label for="name">Nom & Prénom</label>
        <input type="text" id="name-page" placeholder="Ex: Jean Dupont">
        <label for="title">Titre de l'évenement</label>
        <input type="text" id="title-page" placeholder="Ex: Réunion administrative">
        <label for="description">Description</label>
        <textarea type="text" id="desc-page" placeholder="Ex: Rendez vous dans la salle .. pour la réunion....."></textarea>
        <label for="from">Le</label>
        <input type="text" id="from-page">
        <label for="to">L'heures</label>
        <input type="text" id="hours-page" placeholder="Ex: 17:00">
        <input type='submit' id='submitButton-page'></input>
    
        <div class="return-page">
            <button id='backButton-page'>Retour</button>
        </div>
    
    </div>
</div>

<script>
    button = document.getElementById('eventButton-page')
    newEvent = document.getElementById('new-page')
    eventModal = document.getElementById('calendar')
    returnButton = document.getElementById('backButton-page')
    submitButton = document.getElementById('submitButton-page')
    
    button.addEventListener('click', function() {
        button.style.display = 'none';
        eventModal.style.display = 'none';
        newEvent.style.display = 'flex';
        document.getElementById('from-page').value = window.dateDay;
    })

    returnButton.addEventListener('click', function() {
        returnButton.display = 'none'
        eventModal.style.display = 'block';
        button.style.display = 'flex'
        newEvent.style.display = 'none';
    })

    submitButton.addEventListener('click', function() {
        window.name = document.getElementById('name-page').value;
        window.title = document.getElementById('title-page').value;
        window.desc = document.getElementById('desc-page').value;
        window.dateDay = document.getElementById('from-page').value;
        window.hours = document.getElementById('hours-page').value;
        newEvents();
    })
</script>