document.addEventListener('DOMContentLoaded', function() {
    const calendar = document.getElementById('calendar');
    const currentScript = window.pageName;
    let currentDate = new Date();
    let events = {};
    let color = {
        'Voitures': 'blue',
        'Salle': 'red',
        'Rdv-rEu': 'orange',
        "twalet": 'green'
    };

    // Fonction pour récupérer les événements depuis le serveur PHP
     function fetchEvents() {
            console.log('Fetching events...');
            axios.get('http://localhost:5000/')
                .then(response => {
                    console.log('Data received:', response.data);
                    events = formatEvents(response.data);
                    renderCalendar(new Date().getMonth(), new Date().getFullYear());
                })
                .catch(error => console.error('Erreur lors de la récupération des événements:', error));
        }

        function formatEvents(eventArray) {
            const formattedEvents = {};
            eventArray.forEach(event => {
                const eventDate = event.event_date.split('T')[0]; // Assure que l'heure est supprimée
                if (!formattedEvents[eventDate]) {
                    formattedEvents[eventDate] = [];
                }
                formattedEvents[eventDate].push(event);
            });
            return formattedEvents;
        }

    const months = [
        'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
        'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
    ];

    function renderCalendar(month, year) {
        console.log('Rendering calendar for:', month, year);
        console.log('Events:', events);

        calendar.innerHTML = `
            <header>
                <button id="prevMonth">&lt;</button>
                <h1>${months[month]} ${year}</h1>
                <button id="nextMonth">&gt;</button>
            </header>
            <div class="days">
                <div class="header">Sun</div>
                <div class="header">Mon</div>
                <div class="header">Tue</div>
                <div class="header">Wed</div>
                <div class="header">Thu</div>
                <div class="header">Fri</div>
                <div class="header">Sat</div>
            </div>
        `;

        const daysContainer = calendar.querySelector('.days');
        const firstDay = new Date(year, month, 1).getDay();
        const lastDay = new Date(year, month + 1, 0).getDate();

        for (let i = 0; i < firstDay; i++) {
            daysContainer.innerHTML += '<div></div>';
        }

        for (let i = 1; i <= lastDay; i++) {
            const randomColor = color[currentScript]
            

            const dayKey = `${year}-${(month + 1).toString().padStart(2, '0')}-${i.toString().padStart(2, '0')}`;
            const hasEvent = events[dayKey] ? 'has-event' : '';
            const eventDot = hasEvent ? `<div class="event-dot" style="background-color: ${randomColor}"></div>` : '';
            daysContainer.innerHTML += `
                <div class="day ${hasEvent}" data-date="${dayKey}">
                    <div class="date">${i}</div>
                    ${eventDot}
                </div>`;
        }

        document.getElementById('prevMonth').addEventListener('click', () => {
            changeMonth(-1); // Change to subtract 12 months
        });

        document.getElementById('nextMonth').addEventListener('click', () => {
            changeMonth(1); // Change to add 12 months
        });

        document.querySelectorAll('.day.has-event').forEach(day => {
            day.addEventListener('click', showEventDetails);
        });
    }

    function changeMonth(step) {
        const newMonth = currentDate.getMonth() + step;
        currentDate.setMonth(newMonth);



        renderCalendar(currentDate.getMonth(), currentDate.getFullYear());
    }

    function showEventDetails(event) {
        const date = event.currentTarget.getAttribute('data-date');
        const modal = document.getElementById('eventModal');
        const eventDetailsContainer = document.getElementById('eventType');

        eventDetailsContainer.placeholder = events[date][0]['event_title'];
        modal.style.display = 'block';

        const span = document.getElementsByClassName('close')[0];
        span.onclick = function() {
            modal.style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    }

    fetchEvents(); // Appel pour récupérer les événements au chargement de la page
});
