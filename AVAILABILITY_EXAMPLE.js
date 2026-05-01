// Exemple d'intégration - Vérification de disponibilité avant réservation
// À utiliser dans votre application mobile ou frontend

/**
 * Service de gestion de disponibilité
 */
class AvailabilityService {
  constructor(apiUrl = 'https://api.example.com') {
    this.apiUrl = apiUrl;
  }

  /**
   * Vérifier si des chambres sont disponibles pour les dates demandées
   */
  async checkAvailability(roomTypeId, checkIn, checkOut) {
    try {
      const response = await fetch(`${this.apiUrl}/api/availability/check`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          room_type_id: roomTypeId,
          check_in: checkIn,
          check_out: checkOut,
        }),
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      return await response.json();
    } catch (error) {
      console.error('Erreur lors de la vérification de disponibilité:', error);
      return null;
    }
  }

  /**
   * Obtenir le calendrier mensuel de disponibilité
   */
  async getMonthlyCalendar(roomTypeId, startDate, endDate) {
    try {
      const params = new URLSearchParams({
        start_date: startDate,
        end_date: endDate,
      });

      const response = await fetch(
        `${this.apiUrl}/api/rooms/${roomTypeId}/available-dates?${params}`,
        {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
          },
        }
      );

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      return await response.json();
    } catch (error) {
      console.error('Erreur lors du chargement du calendrier:', error);
      return null;
    }
  }

  /**
   * Formater une date au format YYYY-MM-DD
   */
  formatDate(date) {
    return date instanceof Date
      ? date.toISOString().split('T')[0]
      : date;
  }

  /**
   * Obtenir le calendrier du mois en cours
   */
  async getCurrentMonthCalendar(roomTypeId) {
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);

    return this.getMonthlyCalendar(
      roomTypeId,
      this.formatDate(firstDay),
      this.formatDate(lastDay)
    );
  }

  /**
   * Obtenir le calendrier du mois suivant
   */
  async getNextMonthCalendar(roomTypeId) {
    const today = new Date();
    const nextMonth = new Date(today.getFullYear(), today.getMonth() + 1, 1);
    const lastDay = new Date(
      nextMonth.getFullYear(),
      nextMonth.getMonth() + 1,
      0
    );

    return this.getMonthlyCalendar(
      roomTypeId,
      this.formatDate(nextMonth),
      this.formatDate(lastDay)
    );
  }
}

/**
 * Composant UI - Affichage du calendrier de disponibilité
 * (Exemple pour Vue.js / React / Angular)
 */
class ReservationCalendarUI {
  constructor(roomTypeId, containerSelector, apiUrl) {
    this.roomTypeId = roomTypeId;
    this.container = document.querySelector(containerSelector);
    this.availability = new AvailabilityService(apiUrl);
    this.selectedDates = {
      checkIn: null,
      checkOut: null,
    };
  }

  /**
   * Initialiser et afficher le calendrier
   */
  async render() {
    const calendar = await this.availability.getCurrentMonthCalendar(this.roomTypeId);

    if (!calendar) {
      this.container.innerHTML = '<p>Impossible de charger le calendrier</p>';
      return;
    }

    this.renderCalendarTable(calendar);
  }

  /**
   * Rendre le tableau du calendrier
   */
  renderCalendarTable(calendar) {
    const html = `
      <div class="availability-calendar">
        <h3>${calendar.room_type.name}</h3>
        <p class="room-info">
          ${calendar.room_type.total_rooms} chambre(s) - 
          ${calendar.room_type.price_per_night.toLocaleString()} € / nuit
        </p>
        
        <table class="calendar-table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Jour</th>
              <th>Disponibles</th>
              <th>Total</th>
              <th>Taux d'occupation</th>
            </tr>
          </thead>
          <tbody>
            ${Object.values(calendar.calendar)
              .map(day => this.renderDayRow(day))
              .join('')}
          </tbody>
        </table>
      </div>
    `;

    this.container.innerHTML = html;
    this.attachEventListeners();
  }

  /**
   * Rendre une ligne du calendrier
   */
  renderDayRow(day) {
    const availabilityClass = day.is_available ? 'available' : 'unavailable';
    const occupancyColor = this.getOccupancyColor(day.occupancy_rate);

    return `
      <tr class="day-row ${availabilityClass}" data-date="${day.date}">
        <td class="date">${new Date(day.date).toLocaleDateString('fr-FR')}</td>
        <td class="day-name">${day.day_of_week}</td>
        <td class="available-count">${day.available_rooms}</td>
        <td class="total-count">${day.total_rooms}</td>
        <td class="occupancy">
          <div class="occupancy-bar">
            <div class="occupancy-fill" style="width: ${day.occupancy_rate}%; background-color: ${occupancyColor};"></div>
          </div>
          ${day.occupancy_rate.toFixed(0)}%
        </td>
      </tr>
    `;
  }

  /**
   * Obtenir la couleur en fonction du taux d'occupation
   */
  getOccupancyColor(occupancyRate) {
    if (occupancyRate < 25) return '#4CAF50'; // Vert
    if (occupancyRate < 50) return '#8BC34A'; // Vert clair
    if (occupancyRate < 75) return '#FFC107'; // Orange
    return '#F44336'; // Rouge
  }

  /**
   * Attacher les écouteurs d'événements
   */
  attachEventListeners() {
    const rows = this.container.querySelectorAll('.day-row.available');

    rows.forEach(row => {
      row.addEventListener('click', (e) => {
        const date = row.dataset.date;
        this.selectDate(date, row);
      });
    });
  }

  /**
   * Sélectionner une date
   */
  selectDate(date, element) {
    if (!this.selectedDates.checkIn) {
      this.selectedDates.checkIn = date;
      element.classList.add('selected-check-in');
    } else if (!this.selectedDates.checkOut) {
      if (date > this.selectedDates.checkIn) {
        this.selectedDates.checkOut = date;
        element.classList.add('selected-check-out');
        this.onDatesSelected();
      } else {
        alert('La date de départ doit être après la date d\'arrivée');
      }
    } else {
      // Réinitialiser la sélection
      this.selectedDates = {
        checkIn: date,
        checkOut: null,
      };
      this.render();
    }
  }

  /**
   * Callback lorsque les dates sont sélectionnées
   */
  async onDatesSelected() {
    console.log('Dates sélectionnées:', this.selectedDates);

    // Vérifier la disponibilité finale
    const availability = await this.availability.checkAvailability(
      this.roomTypeId,
      this.selectedDates.checkIn,
      this.selectedDates.checkOut
    );

    if (availability && availability.available) {
      console.log(`✅ ${availability.available_rooms} chambre(s) disponible(s)`);
      this.showReservationForm();
    } else {
      console.log('❌ Aucune chambre disponible');
      alert('Aucune chambre disponible pour ces dates');
      this.render();
    }
  }

  /**
   * Afficher le formulaire de réservation
   */
  showReservationForm() {
    const checkIn = new Date(this.selectedDates.checkIn).toLocaleDateString('fr-FR');
    const checkOut = new Date(this.selectedDates.checkOut).toLocaleDateString('fr-FR');

    alert(`
      ✅ Réservation possible!
      
      Arrivée: ${checkIn}
      Départ: ${checkOut}
      
      Cliquez sur "Réserver" pour continuer...
    `);

    // Émettre un événement ou appeler le formulaire de réservation
    window.dispatchEvent(
      new CustomEvent('reservation:ready', {
        detail: this.selectedDates,
      })
    );
  }
}

// Utilisation:
/*
const calendar = new ReservationCalendarUI(
  1, // room_type_id
  '#calendar-container',
  'https://api.example.com'
);
calendar.render();

// Écouter l'événement de réservation prête
window.addEventListener('reservation:ready', (e) => {
  console.log('Dates prêtes pour réservation:', e.detail);
  // Afficher le formulaire de réservation
});
*/
