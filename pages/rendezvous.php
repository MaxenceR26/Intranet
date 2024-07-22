<script>window.pageName = 'Rdv-rEu';</script>
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
        <input type="text" placeholder="Ex: Jean Dupont">
        <label for="title">Titre de l'évenement</label>
        <input type="text" placeholder="Ex: Réunion administrative">
        <label for="description">Description</label>
        <textarea type="text" placeholder="Ex: Rendez vous dans la salle .. pour la réunion....."></textarea>
        <label for="from">Du</label>
        <input type="text" placeholder="Ex: 22/07/2024">
        <label for="to">Au</label>
        <input type="text" placeholder="Ex: 26/07/2024">
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
    button.addEventListener('click', function() {
        button.style.display = 'none';
        eventModal.style.display = 'none';
        newEvent.style.display = 'flex';

    })

    returnButton.addEventListener('click', function() {
        returnButton.display = 'none'
        eventModal.style.display = 'block';
        button.style.display = 'flex'
        newEvent.style.display = 'none';
    })
</script>