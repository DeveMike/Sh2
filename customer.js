// Funktio päivittää UI:n käyttäjän varauksilla
function updateReservationsUI(data) {
    console.log("Saadut tiedot palvelimelta:", data);
    const resultsDiv = document.querySelector('.reservations-container');
    resultsDiv.innerHTML = '';

    if (!data.reservations || data.reservations.length === 0) {
        console.log("Ei varauksia näytettäväksi.");
        resultsDiv.innerHTML = '<p style="color: white;">Ei varauksia</p>';
        toggleBookingLinkAndIcon(false);
        return;
    }
    console.log("Varausdata:", data.reservations);

    data.reservations.forEach(reservation => {
        console.log("Käsitellään varausta:", reservation);
        const div = document.createElement('div');
        div.classList.add('class-card');
        div.setAttribute('data-class-id', reservation.class_id);
        div.setAttribute('data-booking-id', reservation.booking_id);
        div.setAttribute('data-instructor-id', reservation.instructor_id);
        

        const finnishMonths = ["", "Tammi", "Helmi", "Maalis", "Huhti", "Touko", "Kesä", "Heinä", "Elo", "Syys", "Loka", "Marras", "Joulu"];

        const startDate = new Date(reservation.class_start_time);
        const endDate = new Date(reservation.class_end_time);
        
        const formattedStartDate = `${startDate.getDate()} ${finnishMonths[startDate.getMonth() + 1]} | ${startDate.getHours()}:${startDate.getMinutes().toString().padStart(2, '0')} - ${endDate.getHours()}:${endDate.getMinutes().toString().padStart(2, '0')}`;        

        div.innerHTML = `
        <div class="class-info">
        <div class="date-time">${formattedStartDate}</div>
            <div class="booking-id">Varaus ID: ${reservation.booking_id}</div>
            <div class="instructor-name">Ohjaaja: ${reservation.instructor_name}</div>
                <div class="name">${reservation.name}</div>
                <div class="location">Kuntosali: ${reservation.address}</div>
            </div>
            <div class="class-actions">
                <button class="info-btn">Info</button>
                <div class="info-section">${reservation.description}</div>
                <button class="cancel-btn">Peruuta</button>
            </div>
        `;

        resultsDiv.appendChild(div);
        console.log("Varauskortti lisätty:", div);

        // Lisää kuuntelija peruutusnapille
        const cancelBtn = div.querySelector('.cancel-btn');
        cancelBtn.addEventListener('click', function() {
            // Tässä voisi olla logiikka varauksen peruuttamiseen
            console.log("Peruutetaan varaus:", reservation);
        });

        // Lisää kuuntelija info-napille
        const infoBtn = div.querySelector('.info-btn');
        infoBtn.addEventListener('click', function() {
            const classCard = infoBtn.closest('.class-card');
            const infoSection = classCard.querySelector('.info-section');

            // Lisää kuuntelija peruutusnapille
    const cancelBtn = div.querySelector('.cancel-btn');
    cancelBtn.addEventListener('click', function() {
        const classCard = cancelBtn.closest('.class-card');
        const bookingId = classCard.getAttribute('data-booking-id');
        console.log("Peruutetaan varaus, ID:", bookingId);
        // Tässä voisi olla logiikka varauksen peruuttamiseen
    });

            
            if(infoSection.style.display === 'none' || infoSection.style.display === '') {
                console.log("Näytetään lisätiedot varaukselle:", reservation);
                infoSection.style.display = 'block';
            } else {
                console.log("Piilotetaan lisätiedot varaukselle:", reservation);
                infoSection.style.display = 'none';
            }
        });
    });
    toggleBookingLinkAndIcon(true);

}

fetch('getUserReservations.php')
    .then(response => response.json())
    .then(data => {
        if (!data.reservations) {
            console.error('Varauksia ei löytynyt palvelimen vastauksesta:', data);
        } else {
            updateReservationsUI({ reservations: data.reservations });
        }
    })
    .catch(error => console.error('Virhe ladattaessa varauksia:', error))


    function toggleBookingLinkAndIcon(hasReservations) {
        const bookingLink = document.querySelector('.booking-link');
        const plusIcon = document.querySelector('.plus-icon');
    
        if (hasReservations) {
            bookingLink.style.display = 'none';
            plusIcon.style.display = 'none';
        } else {
            bookingLink.style.display = 'block'; // tai 'inline', riippuen alkuperäisestä asetuksesta
            plusIcon.style.display = 'block'; // tai 'inline', riippuen alkuperäisestä asetuksesta
        }
    }

    
