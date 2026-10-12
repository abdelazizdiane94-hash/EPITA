// File: /EPITA/EPITA/projects/chronos-desk/public/js/sla_timer.js

// Fonctionnalité de gestion du timer SLA pour le suivi des accords de niveau de service
class SLATimer {
    constructor(slaDuration, displayElement) {
        this.slaDuration = slaDuration; // Durée SLA en secondes
        this.displayElement = displayElement; // Élément DOM pour afficher le timer
        this.timer = null; // Référence au timer
        this.remainingTime = slaDuration; // Temps restant
    }

    // Démarre le timer
    start() {
        this.updateDisplay();
        this.timer = setInterval(() => {
            this.remainingTime--;
            this.updateDisplay();

            // Vérifie si le temps est écoulé
            if (this.remainingTime <= 0) {
                this.stop();
                alert("Le temps SLA est écoulé !");
            }
        }, 1000); // Met à jour chaque seconde
    }

    // Arrête le timer
    stop() {
        clearInterval(this.timer);
        this.timer = null;
    }

    // Réinitialise le timer
    reset() {
        this.stop();
        this.remainingTime = this.slaDuration;
        this.updateDisplay();
    }

    // Met à jour l'affichage du timer
    updateDisplay() {
        const minutes = Math.floor(this.remainingTime / 60);
        const seconds = this.remainingTime % 60;
        this.displayElement.textContent = `${this.pad(minutes)}:${this.pad(seconds)}`;
    }

    // Ajoute un zéro devant les nombres inférieurs à 10
    pad(number) {
        return number < 10 ? '0' + number : number;
    }
}

// Exemple d'utilisation
document.addEventListener('DOMContentLoaded', () => {
    const slaDisplay = document.getElementById('sla-timer-display'); // Élément pour afficher le timer
    const slaTimer = new SLATimer(300, slaDisplay); // 5 minutes SLA
    slaTimer.start(); // Démarre le timer
});