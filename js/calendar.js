eventButton = document.getElementById('eventButton-page-page-calendar');
const metaTag = document.querySelector('meta[name="page"]');
const currentScript = metaTag ? metaTag.getAttribute('content') : 'inconnue';

document.addEventListener('DOMContentLoaded', function() {
    const eventButton = document.getElementById('eventButton-page-calendar');
    const metaTag = document.querySelector('meta[name="page"]');
    const currentScript = metaTag ? metaTag.getAttribute('content') : 'inconnue';
    const calendar = document.getElementById('calendar-page-calendar');
    let currentDate = new Date();
    let events = {};
    let color = {
        'Voitures': 'blue',
        'Salles': 'red',
        'Rdv-rEu': 'orange',
        "twalet": 'green'
    };

    let title = {
        'Voitures': 'Calendrier location de voiture',
        'Salles': 'Calendrier location de salle',
        'Rdv-rEu': 'Calendrier Réunions/RDV'
    };

    function fetchEvents() {
        console.log('Fetching events...');
        axios.get('http://localhost:5000/type/' + currentScript)
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
            <header id="header-page-calendar">
                <button id="prevMonth-page-calendar">&lt;</button>
                <h1>${months[month]} ${year}</h1>
                <button id="nextMonth-page-calendar">&gt;</button>
            </header>
            <p>${title[currentScript]}</p>
            <div class="days-page-calendar">
                <div class="header-page-calendar">Sun</div>
                <div class="header-page-calendar">Mon</div>
                <div class="header-page-calendar">Tue</div>
                <div class="header-page-calendar">Wed</div>
                <div class="header-page-calendar">Thu</div>
                <div class="header-page-calendar">Fri</div>
                <div class="header-page-calendar">Sat</div>
            </div>
        `;

        const daysContainer = calendar.querySelector('.days-page-calendar');
        const firstDay = new Date(year, month, 1).getDay();
        const lastDay = new Date(year, month + 1, 0).getDate();

        for (let i = 0; i < firstDay; i++) {
            daysContainer.innerHTML += '<div></div>';
        }

        for (let i = 1; i <= lastDay; i++) {
            const dayKey = `${year}-${(month + 1).toString().padStart(2, '0')}-${i.toString().padStart(2, '0')}`;
            const hasEvent = events[dayKey] ? 'has-event-page-calendar' : '';
            const isDisabled = hasEvent ? 'disabled-page-calendar' : ''; // Ajout de la classe 'disabled' si l'événement est présent
            const eventDot = hasEvent ? `<div class="event-dot-page-calendar" style="background-color: ${color[currentScript]}"></div>` : '';
            daysContainer.innerHTML += `
                <div class="day-page-calendar ${hasEvent} ${isDisabled}" data-date="${dayKey}">
                    <div class="date">${i}</div>
                    ${eventDot}
                </div>`;
        }

        document.getElementById('prevMonth-page-calendar').addEventListener('click', () => {
            changeMonth(-1); // Change to subtract 1 month
        });

        document.getElementById('nextMonth-page-calendar').addEventListener('click', () => {
            changeMonth(1); // Change to add 1 month
        });

        document.querySelectorAll('.day-page-calendar').forEach(day => {
            if (!day.classList.contains('disabled-page-calendar')) { // Ne pas ajouter l'événement de clic si le jour est désactivé
                day.addEventListener('click', (event) => {
                    const date = event.currentTarget.querySelector('.date').textContent;
                    const formattedDate = `${year}-${(month + 1).toString().padStart(2, '0')}-${date.padStart(2, '0')}`;
                    eventButton.style.display = 'flex';
                    window.dateDay = formattedDate;
                });
            }
        });

        document.querySelectorAll('.day-page-calendar.has-event-page-calendar').forEach(day => {
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
        const modal = document.getElementById('eventModal-page-calendar');
        const eventDetailsContainer = document.getElementById('eventType-page-calendar');

        eventDetailsContainer.placeholder = events[date][0]['event_title'];
        modal.style.display = 'block';

        const span = document.getElementsByClassName('close-page-calendar')[0];
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

function convertDateFormat(dateStr) {
    var dateParts = dateStr.split('/');
    var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];
    return formattedDate;
}

function newEvents() {
    // var newDateFrom = convertDateFormat(window.from);
    // var newDateTo = convertDateFormat(window.from);

    axios.post('http://localhost:5000/createevent/', {
        event_title: window.title,
        event_date: window.dateDay,
        descriptions: window.desc,
        hours: window.hours,
        type: currentScript
      }, {
        headers: {
          'accept': 'application/json',
          'Content-Type': 'application/json'
        }
      })
      .then(response => {
        window.location.reload();
        eventButton.style.display = 'none';
      })
      .catch(error => {
        console.error('Une erreur est survenue pendant l\'execution de la reqûete', error);
      });
}