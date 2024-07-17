document.addEventListener('DOMContentLoaded', function() {
    const calendar = document.getElementById('calendar');
    let currentDate = new Date();
    let events = {
        '2024-7-17': 'Location voiture le 17/07/2024',
        '2024-7-22': 'Concert le 22/07/2024'
    };

    const months = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

    function renderCalendar(month, year) {
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
            const dayKey = `${year}-${month + 1}-${i}`;
            const hasEvent = events[dayKey] ? 'has-event' : '';
            const eventDot = hasEvent ? '<div class="event-dot"></div>' : '';
            daysContainer.innerHTML += `
                <div class="day ${hasEvent}" data-date="${dayKey}">
                    <div class="date">${i}</div>
                    ${eventDot}
                </div>`;
        }

        document.getElementById('prevMonth').addEventListener('click', () => {
            changeMonth(-1);
        });

        document.getElementById('nextMonth').addEventListener('click', () => {
            changeMonth(1);
        });

        document.querySelectorAll('.day.has-event').forEach(day => {
            day.addEventListener('click', showEventDetails);
        });
    }

    function changeMonth(step) {
        currentDate.setMonth(currentDate.getMonth() + step);
        renderCalendar(currentDate.getMonth(), currentDate.getFullYear());
    }

    function showEventDetails(event) {
        const date = event.currentTarget.getAttribute('data-date');
        const eventDetails = events[date];
        const modal = document.getElementById('eventModal');
        const eventDetailsContainer = document.getElementById('eventDetails');

        eventDetailsContainer.placeholder = eventDetails;
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

    renderCalendar(currentDate.getMonth(), currentDate.getFullYear());
});

